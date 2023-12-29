<div class="item-page_comes-to-block">

	@foreach($spare_items as $sp_item)

		<div class="comes-to-item-line">
			<a href="{{ asset('catalogue/item/'.$sp_item->{'1c_id'}) }}" target="_blank">
				{{ $sp_item->name }}
			</a>
		</div>

	@endforeach


</div>
