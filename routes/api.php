<?php

use App\Helpers\Amocrm;
use App\Helpers\AmoHelper;
use DrewM\MailChimp\MailChimp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/amocrm')->group(function () {
    Route::post('/emailers/{stage}', function (Request $request, $stage) {
        $amo = new Amocrm();
        $helper = new AmoHelper();
        $statusInfo = null;

        if (isset($request['leads']['add'])) {
            $statusInfo = $request['leads']['add'][0];
            $status = 'add';
        } else if (isset($request['leads']['status'])) {
            $statusInfo = $request['leads']['status'][0];
            $status = 'moved';
        } else {
            abort('400');
        }

        $leadId = $statusInfo['id'];
        $lead = $amo->getLeadById($leadId);
        $contactId = $lead->main_contact_id;
        $contact = $amo->getContactById($contactId);
        $contactLang = $amo->getContactLang($contact);

        $email = $amo->getContactEmail($contact);

        if (!array_key_exists($stage, AmoHelper::$stages)) {
            abort(404);
        }

        $hash = null;

        if ($stage === 'questionnaire') {
            $hash = $helper->createUser($leadId);
        }

        $helper->sendFormToEmail($hash, $email, $stage, $contactLang);

        response()->json(['code' => 200], 200);
    });
    Route::prefix('/customers')->group(function () {
        Route::post('/', function (Request $request) {
            $amo = new Amocrm();
            $id = $request->get('customers')['status'][0]['id'];

            $customer = $amo->getCustomers([
                'id' => $id,
            ])[0];

            $amo->l($customer);
            $amo->l($customer->main_contact->id);

            $contact = $amo->getContactById($customer->main_contact->id);

            $amo->l($contact);

            $endSubscriptionsDay = $amo->getCustomFieldValue($amo->END_SUBSCRIPTIONS_DAY, $contact->custom_fields);

            $endSubscriptionsTimestamp = strtotime($endSubscriptionsDay);

            $amo->l($endSubscriptionsTimestamp);

            $amo->updateCustomers([[
                'id' => $customer->id,
                'updated_at' => time(),
                'next_date' => $endSubscriptionsTimestamp,
            ]]);
        });

        Route::post('/purchase', function (Request $request) {
            $amo = new Amocrm();
            $statusInfo = null;

            if (isset($request['leads']['add'])) {
                $statusInfo = $request['leads']['add'][0];
            } else if (isset($request['leads']['status'])) {
                $statusInfo = $request['leads']['status'][0];
            } else {
                abort('400');
            }

            $leadId = $statusInfo['id'];

            $lead = $amo->getLeadById($leadId);

            $amo->l($lead);

            $contactId = $lead->main_contact_id;

            $language = $amo->getCustomFieldValue($amo->LEAD_LANGUAGE, $lead->custom_fields);

            $amo->updateContacts([[
                'id' => $contactId,
                'updated_at' => time(),
                'custom_fields' => [[
                    'id' => $amo->CONTACT_LANGUAGE,
                    'values' => [[
                        'value' => ([
                            $amo->LEAD_LANG_EN => $amo->CONTACT_LANG_EN,
                            $amo->LEAD_LANG_AR => $amo->CONTACT_LANG_AR,
                            null => $amo->CONTACT_LANG_EN,
                        ])[$language],
                    ]]
                ]]
            ]]);

            $contact = $amo->getContactById($contactId);

            $amo->l($contact);

            $endSubscriptionsDay = $amo->getCustomFieldValue($amo->END_SUBSCRIPTIONS_DAY, $contact->custom_fields);

            $endSubscriptionsTimestamp = strtotime($endSubscriptionsDay);

            $amo->l($endSubscriptionsTimestamp);

            $tags = [];

            foreach ($lead->tags as $tag) {
                $tags[] = $tag->name;
            }

            $customer['add'] = [[
                'name' => $contact->name,
                'contacts_id' => [
                    $contact->id,
                ],
                'tags' => join(', ', $tags),
                'next_date' => $endSubscriptionsTimestamp
            ]];

            $amo->l($customer);

            $path = '/api/v2/customers';
            $customerAnswer = $amo->q($path, $customer);

            $amo->l($customerAnswer);


            $customer = $customerAnswer->_embedded->items[0];

            $transaction['add'] = [[
                'customer_id' => $customer->id,
                'date' => time(),
                'price' => $lead->price,
            ]];

            $path = '/api/v2/transactions';
            $transactionAnswer = $amo->q($path, $transaction);

            $amo->l($transactionAnswer);
        });

        Route::post('/get', function () {
            Artisan::call('amo-customers:start');
        });
    });

    Route::post('/mailchimp/lists/{listId}/members', function (Request $request, $listId) {
        $mailChimp = new MailChimp('989f3ac849db33d346f47093dc88b70b-us20');
        $amo = new Amocrm();
        $statusInfo = null;

        if (isset($request['leads']['add'])) {
            $statusInfo = $request['leads']['add'][0];
        } else if (isset($request['leads']['status'])) {
            $statusInfo = $request['leads']['status'][0];
        } else {
            abort('400');
        }

        $lead = $amo->getLeadById($statusInfo['id']);
        $contact = $amo->getContactById($lead->main_contact_id);
        $contactData = array_column($contact->custom_fields, 'values', 'name');

//  delete method
//        $subscriber_hash = $mailChimp->subscriberHash($contactData['Email'][0]->value);
//        $mailChimp->delete("lists/$listId/members/$subscriber_hash");

        $mailChimp->post("lists/$listId/members", [
            'email_address' => $contactData['Email'][0]->value,
            'status'        => 'subscribed',
            'merge_fields'  => [
                'NAME'      => $contact->name,
                'PHONE'     => $contactData['Phone'][0]->value,
                'LANGUAGE'  => $amo->getContactLang($contact),
            ],
        ]);

        if ($result = !$mailChimp->success()) {
            return $mailChimp->getLastError();
        }

        response()->json(['code' => 200], 200);
    });

    Route::prefix('/clients')->group(function () {
        Route::post('/acuity/created/', function (Request $request) {
            $amo = new Amocrm();
            $acuity = new AcuityScheduling([
                'userId' => env('ACUITY_USER_ID'),
                'apiKey' => env('ACUITY_API_KEY'),
            ]);

            $statusInfo = null;

            if (isset($request['leads']['add'])) {
                $statusInfo = $request['leads']['add'][0];
            } else if (isset($request['leads']['status'])) {
                $statusInfo = $request['leads']['status'][0];
            } else {
                abort('400');
            }

            $leadId = $statusInfo['id'];
            $lead = $amo->getLeadById($leadId);

            $contactId = $lead->main_contact_id;
            $contact = $amo->getContactById($contactId);

            $fio = explode(' ', $contact->name);
            $phone = null;

            foreach ($contact->custom_fields as $customField) {
                if (isset($customField->code) && $customField->code === 'PHONE') {
                    $phone = $customField->values[0]->value;
                }
            }

            $acuity->request('/clients', [
                'method' => 'POST',
                'data' => [
                    'firstName' => $fio[0],
                    'lastName' => $fio[1] ?? '...',
                    'email' => $amo->getContactEmail($contact),
                    'phone' => $phone,
                ],
            ]);
        });
    });

    Route::post('/leads-spreader', function (Request $request) {
        $amo = new Amocrm();

        $statusInfo = $request['leads']['add'][0];
        $leadId = $statusInfo['id'];

        $result = DB::table('leads_spreader')->select('id', DB::raw('min(count) as count'))->groupBy('id')->orderBy('count')->first();

        DB::table('leads_spreader')->where('id', $result->id)->update([
            'count' => $result->count + 1
        ]);

        $response = $amo->updateLeads([[
            'id' => $leadId,
            'updated_at' => time(),
            'responsible_user_id' => $result->id,
        ]]);

        $amo->l($response);
    });

});

Route::post('/customers', function (Request $request) {
    $amo = new Amocrm();
    $id = $request->get('customers')['status'][0]['id'];

    $customer = $amo->getCustomers([
        'id' => $id,
    ])[0];

    $amo->l($customer);
    $amo->l($customer->main_contact->id);

    $contact = $amo->getContactById($customer->main_contact->id);

    $amo->l($contact);

    $endSubscriptionsDay = $amo->getCustomFieldValue($amo->END_SUBSCRIPTIONS_DAY, $contact->custom_fields);

    $endSubscriptionsTimestamp = strtotime($endSubscriptionsDay);

    $amo->l($endSubscriptionsTimestamp);

    $amo->updateCustomers([[
        'id' => $customer->id,
        'updated_at' => time(),
        'next_date' => $endSubscriptionsTimestamp,
    ]]);
});

Route::group(['prefix' => '/acuity/webhook'], function () {
    Route::post('/created', 'AcuityController@created');
    Route::post('/closed', 'AcuityController@closed');
    Route::post('/updated', 'AcuityController@updated');
    Route::get('/cron', function () {
        Artisan::call('acuity:start');
    });
});

