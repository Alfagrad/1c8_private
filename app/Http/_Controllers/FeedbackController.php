<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request) {

        $captcha = \App::make('\App\Helpers\ReCaptchaHelper');

        $captchaState = $captcha->check($request->get('g-recaptcha-response'), $request->ip());

        if(!$captchaState){
            return redirect()->back()->with('note', 'Упс... Что-то пошло не так!<br><br>Попробуйте еще раз.');
        }

        // проверяем переменные
        $name = strip_tags(htmlspecialchars(trim($request->name)));
        $email = strip_tags(htmlspecialchars(trim($request->email)));
        $phone = strip_tags(htmlspecialchars(trim($request->phone)));
        $comment = strip_tags(htmlspecialchars(trim($request->comment)));

        $title = 'Сообщение из формы обратной связи сайта Alfastok.by';

        $body = "
<div>
    <p>Имя: <strong>{$name}</strong></p>
    <p>E-mail: <strong>{$email}</strong></p>
    <p>Телефон: <strong>{$phone}</strong></p>
    <p>Сообщение:<br><strong>{$comment}</strong></p>
</div>
        ";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: ".setting('email_from')."\r\n";

        $mailing = mail(setting('email_to_secretary'), $title, $body, $headers);

        if($mailing) $note = "Уважаемый(ая) $name!<br><br>Ваше сообщение отправлено!<br><br>Очень скоро мы свяжемся с Вами.";
            else $note = "Упс... Что-то пошло не так!<br><br>Попробуйте еще раз.";

        return redirect()->back()->with(['note' => $note]);
    }
}
