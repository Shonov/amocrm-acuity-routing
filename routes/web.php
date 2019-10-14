<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Helpers\Amocrm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


Route::get('/', function () {
    return view('welcome');
});


Route::prefix('/emailers')->group(function () {
    Route::get('/{email}', function (Request $request, $email) {
        $link = null;

        if ($hash = $request->query('user')) {
            $lang = strripos($email, '-ar') ? 'ar' : 'en';
            $link = route('questionnaire', $lang) . '?user=' . $hash;

            return view('emailers.' . $email, ['email' => [
                'web' => route('emailers', $email). '?user=' . $hash,
                'questionnaire' => $link
            ]]);
        }

        if (($email === 'online-payment' || $email === 'online-payment-ar') && $userEmail = $request->query('email')) {
            $lang = strripos($email, '-ar') ? 'ar' : 'eng';
            return view('emailers.' . $email, ['email' => [
                'web' => route('emailers', $email). '?email=' . $userEmail,
                'payment' => 'https://kuwait.nexfit.com/payment?lang=' . $lang . '&email=' . $userEmail
            ]]);
        }

        return view('emailers.' . $email, ['email' => $email]);
    })->name('emailers');
});

Route::prefix('/questionnaire')->group(function () {
    Route::post('/', function (Request $request) {
        $amo = new Amocrm();
        $acuity = new AcuityScheduling([
            'userId' => env('ACUITY_USER_ID'),
            'apiKey' => env('ACUITY_API_KEY'),
        ]);


        function getTranslations($steps, $separator = '<hr>')
        {
            $newLine = '<br>';

            if ($separator === PHP_EOL) {
                $newLine = $separator;
            }

            $body = '';

            foreach ($steps[1] as $key => $value) {
                $body .= returnTranslate($key) . ': ' . $value . $newLine;
            }

            $body .= $separator;

            foreach ($steps[2] as $key => $value) {
                if ($key === 'rateSelf') {
                    $body .= returnTranslate($key) . ': ' . join(", ", $value) . $newLine;

                    continue;
                } else if ($key === 'workouts_per_week') {
                    $body .= returnTranslate($key) . ': ' . returnTranslate($value) . $newLine;

                    continue;
                }
                $body .= returnTranslate($key) . ': ' . $value . $newLine;
            }
            $body .= $separator;

            $selectedValues = [];

            foreach ($steps[3] as $value) {
                $selectedValues[] = returnTranslate($value);
            }

            $body .= 'What is stopping you from exercising: ' . join(', ', $selectedValues) . $newLine;
            $body .= $separator;

            $selectedValues = [];

            foreach ($steps[4] as $value) {
                $selectedValues[] = returnTranslate($value);
            }

            $body .= 'Who would be happy seeing you healthy with an amazing physique: ' . join(', ', $selectedValues) . $newLine;
            $body .= $separator;

            $selectedValues = ['Nothing'];

            if ($steps[5] !== null) {
                foreach ($steps[5] as $value) {
                    $selectedValues[] = returnTranslate($value);
                }
            }

            $body .= 'Why do you want to do EMS Personal Fitness Training: ' . join(', ', $selectedValues) . $newLine;

            return $body;
        }

        function returnTranslate($field)
        {
            if (in_array($field, [
                'name',
                'email',
                'gender',
                'age',
                'height',
                'weight',
            ])) {
                return ucwords($field);
            }

            return [
                '1' => '1 hour',
                '2' => '2 hours',
                '3' => '3 hours',
                '4' => '5 hours',
                '5' => '8 hours',
                '6' => '10+ hours',
                'rateSelf' => 'How would you rate your current self',
                'body' => 'How is your body doing',
                'stamina' => 'What about your stamina',
                'workouts_per_week' => 'How many hours can you dedicate to workouts per week',
                'clock' => 'I don’t have the time',
                'family' => 'I have family responsibilities',
                'question' => 'I don’t know what to do',
                'money' => 'I don’t have the money',
                'quit' => 'I start but then quit',
                'wheel' => 'I’m ill or disabled',
                'fitness' => 'Nothing- I’m a fitness freak',
                'partner' => 'My Partner',
                'kids' => 'My Kids',
                'parents' => 'My Parents',
                'friends' => 'My Close Friends',
                'b_s' => 'My Brothers and/or Sisters',
                'b_c' => 'My Boss and Colleagues',
                'nothing' => 'None of these',
                'illness-free' => 'Staying illness-free',
                'fitting-jeans' => 'Fitting into my old jeans',
                'having-more' => 'Having more energy, stamina and endurance',
                'avoiding-muscle' => 'Avoiding Muscle and Joint pains',
                'feeling-young' => 'Feeling Young',
                'keep-mind' => 'Keeping my mind sharp',
                'more-active' => 'Having a more active social life and lifestyle',
                'stay-healthy' => 'Stay healthy for my family',
                'beach-body' => 'Get my summer beach body',
                'impress-partner' => 'Impress my partner',
                'null' => 'Nothing',
                null => 'Nothing',
            ][$field];
        }

        if (!$request->session()->has('user_id')) {
            $leadId = 0;
            $hash = '';

            $userId = DB::table('quiz_lead')->insertGetId([
                'lead_id' => $leadId,
                'lead_hash' => $hash,
            ]);

            $request->session()->put('user_id', $userId);
        }

        $userId = $request->session()->get('user_id');

        $steps = $request->session()->get('step');
        $steps[$request->get('step')] = $request->get('form_data');
        $request->session()->put('step', $steps);


        if (+$request->get('step') === 5) {
            $translation = getTranslations($request->session()->get('step'));

            Mail::send([], [], function ($message) use ($translation) {
                $message
                    ->to('admanager@digitalfalcon.ae')
                    ->subject('New Reply Questionnaire')
                    ->setBody($translation, 'text/html'); // for HTML rich messages
            });

            $leadId = DB::table('quiz_lead')->select('lead_id')->where('id', $userId)->get()[0]->lead_id;

            if ($leadId !== 0 && $leadId !== '0') {
                $lead = $amo->getLeadById($leadId);

                $contactId = $lead->main_contact_id;

                $contact = $amo->getContactById($contactId);

                $email = $amo->getContactEmail($contact);

            } else {
                $email = $steps[1]['email'];
                $lead = $amo->findLeadBy($email)->response->leads[0];
                $leadId = $lead->id;
            }

            if (!$amo->isAfterStage(+$lead->status_id, $amo->STATUS_QUESTIONNAIRE_DONE)) {
                $amo->moveLeadToStatus($leadId, $amo->STATUS_QUESTIONNAIRE_DONE);
            }

            $notes = PHP_EOL . '_______________' . PHP_EOL . 'Questionnaire: ' . PHP_EOL . getTranslations($request->session()->get('step'), PHP_EOL);

            $amo->q('/api/v2/notes', [
                'add' => [[
                    'element_id' => $lead->main_contact_id,
                    'element_type' => '1',
                    'text' => $notes,
                    'note_type' => '4',
                ]]
            ]);

            $clients = $acuity->request('/clients', [
                'query' => [
                    'search' => $email
                ]
            ]);

            foreach ($clients as $client) {
                $acuity->request('/clients', [
                    'method' => 'PUT',
                    'data' => [
                        'firstName' => $client['firstName'],
                        'lastName' => $client['lastName'],
                        'email' => $client['email'],
                        'phone' => $client['phone'],
                    ],
                    'query' => [
                        'firstName' => $client['firstName'],
                        'lastName' => $client['lastName'],
                        'email' => $client['email'],
                        'phone' => $client['phone'],
                        'notes' => $client['notes'] . PHP_EOL . $notes,
                    ],
                ]);
            }
        }

        return $request->session()->all();
    });

    Route::get('/{lang}', function (Request $request, $lang) {
        if ($hash = $request->query('user')) {
            $userId = DB::table('quiz_lead')->select('id')->where('lead_hash', '=', $hash)->get()[0]->id;
            $request->session()->put('user_id', $userId);
        }


        if (view()->exists('questionnaire.' . $lang)) {
            return view('questionnaire.' . $lang);
        }

        abort(404);
    })->name('questionnaire');
});
