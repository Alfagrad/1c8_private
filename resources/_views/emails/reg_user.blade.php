<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Регистрация  пользователя c существующим контрагентом:</h2>

<p><b>Имя: </b> {{$profile->name}} </p>
<p><b>Фамилия: </b> {{$profile->surname}} </p>
<p><b>Емайл: </b> {{$profile->email}} </p>
<p><b>Имя компании: </b> {{$profile->company_name}} </p>
<p><b>Адрес компании: </b> {{$profile->company_address}} </p>
<p><b>УНП: </b> {{$profile->unp}} </p>
<p><b>Р\С: </b> {{$profile->bank_account}} </p>
<p><b>Имя банка: </b> {{$profile->bank_name}} </p>

<h3>Контакты:</h3>
@foreach($profile->contact as $tempContact)
    <p>
        <b>Имя: </b> {{$tempContact->name}}
        <br>
        <b>Телефон: </b> {{$tempContact->phone}}
    </p>
@endforeach


<h3>Адреса:</h3>
@foreach($profile->address as $tempAddress)
    <p>
        <b>Адрес: </b> {{$tempAddress->address}}
        <br>
        <b>Коммент: </b> {{$tempAddress->comment}}
    </p>
@endforeach

<h3>Дополнительно:</h3>
@if($profile->trade_object)
    <p><b>Торговые объекты:</b> {{$profile->trade_object}}</p>
@endif

@if($profile->shops)
    <p><b>Магазины:</b> {{$profile->shops}}</p>
@endif

@if($profile->coverage_area)
    <p><b>Зона покрытия:</b> {{$profile->coverage_area}}</p>
@endif

<p><a href="{{route('regAccept', ['profileId' => $profile->id])}};">Подтвердить регистрацию</a></p>
<p><a href="{{route('regRefuse', ['profileId' => $profile->id])}};">Отказать в регистрации</a></p>
</body>
</html>