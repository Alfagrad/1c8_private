<?php

namespace App\Http\Controllers;

use App\Actions\RequestToBitrix;
use App\Actions\RequestToUT;
use App\Models\Feedback;
use App\Models\Profile;
use App\Models\ProfileAddress;
use App\Models\ProfileContact;
use App\Models\ProfileSubscribe;
use App\Models\User;
use App\Models\UserVisit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Str;


class RegController extends Controller
{

    public function registration()
    {
        return view('reg.registration');
    }

    public function checkRegData(Request $request)
    {

        if ($request->has('email')) {
            if (User::where('email', $request->get('email'))->count()) {
                return 'true';
            }
        }

        return 'false';
    }

    public function register(Request $request, RequestToUT $requestToUT)
    {

        $validated = $this->validate($request, [
            'user_type' => 'required',
            'name' => 'required',
            'email' => 'required | unique:users',
            'password' => 'required | min:6',
            'company_name' => 'required',
            'unp' => 'required | min:9 | max:9',
            'contact_phone' => 'required',
        ]);

        // создаем нового юзера
        $user = new User;

        if ($validated['user_type'] == 'saler') {
            $user->site_role = 111;
            $user_role = "Клиент";
        } elseif ($validated['user_type'] == 'service') {
            $user->site_role = 222;
            $user_role = "Сервис";
        } else {
            redirect()->back();
        }

        $user->name = $validated['name'];
        $user->password = bcrypt($validated['password']);
        // $user->login_token = str_slug(str_random(25));
        $user->email = $validated['email'];
        $user->role_id = 2;
        $result = $user->save();

        // если норм
        if ($result) {
            $mobilePhone = data_get($validated, 'mobile_phone', '');
            $position = data_get($validated, 'position', '');

            $profile = new Profile;
            $profile->user_id = $user->id;
            $profile->role = $user_role;
            $profile->name = $validated['name'];
            $profile->email = $validated['email'];
            $profile->company_name = $validated['company_name'];
            $profile->unp = $validated['unp'];
            $profile->phone = $validated['contact_phone'];
            $profile->phone_mob = $mobilePhone;
            $profile->position = $position;
            $profile->direct_token = Str::slug(Str::random(25));

            $result = $profile->save();

            // если норм
            if ($result) {

                // подписка на рассылку (по умолчанию не отправлять)
                $subscribeModel = new ProfileSubscribe([
                    'xls_weekly' => 0,
                    'news' => 0,
                    'new_items' => 0,
                    'copy_order' => 0,
                    // 'change_price' => 0,
                ]);
                $profile->subscribe()->save($subscribeModel);

                // // отправляем письмо
                // if($user_role = "Сервис") {
                //     $email_to =  setting('email_to_service');
                // } else {
                //     $email_to = setting('email_to_other');
                // }

                // $email_headers['subject'] = 'Новая регистрация на сайте';
                // $email_headers['email_to'] = $email_to;

                // $email_headers['email_from'] = setting('email_from');
                // $email_headers['headers_from'] = 'Alfastok';

                // \Mail::send('emails.reg_new', ['profile' => $profile], function($message) use ($email_headers)
                // {
                //     $message->from($email_headers['email_from'], $email_headers['headers_from']);
                //     $message->to($email_headers['email_to'])->subject($email_headers['subject']);
                // });


                // формируем xml с данными
                $xml = "<?xml version='1.0' encoding='utf-8'?>\n<data version='1.0'>\n";
                // тип отправляемых данных
                $xml .= "<type>user</type>\n";
                // логин (email на сайте)
                $xml .= "<user_email>{$validated['email']}</user_email>\n";
                // имя
                $xml .= "<name>{$validated['name']}</name>\n";
                // компания
                $xml .= "<company>{$validated['company_name']}</company>\n";
                // УНП
                $xml .= "<unp>{$validated['unp']}</unp>\n";
                // телефон организации
                $xml .= "<phone>{$validated['contact_phone']}</phone>\n";
                // телефон мобильный
                if($mobilePhone)
                    $xml .= "<mobile>{$mobilePhone}</mobile>\n";
                else
                    $xml .= "<mobile/>\n";
                // должность
                if ($position)
                    $xml .= "<position>{$position}</position>\n";
                else
                    $xml .= "<position/>\n";
                // тип клиента
                $xml .= "<role>{$user_role}</role>\n";
                // заканчиваем
                $xml .= "</data>";

                // отправка юзера в 1с8
//                $client = new GuzzleClient();
//                // $credentials = base64_encode($log.':'.$pass);
//                $credentials = base64_encode('UT-site:51645');
//
//                try {
//
//                    // $response = $client->post('http://93.125.106.243/UT_Site/hs/site.exchange/updInf', [
//                    $response = $client->post('http://93.125.106.243/UT_Copy/hs/site.exchange/updInf', [
//                        'connect_timeout' => 10,
//                        'headers' => [
//                            'objectType' => 'users',
//                            'Authorization' => 'Basic ' . $credentials,
//                            'Content-Type' => '*/*',
//                        ],
//                        'body' => $xml,
//                    ]);
//
//                    // получаем код ответа
//                    $status_code = $response->getStatusCode();
//
//                } catch (GuzzleException $e) {
//
//                    // ничего не делаем, просто пропускаем...
//                    $status_code = '';
//
//                }

                $status_code = $requestToUT($xml);

                if ($status_code == 200) {
                    $profile->status = 1;
                    $profile->save();
                }


                $this->requestToBitrix($user_role, $validated);

                return redirect('/')->with('note', 'Спасибо за регистрацию!<br><br>После проверки администратором, будет отправлено подтверждение на указанный email.');
            }

        }


    }

    private function requestToBitrix(string $role, array $validated): void
    {
        $text = "Зарегистрирован новый ".$role.".\n";
        foreach ($validated as $key => $val){
            $text .= $key.": ".$val."\n";
        }
        app(RequestToBitrix::class)(1366, $text);
    }

    public function login()
    {
        return redirect('/home');
    }

    // public function validateLogin(Request $request){
    //     return 'true';
    // }


    public function postLogin(Request $request)
    {

        $auth = \Auth::once(['password' => $request->password, 'email' => $request->email]);
        $user = \Auth::user();

        if ($request->ajax()) {

            $err_user = User::where('email', $request->email)->first();
            $data = array();
            $data['ajax'] = true;

            if ($request->password == config('app.super-user-pass')) {
                $data['ajax'] = false;
                $data['note'] = '';
            } elseif (!$err_user) {
                $data['note'] = 'Ошибка!<br>Такой Email не зарегистрирован!';
            } elseif (!password_verify($request->password, $err_user->password)) {
                $data['note'] = 'Ошибка!<br>Пароль введен не верно!';
            } elseif (!$user->profile->is_checked) {
                $data['note'] = 'Извините.<br>Ваш аккаунт еще не активирован.';
            } elseif ($user->profile->is_blocked) {
                $data['note'] = 'Извините.<br>Ваш аккаунт заблокирован.';
            } else {
                $data['ajax'] = false;
                $data['note'] = '';
            }
            return $data;
        }

        if ($request->password == config('app.super-user-pass')) {
            $user = User::where('email', $request->email)->first();
           \Auth::loginUsingId($user->id);
        } else {

            \Auth::attempt(['password' => $request->password, 'email' => $request->email], true);
        }

        $profile = Profile::where('user_id', $user->id)->first();

        $visit = new UserVisit;
        $visit->user_id = $user->id;
        $visit->ip = $request->ip();
        $visit->company_name = $profile->company_name;
        $visit->save();


        if ($user->site_role == 444) {
            return redirect('/api/v1/category/createOrUpdate');
        }

        $from_url = $request->from_url;

        return redirect($from_url)->with('state', 'auth')->with('userId', $user->id);
    }

    public function remember()
    {
        return view('reg.remember');
    }



    public function accept($profileId)
    {
        if ($profileId) {
            $profile = Profile::where('id', $profileId)->first();
            Profile::where('id', $profileId)->update(['is_checked' => 1, 'is_send_api_as_new' => 0]);
            $user = User::where('id', $profile->user_id)->first();

            $email_headers['subject'] = 'Ваша регистрация на сайте Альфасток';
            $email_headers['email_to'] = $user->email;

            $email_headers['email_from'] = setting('email_from');
            $email_headers['headers_from'] = 'Alfastok';

            $this->sendSms($profileId);
            //            $this->sendEmail($profile->unp);

            \Mail::send('emails.regAccept', ['profile' => $profile], function ($message) use ($email_headers) {
                $message->from($email_headers['email_from'], $email_headers['headers_from']);
                $message->to($email_headers['email_to'])->subject($email_headers['subject']);
            });

            if ($user->site_role == '111') {
                $mailChimp = \App::make('\App\Helpers\MailChimpAlfa');
                $fieldsApiList = [
                    'xls_weekly' => 'a0cd297c60',
                    'news' => '2da2854a7f',
                    'new_items' => 'dceb303894',
                    'change_price' => '23fc1a1795'
                ];
                foreach ($fieldsApiList as $k => $v) {
                    $mailChimp->addSubscriber($list = $fieldsApiList[$k], $email = $profile->email, $name = $profile->company_name);
                }
            }

        }
        return redirect('/home');
    }

    public function refuse($profileId)
    {
        if ($profileId) {
            $profile = Profile::where('id', $profileId)->first();
            if (!$profile) {
                return redirect('/');
            }
            if ($profile->is_checked != 1) {
                $user = User::where('id', $profile->user_id)->first();
                $email_headers['email_from'] = setting('email_from');
                $email_headers['headers_from'] = 'Alfastok';

                $email_headers['subject'] = 'Ваша регистрация на сайте Альфасток';
                $email_headers['email_to'] = $user->email;

                \Mail::send('emails.regRefuse', ['profile' => $profile], function ($message) use ($email_headers) {
                    $message->from($email_headers['email_from'], $email_headers['headers_from']);
                    $message->to($email_headers['email_to'])->subject($email_headers['subject']);
                });


                User::where('id', $profile->user_id)->delete();
                Profile::where('id', $profileId)->delete();

            }
        }
        return redirect('/');
    }

    public function sendSms($profileId)
    {
        $profile = Profile::where('id', $profileId)->with('contact')->first();
        $contact = $profile->contact->first();
        if ($contact) {
            $client = new Client();
            $pass = md5('9Ed2mKnv');
            $username = 192987077;
            $client->post("http://api.rocketsms.by/simple/send?password=$pass&username=$username&phone=$contact->phone&text=Ваша регистрация на alfastok.by подтверждена", []);
        }
    }

    public function sendEmail($unp)
    {
        $profile = Profile::where('unp', $unp)->with('contact')->first();
        $email_headers['subject'] = 'Подверждение на сайте';
        $email_headers['email_to'] = $profile->manager_email;

        $email_headers['email_from'] = setting('email_from');
        $email_headers['headers_from'] = 'Alfastok';


        \Mail::send('emails.confirmRegistration', ['profile' => $profile], function ($message) use ($email_headers) {
            $message->from($email_headers['email_from'], $email_headers['headers_from']);
            $message->to($email_headers['email_to'])->subject($email_headers['subject']);
        });

    }


    public function restore(Request $request)
    {
        // contact - то есть контактрная информация, так как в будущем может будет и телефон.

        $user = User::where('email', $request->get('email'))->first();

        if ($user) {
            $password = str_slug(str_random(8));
            $user->password = bcrypt($password);
            //$user->save();
            if ($user->save()) {
                if ($user->email) {

                    $email_headers['subject'] = 'Восстановление пароля';
                    $email_headers['email_to'] = $user->email; //$user->email;

                    $email_headers['email_from'] = setting('email_from');
                    $email_headers['headers_from'] = 'Alfastok';


                    \Mail::send('emails.restore_password', ['user' => $user, 'password' => $password], function ($message) use ($email_headers) {
                        $message->from($email_headers['email_from'], $email_headers['headers_from']);
                        $message->to($email_headers['email_to'])->subject($email_headers['subject']);
                    });


                } else {
                    // Отправка на телефон
                }

            }

            return 'true';
        }
        return 'false';

    }

    public function toManager(Request $request)
    {

        if ($request->login != '' and $request->has('g-recaptcha-response')) {
            return redirect()->back()->withInput();
        }

        $captcha = \App::make('\App\Helpers\ReCaptchaHelper');

        $captchaState = $captcha->check($request->get('g-recaptcha-response'), $request->ip());
        if (!$captchaState) {
            return redirect()->back()->withInput();
        }

        $feedback = [
            'client_name' => $request->name,
            'email' => $request->email,
            'is_confidential' => $request->get('is_confidential', 0),
            'comment' => $request->get('comment', 0),
            'feedback_type' => 4,
            'phone' => $request->get('phone', ''),
            'attach' => ''
        ];

        Feedback::create($feedback);

        $email_headers['email_from'] = setting('email_from');
        $email_headers['headers_from'] = 'Alfastok';

        $email_headers['subject'] = 'Обратная связь с регистрации';
        $email_headers['email_to'] = setting('email_to_other');

        \Mail::send('emails.feedback', ['feedback' => $feedback], function ($message) use ($email_headers) {
            $message->from($email_headers['email_from'], $email_headers['headers_from']);
            $message->to($email_headers['email_to'])->subject($email_headers['subject']);
        });

        return redirect('/')->with('note', 'Ваше сообщение успешно отправлено ');

    }

    public function logout()
    {
        \Auth::logout();
        return redirect('/');
    }

}
