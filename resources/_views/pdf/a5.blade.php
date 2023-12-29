<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Document</title>

<style type="text/css">
body {
	font-family: DejaVu Sans, sans-serif;
	box-sizing: border-box;
	line-height: 1;
}
br {
	line-height: 1;
}
</style>

</head>
<body>
<div style="width: 100%; margin: -30px; margin-bottom:-60px;">
	
	<div style="width: 535px; height: auto; display: inline-block; float: left; padding-right: 10px;  border-right: dashed 1px #eee;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 110px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 103px;">
					</div>
				</td>
				@endif

				@php
					if(!empty($output['brand_pic'])) $style = "width: 420px";
						else $style = "width: 100%";
				@endphp
				<td style="padding: 0; {{ $style }}">
					<div>
						<h1 style="font-size: 24px; letter-spacing: -2px; word-spacing: -3px; height: auto; max-height: 94px; overflow: hidden; padding: 0; margin: 0;">{{ $output['name'] }}</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td>
					<div style="text-align: center; width: 210px; height: 210px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block; width: auto; max-width: 210px; height: auto; max-height: 210px;">
						@endif
					</div>
				</td>
				<td style="width: 310px;">
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 5px 8px; margin: 0 25px 20px; background-color: red; color: #fff; border-radius: 7px; text-transform: uppercase; font-size: 28px; font-weight: bold; text-align: center;">
							{{ $output['type'] }}
						</div>
						@endif

						<div>

							@if(!empty($output['type']))
							<div style="font-size: 44px; color: #999; text-decoration: line-through; text-align: center; margin-bottom: 10px;">
								{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span>
							</div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 48px; font-weight: bold; color: red; text-align: center;">
								{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span>
							</div>
						</div>

					</div>

				</td>
			</tr>
		</table>

		<div style="width: 100%;">
			@if(!empty($output['advantages']))
			<div style="font-weight: bold; font-size: 12px; margin-bottom: 3px;">Преимущества:</div>
			<div style="font-size: 10px; line-height: 1; margin-bottom: 7px; height: auto; max-height: 127px; overflow: hidden;">
				{!! $output['advantages'] !!}
			</div>
			@endif
		</div>

		<div style="width: 100%; overflow: hidden; height: auto; max-height: 175px; margin-bottom: 10px;">
			<table style="width: 100%; overflow: hidden;">
				<tr>
					<td style="width: 50%; vertical-align: top;">
						<div">
							@if(!empty($output['characteristics']))
							<div style="font-weight: bold; font-size: 12px; margin-bottom: 3px;">Характеристики:</div>
							<div style="font-size: 10px; line-height: 1;">{!! $output['characteristics'] !!}</div>
							@endif
						</div>
					</td>
					<td style="width: 50%; vertical-align: top;">
						<div>
							@if(!empty($output['complect']))
							<div style="font-weight: bold; font-size: 12px; margin-bottom: 3px;">Комплектация:</div>
							<div style="font-size: 10px; line-height: 1;">{!! $output['complect'] !!}</div>
							@endif
						</div>
					</td>
				</tr>
			</table>
		</div>

		<div style="width: 100%; font-size: 12px; margin-bottom: 10px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if(!empty($output['line_checker']))
		<div style="background-color: red; color: #fff; font-size: 30px; font-weight: bold; text-transform: uppercase; padding: 5px 10px 7px; text-align: center; width: 100%;">
			{{ $output['red_string'] }}
		</div>
		@endif

	</div>

	<div style="width: 535px; height: auto; display: inline-block; float: right;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 110px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 103px;">
					</div>
				</td>
				@endif

				@php
					if(!empty($output['brand_pic'])) $style = "width: 420px";
						else $style = "width: 100%";
				@endphp
				<td style="padding: 0; {{ $style }}">
					<div>
						<h1 style="font-size: 24px; letter-spacing: -2px; word-spacing: -3px; height: auto; max-height: 94px; overflow: hidden; padding: 0; margin: 0;">{{ $output['name'] }}</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td>
					<div style="text-align: center; width: 210px; height: 210px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block; width: auto; max-width: 210px; height: auto; max-height: 210px;">
						@endif
					</div>
				</td>
				<td style="width: 310px;">
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 5px 8px; margin: 0 25px 20px; background-color: red; color: #fff; border-radius: 7px; text-transform: uppercase; font-size: 28px; font-weight: bold; text-align: center;">
							{{ $output['type'] }}
						</div>
						@endif

						<div>

							@if(!empty($output['type']))
							<div style="font-size: 44px; color: #999; text-decoration: line-through; text-align: center; margin-bottom: 10px;">
								{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span>
							</div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 48px; font-weight: bold; color: red; text-align: center;">
								{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span>
							</div>
						</div>

					</div>

				</td>
			</tr>
		</table>

		<div style="width: 100%;">
			@if(!empty($output['advantages']))
			<div style="font-weight: bold; font-size: 12px; margin-bottom: 3px;">Преимущества:</div>
			<div style="font-size: 10px; line-height: 1; margin-bottom: 7px; height: auto; max-height: 127px; overflow: hidden;">
				{!! $output['advantages'] !!}
			</div>
			@endif
		</div>

		<div style="width: 100%; overflow: hidden; height: auto; max-height: 175px; margin-bottom: 10px;">
			<table style="width: 100%; overflow: hidden;">
				<tr>
					<td style="width: 50%; vertical-align: top;">
						<div">
							@if(!empty($output['characteristics']))
							<div style="font-weight: bold; font-size: 12px; margin-bottom: 3px;">Характеристики:</div>
							<div style="font-size: 10px; line-height: 1;">{!! $output['characteristics'] !!}</div>
							@endif
						</div>
					</td>
					<td style="width: 50%; vertical-align: top;">
						<div>
							@if(!empty($output['complect']))
							<div style="font-weight: bold; font-size: 12px; margin-bottom: 3px;">Комплектация:</div>
							<div style="font-size: 10px; line-height: 1;">{!! $output['complect'] !!}</div>
							@endif
						</div>
					</td>
				</tr>
			</table>
		</div>

		<div style="width: 100%; font-size: 12px; margin-bottom: 10px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if(!empty($output['line_checker']))
		<div style="background-color: red; color: #fff; font-size: 30px; font-weight: bold; text-transform: uppercase; padding: 5px 10px 7px; text-align: center; width: 100%;">
			{{ $output['red_string'] }}
		</div>
		@endif

	</div>


</div>
</body>
</html>
{{-- @php dd($output) @endphp --}}
