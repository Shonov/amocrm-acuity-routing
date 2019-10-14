<?php
/**
 * Created by PhpStorm.
 * User: Виталий Шонов
 * Date: 19.05.2019
 * Time: 23:19
 */

namespace App\Http\Services;


use AcuityScheduling;
use App\Helpers\Amocrm;
use Carbon\Carbon;
use Illuminate\Http\Response;
use DateTime;
use App\Contracts\Facades\ChannelLog as Logger;

class AcuityService
{
    protected $amo;
    protected $acuity;
    public $FITNESS_FIRST_LABEL = 557877;

    public function __construct()
    {
        $this->amo = new Amocrm();
        $this->acuity = new AcuityScheduling([
            'userId' => env('ACUITY_USER_ID'),
            'apiKey' => env('ACUITY_API_KEY'),
        ]);
        Logger::channel('acuity');
    }

    public function isTrial($category)
    {
        return $category === 'Free Trial Session' || $category === 'Trial Session';
    }

    public function isMembership($category)
    {
        return $category === 'Membership Session';
    }

    public function created($id)
    {
        $appointment = $this->acuity->request('/appointments/' . $id);
        $lead = $this->checkInAmo($appointment, 'create');

        if ($lead === null) {
            return false;
        }

        if (!$this->amo->isAfterStage(+$lead->status_id, $this->amo->STATUS_FIRST_MEMBERSHIP_SCHEDULED) && $this->isMembership($appointment['category'])) {
            $this->amo->moveLeadToStatus($lead->id, $this->amo->STATUS_FIRST_MEMBERSHIP_SCHEDULED);
        }

        if (!$this->amo->isAfterStage(+$lead->status_id, $this->amo->STATUS_TRIAL_SCHEDULED) && $this->isTrial($appointment['category'])) {
            $this->amo->moveLeadToStatus($lead->id, $this->amo->STATUS_TRIAL_SCHEDULED);
        }

        return true;
    }

    public function updated($id)
    {
        Logger::info('[Updated] id ' . $id . ' start');
        $appointment = $this->acuity->request('/appointments/' . $id);

        $schedulingEndTime = new DateTime($appointment['datetime']);
        $schedulingEndTime->modify('+' . $appointment['duration'] . ' minutes');

        $lead = $this->checkInAmo($appointment, 'updated');

        if ($lead === null) {
            if ($appointment['labels'] === null) {
                Logger::info('[Updated] id ' . $id . ' does haven`t labels');
                return false;
            }

            foreach ($appointment['labels'] as $label) {
                if (!$this->isTrial($appointment['category'])) {
                    break;
                }

                if ((+$label['id']) === $this->FITNESS_FIRST_LABEL) {
                    if ($schedulingEndTime > new DateTime()) {
                        $statusId = $this->amo->STATUS_TRIAL_SCHEDULED;
                    } else {
                        $statusId = $this->amo->STATUS_TRIAL_COMPLETED;
                    }

                    Logger::info(['prepare to create: ' . $id]);

                    $this->amo->createLead(
                        'Fitness First Lead',
                        $statusId,
                        $appointment['firstName'] . ' ' . $appointment['lastName'],
                        $appointment['phone'],
                        $appointment['email']
                    );

                    Logger::info('[Updated] created lead in amo  for appointment id ' . $id);
                    break;
                }
            }

            $lead = $this->checkInAmo($appointment, 'updated');
        }

        $this->amo->l($appointment);
        $this->amo->l($lead);

        if ($appointment['notes'] === '') {
            Logger::info('[Updated] id ' . $id . ' does haven`t notes');
            return false;
        }

        $notes = 'Note from trainer: ' . PHP_EOL . $appointment['notes'];

        $this->amo->q('/api/v2/notes', [
            'add' => [[
                'element_id' => $lead->main_contact_id,
                'element_type' => '1',
                'text' => $notes,
                'note_type' => '4',
            ]]
        ]);

        return true;
    }

    public function closed($id)
    {
        $appointment = $this->acuity->request('/appointments/' . $id);
        $lead = $this->checkInAmo($appointment, 'closed');

        if (!$lead) {
            return false;
        }

        if ((!$this->amo->isAfterStage(+$lead->status_id, $this->amo->STATUS_NO_SHOW_CANCELLED) || $lead->status_id === $this->amo->STATUS_TRIAL_COMPLETED) && $this->isTrial($appointment['category'])) {
            $this->amo->moveLeadToStatus($lead->id, $this->amo->STATUS_NO_SHOW_CANCELLED);
        }

        if ((!$this->amo->isAfterStage(+$lead->status_id, $this->amo->STATUS_NO_SHOW_FIRST_MEMBERSHIP) || $lead->status_id === $this->amo->STATUS_FIRST_MEMBERSHIP_DONE) && $this->isMembership($appointment['category'])) {
            $this->amo->moveLeadToStatus($lead->id, $this->amo->STATUS_NO_SHOW_FIRST_MEMBERSHIP);
        }

        return true;
    }

    public function runCron()
    {
        $currentDay = (new DateTime())->format('Y-m-d');
        $response = $this->acuity->request('/appointments', [
            'query' => [
                'minDate' => $currentDay,
                'maxDate' => $currentDay,
            ]
        ]);

        if ($response === null || $response === []) {
            echo 'No Appointments today';
        }

        foreach ($response as $appointment) {

            $lead = $this->checkInAmo($appointment, 'cron');

            if ($lead === null) {
                continue;
            }

            $timeForLeadChange = new DateTime($appointment['datetime']);
            $timeForLeadChange->modify('+' . (+$appointment['duration'] + 90) . ' minutes');

            if ($timeForLeadChange <= new DateTime()) {

                if (!$this->amo->isAfterStage(+$lead->status_id, $this->amo->STATUS_TRIAL_COMPLETED) && $this->isTrial($appointment['category'])) {
                    $this->amo->moveLeadToStatus($lead->id, $this->amo->STATUS_TRIAL_COMPLETED);
                }

                if (!$this->amo->isAfterStage(+$lead->status_id, $this->amo->STATUS_FIRST_MEMBERSHIP_DONE) && $this->isMembership($appointment['category'])) {
                    $this->amo->moveLeadToStatus($lead->id, $this->amo->STATUS_FIRST_MEMBERSHIP_DONE);
                }

                echo 'Session complete' . '<br><br>';
            } else {
                echo 'Session incomplete' . '<br><br>';
            }
        }

        return true;
    }

    public function syncData()
    {
        $endDay = new Carbon();

        for ($startDay = (new Carbon())->addMonth(-1)->addDay(20); $startDay < $endDay; $startDay->addDay()) {
            $response = $this->acuity->request('/appointments', [
                'query' => [
                    'minDate' => $startDay->format('Y-m-d'),
                    'maxDate' => $startDay->format('Y-m-d'),
                    'showall' => true,
                ]
            ]);

            dump($startDay->format('Y-m-d'));

            if ($response === null || $response === []) {
                echo 'No Appointments today';
                continue;
            }

            foreach ($response as $appointment) {

                $lead = $this->checkInAmo($appointment, 'cron');

                if ($lead === null) {
                    continue;
                }

                if ($appointment['canceled'] === true) {
                    if (!$this->amo->isAfterStage(+$lead->status_id, $this->amo->STATUS_NO_SHOW_CANCELLED) && $this->isTrial($appointment['category'])) {
                        $this->amo->moveLeadToStatus($lead->id, $this->amo->STATUS_NO_SHOW_CANCELLED);
                        dump('push to NO-SHOW TRIAL');
                    }

                    if (!$this->amo->isAfterStage(+$lead->status_id, $this->amo->STATUS_NO_SHOW_FIRST_MEMBERSHIP) && $this->isMembership($appointment['category'])) {
                        $this->amo->moveLeadToStatus($lead->id, $this->amo->STATUS_NO_SHOW_FIRST_MEMBERSHIP);
                        dump('push to NO-SHOW MEMBERSHIP');
                    }

                    continue;
                }

                $timeForLeadChange = new DateTime($appointment['datetime']);
                $timeForLeadChange->modify('+' . (+$appointment['duration'] + 90) . ' minutes');

                if ($timeForLeadChange <= new DateTime()) {

                    if (!$this->amo->isAfterStage(+$lead->status_id, $this->amo->STATUS_TRIAL_COMPLETED) && $this->isTrial($appointment['category'])) {
                        $this->amo->moveLeadToStatus($lead->id, $this->amo->STATUS_TRIAL_COMPLETED);
                        dump('push to TRIAL COMPLETED');
                    }

                    if (!$this->amo->isAfterStage(+$lead->status_id, $this->amo->STATUS_FIRST_MEMBERSHIP_DONE) && $this->isMembership($appointment['category'])) {
                        $this->amo->moveLeadToStatus($lead->id, $this->amo->STATUS_FIRST_MEMBERSHIP_DONE);
                        dump('push to MEMBERSHIP COMPLETED');
                    }
                }
            }
        }

        die();

        return true;
    }

    private function checkInAmo($appointment, $type)
    {
        echo '[' . $type . '] Id: ' . $appointment['id'] . '<br>';

        if (isset($appointment['email']) && !empty($appointment['email'])) {
            $lead = $this->amo->findLeadBy($appointment['email']);
        }

        if (!isset($lead) && isset($appointment['phone']) && !empty($appointment['phone'])) {
            $lead = $this->amo->findLeadBy($appointment['phone']);
        }

        if (!isset($lead)) {
            echo '[' . $type . '] Can`t found' . '<br><br>';
            Logger::info('[' . $type . '] can`t found lead in amo for appointment id ' . $appointment['id']);
            return null;
        }

        if ($lead->response->leads[0]->pipeline_id !== 1795039) {
            dump('Lead on another stage');
            return null;
        }

        dump('Founded leads: ' . $lead->response->leads[0]->id);
        return $lead->response->leads[0];
    }
}
