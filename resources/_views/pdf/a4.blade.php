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
	<div style="width: 700px; height: auto; margin: -10px auto; padding:10px; position: relative;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 140px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 130px;">
					</div>
				</td>
				@endif

				@php
					if(!empty($output['brand_pic'])) $style = "width: auto";
						else $style = "width: 100%";
				@endphp
				<td style="padding: 0; {{ $style }}">
					<div>
						<h1 style="font-size: 32px; letter-spacing: -1.5px; height: auto; max-height: 125px; overflow: hidden; padding: 0; margin: 0">
							{{ $output['name'] }}
						</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td style="">
					<div style="text-align: center; width: 260px; height: 260px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block; width: auto; max-width: 260px; height: auto; max-height: 260px;">
						@endif
					</div>
				</td>
				<td style="width: 460px;">
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 5px 10px; margin: 0 40px 20px; background-color: red; color: #fff; border-radius: 10px; text-transform: uppercase; font-size: 40px; font-weight: bold; text-align: center">
							{{ $output['type'] }}
						</div>
						@endif

						<div>

							@if(!empty($output['type']))
							<div style="font-size: 60px; color: #999; text-decoration: line-through; text-align: center; margin-bottom: 10px;">
								{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span>
							</div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 60px; font-weight: bold; color: red; text-align: center;">
								{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span>
							</div>
						</div>

					</div>

				</td>
			</tr>
		</table>

		<div style="width: 100%;">
			@if(!empty($output['advantages']))
			<div style="font-weight: bold; margin-bottom: 5px;">Преимущества:</div>
			<div style="font-size: 14px; font-weight: normal; line-height: 12px; word-spacing: -2px; letter-spacing: -.3px; margin-bottom: 5px; height: auto; max-height: 170px; overflow: hidden;">
				{!! $output['advantages'] !!}
			</div>
			@endif
		</div>

		<div style="width: 100%; overflow: hidden; height: auto; max-height: 212px; margin-bottom: 10px;">
			<table style="width: 100%; overflow: hidden;">
				<tr>
					<td style="width: 50%; vertical-align: top;">
						<div">
							@if(!empty($output['characteristics']))
							<div style="font-weight: bold; margin-bottom: 3px;">Характеристики:</div>
							<div style="font-size: 14px; line-height: 12px; word-spacing: -2px; letter-spacing: -.3px;">{!! $output['characteristics'] !!}</div>
							@endif
						</div>
					</td>
					<td style="width: 50%; vertical-align: top;">
						<div>
							@if(!empty($output['complect']))
							<div style="font-weight: bold; margin-bottom: 3px;">Комплектация:</div>
							<div style="font-size: 14px; line-height: 12px; word-spacing: -2px; letter-spacing: -.3px;">{!! $output['complect'] !!}</div>
							@endif
						</div>
					</td>
				</tr>
			</table>
		</div>


		<div style="width: 100%; margin-bottom: 10px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if(!empty($output['line_checker']))
		<div style="background-color: red; color: #fff; font-size: 48px; font-weight: bold; text-transform: uppercase; padding: 5px 15px 10px; text-align: center;  width: 100%;">{{ $output['red_string'] }}</div>
		@endif


	</div>

</body>
</html>
{{-- @php dd($output) @endphp --}}
