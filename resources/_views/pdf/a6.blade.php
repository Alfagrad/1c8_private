<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Document</title>

<style type="text/css">
body {
	font-family: DejaVu Sans, sans-serif;
	box-sizing: border-box;
}
</style>

</head>
<body>
<div style="width: 700px; height: auto; margin:-10px auto -20px;">
	
	<div style="width: 338px; height: auto; margin: 0; padding: 5px; display: inline-block; float: left; overflow: hidden; border: dashed 1px #eee;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 70px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 65px;">
					</div>
				</td>
				@endif

				<td style="padding: 0;">
					<div>
						<h1 style="font-size: 16px; letter-spacing: -1.1px; word-spacing: -2px; height: auto; max-height: 62px; overflow: hidden; line-height: 14px; margin: 0;">
							{{ $output['name'] }}
						</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 120px;">
					<div style="text-align: center; width: 120px; height: 120px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block; width: auto; max-width: 120px; height: auto; max-height: 120px;">
						@endif
					</div>
				</td>
				<td>
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 1px 5px 3px; margin: 0 3px 3px; background-color: red; color: #fff; border-radius: 5px; text-transform: uppercase; font-size: 18px; font-weight: bold; text-align: center;">{{ $output['type'] }}</div>
						@endif

						<div>

							@if(!empty($output['type']))
							<div style="font-size: 30px; color: #999; text-decoration: line-through; text-align: center;">{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span></div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 34px; font-weight: bold; color: red; text-align: center; margin-top: -10px;">{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span></div>
						</div>

					</div>

				</td>
			</tr>
		</table>

		<div style="width: 100%;">
			@if(!empty($output['advantages']))
			<div style="font-weight: bold; margin-bottom: 1px; font-size: 8px;">
				Преимущества:
			</div>
			<div style="font-size: 7px; line-height: 7px; letter-spacing: -.3px; height: auto; max-height: 89px; overflow: hidden;">
				{!! $output['advantages'] !!}
			</div>
			@endif
		</div>

		<div style="width: 100%; overflow: hidden; height: auto; max-height: 122px; margin: -2px 0 2px 0;">
			<table style="width: 100%; overflow: hidden;">
				<tr>
					<td style="width: 50%; vertical-align: top;">
						<div">
							@if(!empty($output['characteristics']))
							<div style="font-weight: bold; margin-bottom: 2px; font-size: 8px;">Характеристики:</div>
							<div style="font-size: 7px; line-height: 7px; letter-spacing: -.3px; margin-top: -3px;">
								{!! $output['characteristics'] !!}
							</div>
							@endif
						</div>
					</td>
					<td style="width: 50%; vertical-align: top;">
						<div>
							@if(!empty($output['complect']))
							<div style="font-weight: bold; margin-bottom: 2px; font-size: 8px;">Комплектация:</div>
							<div style="font-size: 7px; line-height: 7px; letter-spacing: -.3px; margin-top: -3px;">
								{!! $output['complect'] !!}
							</div>
							@endif
						</div>
					</td>
				</tr>
			</table>
		</div>

		<div style="width: 100%; font-size: 8px; margin-bottom: 2px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if(!empty($output['line_checker']))
		<div style="background-color: red; color: #fff; font-size: 20px; font-weight: bold; text-transform: uppercase; padding: 0 5px 5px; text-align: center; width: 100%;">{{ $output['red_string'] }}</div>
		@endif

	</div>

	<div style="width: 338px; height: auto; margin: 0; padding: 5px; display: inline-block; float: right; overflow: hidden; border: dashed 1px #eee;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 70px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 65px;">
					</div>
				</td>
				@endif

				<td style="padding: 0;">
					<div>
						<h1 style="font-size: 16px; letter-spacing: -1.1px; word-spacing: -2px; height: auto; max-height: 62px; overflow: hidden; line-height: 14px; margin: 0;">
							{{ $output['name'] }}
						</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 120px;">
					<div style="text-align: center; width: 120px; height: 120px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block; width: auto; max-width: 120px; height: auto; max-height: 120px;">
						@endif
					</div>
				</td>
				<td>
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 1px 5px 3px; margin: 0 3px 3px; background-color: red; color: #fff; border-radius: 5px; text-transform: uppercase; font-size: 18px; font-weight: bold; text-align: center;">{{ $output['type'] }}</div>
						@endif

						<div>

							@if(!empty($output['type']))
							<div style="font-size: 30px; color: #999; text-decoration: line-through; text-align: center;">{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span></div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 34px; font-weight: bold; color: red; text-align: center; margin-top: -10px;">{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span></div>
						</div>

					</div>

				</td>
			</tr>
		</table>

		<div style="width: 100%;">
			@if(!empty($output['advantages']))
			<div style="font-weight: bold; margin-bottom: 1px; font-size: 8px;">
				Преимущества:
			</div>
			<div style="font-size: 7px; line-height: 7px; letter-spacing: -.3px; height: auto; max-height: 89px; overflow: hidden;">
				{!! $output['advantages'] !!}
			</div>
			@endif
		</div>

		<div style="width: 100%; overflow: hidden; height: auto; max-height: 122px; margin: -2px 0 2px 0;">
			<table style="width: 100%; overflow: hidden;">
				<tr>
					<td style="width: 50%; vertical-align: top;">
						<div">
							@if(!empty($output['characteristics']))
							<div style="font-weight: bold; margin-bottom: 2px; font-size: 8px;">Характеристики:</div>
							<div style="font-size: 7px; line-height: 7px; letter-spacing: -.3px; margin-top: -3px;">
								{!! $output['characteristics'] !!}
							</div>
							@endif
						</div>
					</td>
					<td style="width: 50%; vertical-align: top;">
						<div>
							@if(!empty($output['complect']))
							<div style="font-weight: bold; margin-bottom: 2px; font-size: 8px;">Комплектация:</div>
							<div style="font-size: 7px; line-height: 7px; letter-spacing: -.3px; margin-top: -3px;">
								{!! $output['complect'] !!}
							</div>
							@endif
						</div>
					</td>
				</tr>
			</table>
		</div>

		<div style="width: 100%; font-size: 8px; margin-bottom: 2px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if(!empty($output['line_checker']))
		<div style="background-color: red; color: #fff; font-size: 20px; font-weight: bold; text-transform: uppercase; padding: 0 5px 5px; text-align: center; width: 100%;">{{ $output['red_string'] }}</div>
		@endif

	</div>

	<div style="clear: both;"></div>

	<div style="width: 338px; height: auto; margin: 0; padding: 5px; display: inline-block; float: left; overflow: hidden; border: dashed 1px #eee;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 70px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 65px;">
					</div>
				</td>
				@endif

				<td style="padding: 0;">
					<div>
						<h1 style="font-size: 16px; letter-spacing: -1.1px; word-spacing: -2px; height: auto; max-height: 62px; overflow: hidden; line-height: 14px; margin: 0;">
							{{ $output['name'] }}
						</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 120px;">
					<div style="text-align: center; width: 120px; height: 120px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block; width: auto; max-width: 120px; height: auto; max-height: 120px;">
						@endif
					</div>
				</td>
				<td>
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 1px 5px 3px; margin: 0 3px 3px; background-color: red; color: #fff; border-radius: 5px; text-transform: uppercase; font-size: 18px; font-weight: bold; text-align: center;">{{ $output['type'] }}</div>
						@endif

						<div>

							@if(!empty($output['type']))
							<div style="font-size: 30px; color: #999; text-decoration: line-through; text-align: center;">{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span></div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 34px; font-weight: bold; color: red; text-align: center; margin-top: -10px;">{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span></div>
						</div>

					</div>

				</td>
			</tr>
		</table>

		<div style="width: 100%;">
			@if(!empty($output['advantages']))
			<div style="font-weight: bold; margin-bottom: 1px; font-size: 8px;">
				Преимущества:
			</div>
			<div style="font-size: 7px; line-height: 7px; letter-spacing: -.3px; height: auto; max-height: 89px; overflow: hidden;">
				{!! $output['advantages'] !!}
			</div>
			@endif
		</div>

		<div style="width: 100%; overflow: hidden; height: auto; max-height: 122px; margin: -2px 0 2px 0;">
			<table style="width: 100%; overflow: hidden;">
				<tr>
					<td style="width: 50%; vertical-align: top;">
						<div">
							@if(!empty($output['characteristics']))
							<div style="font-weight: bold; margin-bottom: 2px; font-size: 8px;">Характеристики:</div>
							<div style="font-size: 7px; line-height: 7px; letter-spacing: -.3px; margin-top: -3px;">
								{!! $output['characteristics'] !!}
							</div>
							@endif
						</div>
					</td>
					<td style="width: 50%; vertical-align: top;">
						<div>
							@if(!empty($output['complect']))
							<div style="font-weight: bold; margin-bottom: 2px; font-size: 8px;">Комплектация:</div>
							<div style="font-size: 7px; line-height: 7px; letter-spacing: -.3px; margin-top: -3px;">
								{!! $output['complect'] !!}
							</div>
							@endif
						</div>
					</td>
				</tr>
			</table>
		</div>


		<div style="width: 100%; font-size: 8px; margin-bottom: 2px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if(!empty($output['line_checker']))
		<div style="background-color: red; color: #fff; font-size: 20px; font-weight: bold; text-transform: uppercase; padding: 0 5px 5px; text-align: center; width: 100%;">{{ $output['red_string'] }}</div>
		@endif

	</div>

	<div style="width: 338px; height: auto; margin: 0; padding: 5px; display: inline-block; float: right; overflow: hidden; border: dashed 1px #eee;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 70px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 65px;">
					</div>
				</td>
				@endif

				<td style="padding: 0;">
					<div>
						<h1 style="font-size: 16px; letter-spacing: -1.1px; word-spacing: -2px; height: auto; max-height: 62px; overflow: hidden; line-height: 14px; margin: 0;">
							{{ $output['name'] }}
						</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 120px;">
					<div style="text-align: center; width: 120px; height: 120px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block; width: auto; max-width: 120px; height: auto; max-height: 120px;">
						@endif
					</div>
				</td>
				<td>
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 1px 5px 3px; margin: 0 3px 3px; background-color: red; color: #fff; border-radius: 5px; text-transform: uppercase; font-size: 18px; font-weight: bold; text-align: center;">{{ $output['type'] }}</div>
						@endif

						<div>

							@if(!empty($output['type']))
							<div style="font-size: 30px; color: #999; text-decoration: line-through; text-align: center;">{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span></div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 34px; font-weight: bold; color: red; text-align: center; margin-top: -10px;">{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span></div>
						</div>

					</div>

				</td>
			</tr>
		</table>

		<div style="width: 100%;">
			@if(!empty($output['advantages']))
			<div style="font-weight: bold; margin-bottom: 1px; font-size: 8px;">
				Преимущества:
			</div>
			<div style="font-size: 7px; line-height: 7px; letter-spacing: -.3px; height: auto; max-height: 89px; overflow: hidden;">
				{!! $output['advantages'] !!}
			</div>
			@endif
		</div>

		<div style="width: 100%; overflow: hidden; height: auto; max-height: 122px; margin: -2px 0 2px 0;">
			<table style="width: 100%; overflow: hidden;">
				<tr>
					<td style="width: 50%; vertical-align: top;">
						<div">
							@if(!empty($output['characteristics']))
							<div style="font-weight: bold; margin-bottom: 2px; font-size: 8px;">Характеристики:</div>
							<div style="font-size: 7px; line-height: 7px; letter-spacing: -.3px; margin-top: -3px;">
								{!! $output['characteristics'] !!}
							</div>
							@endif
						</div>
					</td>
					<td style="width: 50%; vertical-align: top;">
						<div>
							@if(!empty($output['complect']))
							<div style="font-weight: bold; margin-bottom: 2px; font-size: 8px;">Комплектация:</div>
							<div style="font-size: 7px; line-height: 7px; letter-spacing: -.3px; margin-top: -3px;">
								{!! $output['complect'] !!}
							</div>
							@endif
						</div>
					</td>
				</tr>
			</table>
		</div>

		<div style="width: 100%; font-size: 8px; margin-bottom: 2px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if(!empty($output['line_checker']))
		<div style="background-color: red; color: #fff; font-size: 20px; font-weight: bold; text-transform: uppercase; padding: 0 5px 5px; text-align: center; width: 100%;">{{ $output['red_string'] }}</div>
		@endif

	</div>

</div>

</body>
</html>
{{-- @php dd($output) @endphp --}}
