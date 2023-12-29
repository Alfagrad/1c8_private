@php
	if($type == 'products') {
		$current_type = "Товарах";
		$another_type = "Запчастях";
		$search_type = "spares";
	} else {
		$current_type = "Запчастях";
		$another_type = "Товарах";
		$search_type = "products";
	}
@endphp
<div class="search-answer">
	<div>
		По запросу:
		<br>
		<strong>&laquo;{{ $searchKeyword }}&raquo;</strong>
		<br>
		в <strong>{{ $current_type }}</strong> ничего не найдено!
		<br>
		Попробуйте в <a href="{{ asset('service/search?type='.$search_type.'&search_keywords='.$searchKeyword) }}" title="Искать в {{ $another_type }}"><strong>{{ $another_type }}</strong></a>
	</div>
</div>