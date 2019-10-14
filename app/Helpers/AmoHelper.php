<?php
/**
 * Created by PhpStorm.
 * User: Виталий Шонов
 * Date: 17.05.2019
 * Time: 19:06
 */

namespace App\Helpers;

use App\Mail\CustomMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Contracts\Facades\ChannelLog as Logger;

class AmoHelper
{
    public static $stages = [
        'apologies' => [
            'English' => [
                'subject' => 'We apologize for delay',
                'template' => 'apologies',
            ],
            'Arabic' => [
                'subject' => 'نعتذر عن التأخير',
                'template' => 'apologies-ar',
            ],
        ],
        'didnt-buy' => [
            'English' => [
                'subject' => 'Nexfit Kuwait discount 45 percent',
                'template' => 'didnt-buy',
            ],
            'Arabic' => [
                'subject' => 'خصم 45% في نكسفيت الكويت',
                'template' => 'didnt-buy-ar',
            ],
        ],
        'no-show-trial' => [
            'English' => [
                'subject' => 'You didn’t show up for your trial session',
                'template' => 'no-show-trial',
            ],
            'Arabic' => [
                'subject' => 'لقد تغيبت عن حضور جلستك التجريبية',
                'template' => 'no-show-trial-ar',
            ],
        ],
        'questionnaire' => [
            'English' => [
                'subject' => 'Your Questionnaire Link',
                'template' => 'questionnaire',
            ],
            'Arabic' => [
                'subject' => 'رابط الاستبيان',
                'template' => 'questionnaire-ar',
            ],
        ],
        'questionnaire-done' => [
            'English' => [
                'subject' => 'Questionnaire Received',
                'template' => 'questionnaire-done',
            ],
            'Arabic' => [
                'subject' => 'اكتمل الاستبيان',
                'template' => 'questionnaire-done-ar',
            ],
        ],
        'trial-completed' => [
            'English' => [
                'subject' => 'EMS Personal Training - Trial Session Completed',
                'template' => 'trial-completed',
            ],
            'Arabic' => [
                'subject' => 'التدريبات الشخصية بالتحفيز الكهربائي للعضلات - اكتملت الجلسة التجريبية',
                'template' => 'trial-completed-ar',
            ],
        ],
        'trial-scheduled' => [
            'English' => [
                'subject' => 'EMS Personal Fitness Training - Trial Scheduled',
                'template' => 'trial-scheduled',
            ],
            'Arabic' => [
                'subject' => 'تدريبات اللياقة البدنية بالتحفيز الكهربائي للعضلات - تم جدولة الجلسة التجريبية',
                'template' => 'trial-scheduled-ar',
            ],
        ],
        'welcome' => [
            'English' => [
                'subject' => 'Welcome to Nexfit Kuwait EMS',
                'template' => 'welcome',
            ],
            'Arabic' => [
                'subject' => 'مرحبًا بك في نكسفيت الكويت لتدريبات التحفيز الكهربائي للعضلات',
                'template' => 'welcome-ar',
            ],
        ],
        'online-payment' => [
            'English' => [
                'subject' => 'Your online payment link',
                'template' => 'online-payment',
            ],
            'Arabic' => [
                'subject' => 'رابط الدفع عبر الإنترنت',
                'template' => 'online-payment-ar',
            ],
        ],
        'payment-success' => [
            'English' => [
                'subject' => 'Your online payment was successful',
                'template' => 'payment-success',
            ],
            'Arabic' => [
                'subject' => 'تم الدفع عبر الإنترنت بنجاح',
                'template' => 'payment-success-ar',
            ],
        ],
    ];
    public $currentStage;

    public function __construct()
    {
        Logger::channel('amo');
    }

    public function createUser($leadId): string
    {
        $hash = $leadId . time();

        DB::table('quiz_lead')->insert([
            'lead_id' => $leadId,
            'lead_hash' => $hash,
        ]);

        return $hash;
    }

    public function sendFormToEmail($hash, $to, $mailStage, $lang): bool
    {
        $result = self::$stages[$mailStage][$lang];

        $mail = new CustomMail('emailers.' . $result['template'], $mailStage, $result['subject']);
        $mail->addBcc(env('AMO_URL'));

        $languages = [
            'English' => 'en',
            'Arabic' => 'ar',
        ];

        if ($mailStage === 'questionnaire') {
            $links = [
                'web' => route('emailers', $result['template']) . '?user=' . $hash,
                'questionnaire' => route($mailStage, $languages[$lang]) . '?user=' . $hash,
            ];

            $mail->viewParam = $links;
            Mail::to($to)->send($mail);
            return true;
        }

        if ($mailStage === 'online-payment') {
            $links = [
                'web' => route('emailers', $result['template']) . '?email=' . $to,
                'payment' => 'https://kuwait.nexfit.com/payment?lang=' . $languages[$lang] . '&email=' . $to,
            ];

            $mail->viewParam = $links;
            Mail::to($to)->send($mail);
            return true;
        }

        Mail::to($to)->send($mail, ['email' => $mailStage]);
        return true;
    }

    public function runCron()
    {
        $amo = new Amocrm();

        $rules = [[
            'status_id' => $amo->STAGE_4_WEEKS,
            'next_date' => '+4 week',
        ], [
            'status_id' => $amo->STAGE_3_WEEKS,
            'next_date' => '+3 week',
        ], [
            'status_id' => $amo->STAGE_2_WEEKS,
            'next_date' => '+2 week',
        ], [
            'status_id' => $amo->STAGE_1_WEEKS,
            'next_date' => '+1 week',
        ], [
            'status_id' => $amo->STAGE_MEMBERSHIP_FINISHED,
            'next_date' => 'now',
        ]];
        $toUpdate = [];

        foreach ($rules as $rule) {
            $from = date('d-m-Y', strtotime($rule['next_date'] . ' -6 day'));
            $to = date('d-m-Y', strtotime($rule['next_date']));

            $amo->l([
                'next_date' => $rule['next_date'],
                'from' => (new Carbon($from))->timestamp,
                'to' => (new Carbon($to))->timestamp,
            ]);


            $customers = $amo->getCustomers([
                'filter' => [
                    'next_date' => [
                        'from' => (new Carbon($from))->timestamp,
                        'to' => (new Carbon($to))->timestamp,
                    ],
                ]
            ]);

            dump($customers);

            if ($customers === null) {
                continue;
            }

            foreach ($customers as $customer) {
                if (in_array($customer->status_id, [
                        $amo->STAGE_PURCHASED, $amo->STAGE_INTERESTED_TO_RENEW, $amo->STAGE_NO_MORE_INTEREST
                    ], true) || $customer->status_id === $rule['status_id']) {
                    continue;
                }

                $toUpdate[] = [
                    'id' => $customer->id,
                    'updated_at' => time(),
                    'status_id' => $rule['status_id'],
                ];
            }
        }

        $amo->l($toUpdate);

        $updateCustomers = $amo->updateCustomers($toUpdate);

//        $amo->l($updateCustomers);
        return true;
    }
}
