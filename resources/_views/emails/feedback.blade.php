<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<p><b> Имя: </b> {{$feedback['client_name']}} </p>
<p><b> Email: </b> {{$feedback['email']}} </p>
@if(isset($feedback['company_name']))
    <p><b> Название компании: </b> {{$feedback['company_name']}} </p>
@endif
@if(isset($feedback['phone']))
    <p><b> Телефон: </b> {{$feedback['phone']}} </p>
@endif

@if(isset($feedback['contacts']) and $feedback['contacts'])
    @foreach($feedback['contacts'] as $c)
        <p>{{$c->phone}} {{$c->name}} </p>
    @endforeach

@endif


<br>
@if(isset($feedback['item_id']))
    <p><b> Код товара: </b> {{$feedback['item_id']}} </p>
@endif
@if(isset($feedback['item_name']))
    <p><b> Имя товара: </b> {{$feedback['item_name']}} </p>
@endif

<p><b> Сообщение: </b> {{$feedback['comment']}} </p>
<p><b> Конфиденциально: </b> @if($feedback['is_confidential']) Да @else Нет @endif </p>


</body>
</html>