<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Пользователь изменил свои регистрационные данные:</h2>

<p><b @if($tempProfile->name != $realProfile->name) style="color: red;" @endif> Имя: </b> {{$tempProfile->name}}</p>
@if($tempProfile->name != $realProfile->name)<p><b> Старое имя: </b> {{$realProfile->name}}</p>@endif
<p><b> Емайл: </b> {{$realProfile->email}} </p>

<p><b @if($tempProfile->company_name != $realProfile->company_name) style="color: red;" @endif> Название компании: </b> {{$tempProfile->company_name}} </p>
@if($tempProfile->company_name != $realProfile->company_name)<p><b> Старое название компании:: </b> {{$realProfile->company_name}}</p>@endif

<p><b>  Адрес компании: </b> {{$realProfile->company_address}} </p>
<p><b>  УНП: </b> {{$realProfile->unp}} </p>

<p><b @if($tempProfile->bank_account != $realProfile->bank_account) style="color: red;" @endif>  Р\С: </b> {{$tempProfile->bank_account}} </p>
@if($tempProfile->bank_account != $realProfile->bank_account)<p><b > Старый Р\С:: </b> {{$realProfile->bank_account}}</p>@endif

<p><b @if($tempProfile->bank_name != $realProfile->bank_name) style="color: red;" @endif>  Имя банка: </b> {{$tempProfile->bank_name}} </p>
@if($tempProfile->bank_name != $realProfile->bank_name)<p><b> Старое имя банка: </b> {{$realProfile->bank_name}}</p>@endif

<p><b @if($tempProfile->trade_object != $realProfile->trade_object) style="color: red;" @endif>  Торговые объекты: </b> {{$tempProfile->trade_object}} </p>
@if($tempProfile->trade_object != $realProfile->trade_object)<p><b> Старые торговые объекты: </b> {{$realProfile->trade_object}}</p>@endif

<p><b @if($tempProfile->shops != $realProfile->shops) style="color: red;" @endif>  Магазины: </b> {{$tempProfile->shops}} </p>
@if($tempProfile->shops != $realProfile->shops)<p><b> Старые магазины: </b> {{$realProfile->shops}}</p>@endif

<p><b @if($tempProfile->coverage_area != $realProfile->coverage_area) style="color: red;" @endif>  Зона покрытия: </b> {{$tempProfile->coverage_area}} </p>
@if($tempProfile->coverage_area != $realProfile->coverage_area)<p><b> Старая зона покрытия: </b> {{$realProfile->coverage_area}}</p>@endif

<h3>Контакты :</h3>
<b>Старые</b> <br>
@foreach($realProfile->contact as $realContact)
    <p>
        <b>Имя:</b> {{$realContact->name}}
        <br>
        <b>Телефон:</b> {{$realContact->phone}}
    </p>

@endforeach
<br>
<b>Новые или измененые</b><br>
<?php $isNewContact = 1?>
@foreach($tempProfile->contact as $tempContact)
    @foreach($realProfile->contact as $realContact)
        @if(($tempContact->name == $realContact->name) and ($tempContact->phone == $realContact->phone))
            <?php $isNewContact = 0?>
        @endif
    @endforeach

    @if($isNewContact)
        <p>
            <b @if($isNewContact) style="color: red;" @endif>  Имя: </b> {{$tempContact->name}}
            <br>
            <b @if($isNewContact) style="color: red;" @endif>  Телефон: </b> {{$tempContact->phone}}
        </p>
    @endif
    <?php $isNewContact = 1?>

@endforeach

<h3>Адреса:</h3>

<b>Старые</b><br>
@foreach($realProfile->address as $realAddress)
    <p>
        <b>  Адрес: </b> {{$realAddress->address}}
        <br>
        <b>  Коммент: </b> {{$realAddress->comment}}
    </p>

@endforeach
<br>
<b>Новые или измененые</b><br>
<?php $isNewAddress = 1?>
@foreach($tempProfile->address as $tempAddress)
    @foreach($realProfile->address as $realAddress)
        @if(($tempAddress->address == $realAddress->address) and ($tempAddress->comment == $realAddress->comment))
            <?php $isNewAddress = 0?>
        @endif
    @endforeach
    @if($isNewAddress)
        <p>
            <b @if($isNewAddress) style="color: red;" @endif>  Адрес: </b> {{$tempAddress->address}}
            <br>
            <b @if($isNewAddress) style="color: red;" @endif>  Коммент: </b> {{$tempAddress->comment}}
        </p>
    @endif
    <?php $isNewAddress = 1?>
@endforeach


<p><a href="{{route('profileAccept', ['profileId' => $realProfile->id])}};">Подтвердить изменения</a></p>
<p><a href="{{route('profileRefuse', ['profileId' => $realProfile->id])}};">Отказать в изменениях</a></p>
</body>
</html>