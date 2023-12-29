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
<div style="width: 702px; height: auto; margin: -25px 0;">
	
	<div style="width: 350px; height: 195px; margin: 0; display: inline-block; float: left; overflow: hidden; position: relative; border: dashed 1px #999;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 70px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 67px;">
					</div>
				</td>
				@endif

				<td style="padding: 0; height: 62px; overflow: hidden;">
					<div>
						<h1 style="font-size: 16px; letter-spacing: -1px; word-spacing: -1px; line-height: 1; height: auto; max-height: 62px; overflow: hidden; margin: 0;">{{ $output['name'] }}</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 103px;">
					<div style="text-align: center; width: 103px; height: 103px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block;  width: auto; max-width: 103px; height: auto; max-height: 103px;">
						@endif
					</div>
				</td>
				<td>
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 1px 5px 2px; margin: 0 10px 2px; background-color: red; color: #fff; border-radius: 5px; text-transform: uppercase; font-size: 18px; font-weight: bold; text-align: center;">{{ $output['type'] }}</div>
						@endif

						<div style="line-height: 0.7;">

							@if(!empty($output['type']))
							<div style="font-size: 24px; color: #999; text-decoration: line-through; text-align: center;">{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span></div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 30px; font-weight: bold; color: red; text-align: center;">{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span></div>
						</div>

					</div>

				</td>
			</tr>
		</table>


		<div style="width: 100%; font-size: 8px; padding-left: 3px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if($output['barcode'])

		<div style="position: absolute; right: 10px; top: 161px">
			{!! DNS1D::getBarcodeHTML($output['barcode'], "EAN13", 1.6, 20) !!}
		</div>

		<div style="position: absolute; right: 7px; top: 175px; font-size: 14px; letter-spacing: 3px;">
			{{ $output['barcode'] }}
		</div>

		@endif

	</div>

	<div style="width: 350px; height: 195px; margin: 0; display: inline-block; float: right; overflow: hidden; position: relative; border: dashed 1px #999;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 70px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 67px;">
					</div>
				</td>
				@endif

				<td style="padding: 0; height: 62px; overflow: hidden;">
					<div>
						<h1 style="font-size: 16px; letter-spacing: -1px; word-spacing: -1px; line-height: 1; height: auto; max-height: 62px; overflow: hidden; margin: 0;">{{ $output['name'] }}</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 103px;">
					<div style="text-align: center; width: 103px; height: 103px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block;  width: auto; max-width: 103px; height: auto; max-height: 103px;">
						@endif
					</div>
				</td>
				<td>
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 1px 5px 2px; margin: 0 10px 2px; background-color: red; color: #fff; border-radius: 5px; text-transform: uppercase; font-size: 18px; font-weight: bold; text-align: center;">{{ $output['type'] }}</div>
						@endif

						<div style="line-height: 0.7;">

							@if(!empty($output['type']))
							<div style="font-size: 24px; color: #999; text-decoration: line-through; text-align: center;">{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span></div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 30px; font-weight: bold; color: red; text-align: center;">{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span></div>
						</div>

					</div>

				</td>
			</tr>
		</table>


		<div style="width: 100%; font-size: 8px; padding-left: 3px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if($output['barcode'])

		<div style="position: absolute; right: 10px; top: 161px">
			{!! DNS1D::getBarcodeHTML($output['barcode'], "EAN13", 1.6, 20) !!}
		</div>

		<div style="position: absolute; right: 7px; top: 175px; font-size: 14px; letter-spacing: 3px;">
			{{ $output['barcode'] }}
		</div>

		@endif

	</div>

	<div style="clear: both;"></div>

	<div style="width: 350px; height: 195px; margin: 0; display: inline-block; float: left; overflow: hidden; position: relative; border: dashed 1px #999;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 70px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 67px;">
					</div>
				</td>
				@endif

				<td style="padding: 0; height: 62px; overflow: hidden;">
					<div>
						<h1 style="font-size: 16px; letter-spacing: -1px; word-spacing: -1px; line-height: 1; height: auto; max-height: 62px; overflow: hidden; margin: 0;">{{ $output['name'] }}</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 103px;">
					<div style="text-align: center; width: 103px; height: 103px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block;  width: auto; max-width: 103px; height: auto; max-height: 103px;">
						@endif
					</div>
				</td>
				<td>
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 1px 5px 2px; margin: 0 10px 2px; background-color: red; color: #fff; border-radius: 5px; text-transform: uppercase; font-size: 18px; font-weight: bold; text-align: center;">{{ $output['type'] }}</div>
						@endif

						<div style="line-height: 0.7;">

							@if(!empty($output['type']))
							<div style="font-size: 24px; color: #999; text-decoration: line-through; text-align: center;">{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span></div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 30px; font-weight: bold; color: red; text-align: center;">{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span></div>
						</div>

					</div>

				</td>
			</tr>
		</table>


		<div style="width: 100%; font-size: 8px; padding-left: 3px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if($output['barcode'])

		<div style="position: absolute; right: 10px; top: 161px">
			{!! DNS1D::getBarcodeHTML($output['barcode'], "EAN13", 1.6, 20) !!}
		</div>

		<div style="position: absolute; right: 7px; top: 175px; font-size: 14px; letter-spacing: 3px;">
			{{ $output['barcode'] }}
		</div>

		@endif

	</div>

	<div style="width: 350px; height: 195px; margin: 0; display: inline-block; float: right; overflow: hidden; position: relative; border: dashed 1px #999;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 70px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 67px;">
					</div>
				</td>
				@endif

				<td style="padding: 0; height: 62px; overflow: hidden;">
					<div>
						<h1 style="font-size: 16px; letter-spacing: -1px; word-spacing: -1px; line-height: 1; height: auto; max-height: 62px; overflow: hidden; margin: 0;">{{ $output['name'] }}</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 103px;">
					<div style="text-align: center; width: 103px; height: 103px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block;  width: auto; max-width: 103px; height: auto; max-height: 103px;">
						@endif
					</div>
				</td>
				<td>
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 1px 5px 2px; margin: 0 10px 2px; background-color: red; color: #fff; border-radius: 5px; text-transform: uppercase; font-size: 18px; font-weight: bold; text-align: center;">{{ $output['type'] }}</div>
						@endif

						<div style="line-height: 0.7;">

							@if(!empty($output['type']))
							<div style="font-size: 24px; color: #999; text-decoration: line-through; text-align: center;">{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span></div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 30px; font-weight: bold; color: red; text-align: center;">{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span></div>
						</div>

					</div>

				</td>
			</tr>
		</table>


		<div style="width: 100%; font-size: 8px; padding-left: 3px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if($output['barcode'])

		<div style="position: absolute; right: 10px; top: 161px">
			{!! DNS1D::getBarcodeHTML($output['barcode'], "EAN13", 1.6, 20) !!}
		</div>

		<div style="position: absolute; right: 7px; top: 175px; font-size: 14px; letter-spacing: 3px;">
			{{ $output['barcode'] }}
		</div>

		@endif

	</div>

	<div style="clear: both;"></div>

	<div style="width: 350px; height: 195px; margin: 0; display: inline-block; float: left; overflow: hidden; position: relative; border: dashed 1px #999;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 70px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 67px;">
					</div>
				</td>
				@endif

				<td style="padding: 0; height: 62px; overflow: hidden;">
					<div>
						<h1 style="font-size: 16px; letter-spacing: -1px; word-spacing: -1px; line-height: 1; height: auto; max-height: 62px; overflow: hidden; margin: 0;">{{ $output['name'] }}</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 103px;">
					<div style="text-align: center; width: 103px; height: 103px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block;  width: auto; max-width: 103px; height: auto; max-height: 103px;">
						@endif
					</div>
				</td>
				<td>
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 1px 5px 2px; margin: 0 10px 2px; background-color: red; color: #fff; border-radius: 5px; text-transform: uppercase; font-size: 18px; font-weight: bold; text-align: center;">{{ $output['type'] }}</div>
						@endif

						<div style="line-height: 0.7;">

							@if(!empty($output['type']))
							<div style="font-size: 24px; color: #999; text-decoration: line-through; text-align: center;">{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span></div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 30px; font-weight: bold; color: red; text-align: center;">{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span></div>
						</div>

					</div>

				</td>
			</tr>
		</table>


		<div style="width: 100%; font-size: 8px; padding-left: 3px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if($output['barcode'])

		<div style="position: absolute; right: 10px; top: 161px">
			{!! DNS1D::getBarcodeHTML($output['barcode'], "EAN13", 1.6, 20) !!}
		</div>

		<div style="position: absolute; right: 7px; top: 175px; font-size: 14px; letter-spacing: 3px;">
			{{ $output['barcode'] }}
		</div>

		@endif

	</div>

	<div style="width: 350px; height: 195px; margin: 0; display: inline-block; float: right; overflow: hidden; position: relative; border: dashed 1px #999;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 70px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 67px;">
					</div>
				</td>
				@endif

				<td style="padding: 0; height: 62px; overflow: hidden;">
					<div>
						<h1 style="font-size: 16px; letter-spacing: -1px; word-spacing: -1px; line-height: 1; height: auto; max-height: 62px; overflow: hidden; margin: 0;">{{ $output['name'] }}</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 103px;">
					<div style="text-align: center; width: 103px; height: 103px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block;  width: auto; max-width: 103px; height: auto; max-height: 103px;">
						@endif
					</div>
				</td>
				<td>
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 1px 5px 2px; margin: 0 10px 2px; background-color: red; color: #fff; border-radius: 5px; text-transform: uppercase; font-size: 18px; font-weight: bold; text-align: center;">{{ $output['type'] }}</div>
						@endif

						<div style="line-height: 0.7;">

							@if(!empty($output['type']))
							<div style="font-size: 24px; color: #999; text-decoration: line-through; text-align: center;">{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span></div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 30px; font-weight: bold; color: red; text-align: center;">{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span></div>
						</div>

					</div>

				</td>
			</tr>
		</table>


		<div style="width: 100%; font-size: 8px; padding-left: 3px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if($output['barcode'])

		<div style="position: absolute; right: 10px; top: 161px">
			{!! DNS1D::getBarcodeHTML($output['barcode'], "EAN13", 1.6, 20) !!}
		</div>

		<div style="position: absolute; right: 7px; top: 175px; font-size: 14px; letter-spacing: 3px;">
			{{ $output['barcode'] }}
		</div>

		@endif

	</div>

	<div style="clear: both;"></div>

	<div style="width: 350px; height: 195px; margin: 0; display: inline-block; float: left; overflow: hidden; position: relative; border: dashed 1px #999;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 70px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 67px;">
					</div>
				</td>
				@endif

				<td style="padding: 0; height: 62px; overflow: hidden;">
					<div>
						<h1 style="font-size: 16px; letter-spacing: -1px; word-spacing: -1px; line-height: 1; height: auto; max-height: 62px; overflow: hidden; margin: 0;">{{ $output['name'] }}</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 103px;">
					<div style="text-align: center; width: 103px; height: 103px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block;  width: auto; max-width: 103px; height: auto; max-height: 103px;">
						@endif
					</div>
				</td>
				<td>
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 1px 5px 2px; margin: 0 10px 2px; background-color: red; color: #fff; border-radius: 5px; text-transform: uppercase; font-size: 18px; font-weight: bold; text-align: center;">{{ $output['type'] }}</div>
						@endif

						<div style="line-height: 0.7;">

							@if(!empty($output['type']))
							<div style="font-size: 24px; color: #999; text-decoration: line-through; text-align: center;">{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span></div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 30px; font-weight: bold; color: red; text-align: center;">{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span></div>
						</div>

					</div>

				</td>
			</tr>
		</table>


		<div style="width: 100%; font-size: 8px; padding-left: 3px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if($output['barcode'])

		<div style="position: absolute; right: 10px; top: 161px">
			{!! DNS1D::getBarcodeHTML($output['barcode'], "EAN13", 1.6, 20) !!}
		</div>

		<div style="position: absolute; right: 7px; top: 175px; font-size: 14px; letter-spacing: 3px;">
			{{ $output['barcode'] }}
		</div>

		@endif

	</div>

	<div style="width: 350px; height: 195px; margin: 0; display: inline-block; float: right; overflow: hidden; position: relative; border: dashed 1px #999;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 70px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 67px;">
					</div>
				</td>
				@endif

				<td style="padding: 0; height: 62px; overflow: hidden;">
					<div>
						<h1 style="font-size: 16px; letter-spacing: -1px; word-spacing: -1px; line-height: 1; height: auto; max-height: 62px; overflow: hidden; margin: 0;">{{ $output['name'] }}</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 103px;">
					<div style="text-align: center; width: 103px; height: 103px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block;  width: auto; max-width: 103px; height: auto; max-height: 103px;">
						@endif
					</div>
				</td>
				<td>
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 1px 5px 2px; margin: 0 10px 2px; background-color: red; color: #fff; border-radius: 5px; text-transform: uppercase; font-size: 18px; font-weight: bold; text-align: center;">{{ $output['type'] }}</div>
						@endif

						<div style="line-height: 0.7;">

							@if(!empty($output['type']))
							<div style="font-size: 24px; color: #999; text-decoration: line-through; text-align: center;">{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span></div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 30px; font-weight: bold; color: red; text-align: center;">{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span></div>
						</div>

					</div>

				</td>
			</tr>
		</table>


		<div style="width: 100%; font-size: 8px; padding-left: 3px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if($output['barcode'])

		<div style="position: absolute; right: 10px; top: 161px">
			{!! DNS1D::getBarcodeHTML($output['barcode'], "EAN13", 1.6, 20) !!}
		</div>

		<div style="position: absolute; right: 7px; top: 175px; font-size: 14px; letter-spacing: 3px;">
			{{ $output['barcode'] }}
		</div>

		@endif

	</div>

	<div style="clear: both;"></div>

	<div style="width: 350px; height: 195px; margin: 0; display: inline-block; float: left; overflow: hidden; position: relative; border: dashed 1px #999;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 70px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 67px;">
					</div>
				</td>
				@endif

				<td style="padding: 0; height: 62px; overflow: hidden;">
					<div>
						<h1 style="font-size: 16px; letter-spacing: -1px; word-spacing: -1px; line-height: 1; height: auto; max-height: 62px; overflow: hidden; margin: 0;">{{ $output['name'] }}</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 103px;">
					<div style="text-align: center; width: 103px; height: 103px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block;  width: auto; max-width: 103px; height: auto; max-height: 103px;">
						@endif
					</div>
				</td>
				<td>
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 1px 5px 2px; margin: 0 10px 2px; background-color: red; color: #fff; border-radius: 5px; text-transform: uppercase; font-size: 18px; font-weight: bold; text-align: center;">{{ $output['type'] }}</div>
						@endif

						<div style="line-height: 0.7;">

							@if(!empty($output['type']))
							<div style="font-size: 24px; color: #999; text-decoration: line-through; text-align: center;">{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span></div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 30px; font-weight: bold; color: red; text-align: center;">{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span></div>
						</div>

					</div>

				</td>
			</tr>
		</table>


		<div style="width: 100%; font-size: 8px; padding-left: 3px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if($output['barcode'])

		<div style="position: absolute; right: 10px; top: 161px">
			{!! DNS1D::getBarcodeHTML($output['barcode'], "EAN13", 1.6, 20) !!}
		</div>

		<div style="position: absolute; right: 7px; top: 175px; font-size: 14px; letter-spacing: 3px;">
			{{ $output['barcode'] }}
		</div>

		@endif

	</div>

	<div style="width: 350px; height: 195px; margin: 0; display: inline-block; float: right; overflow: hidden; position: relative; border: dashed 1px #999;">
		<table style="width: 100%;">
			<tr>

				@if(!empty($output['brand_pic']))
				<td style="width: 70px;">
					<div>
						<img src="{{ asset('/storage/brand-logo/'.$output['brand_pic']) }}" style="display: block; width: 67px;">
					</div>
				</td>
				@endif

				<td style="padding: 0; height: 62px; overflow: hidden;">
					<div>
						<h1 style="font-size: 16px; letter-spacing: -1px; word-spacing: -1px; line-height: 1; height: auto; max-height: 62px; overflow: hidden; margin: 0;">{{ $output['name'] }}</h1>
					</div>
					
				</td>
			</tr>
			
		</table>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 103px;">
					<div style="text-align: center; width: 103px; height: 103px;">
						@if(!empty($output['item_pic_path']))
						<img src="{{ asset('/storage/'.$output['item_pic_path']) }}" style="display: inline-block;  width: auto; max-width: 103px; height: auto; max-height: 103px;">
						@endif
					</div>
				</td>
				<td>
					<div style="visibility: {{ $output['type_price'] }};">

						@if(!empty($output['type']))
						<div style="padding: 1px 5px 2px; margin: 0 10px 2px; background-color: red; color: #fff; border-radius: 5px; text-transform: uppercase; font-size: 18px; font-weight: bold; text-align: center;">{{ $output['type'] }}</div>
						@endif

						<div style="line-height: 0.7;">

							@if(!empty($output['type']))
							<div style="font-size: 24px; color: #999; text-decoration: line-through; text-align: center;">{{ $output['price_new'] }}<span style="font-size: 0.6em">руб</span></div>
							@endif

							<div class="pricetag_price-bl_mr-bel" style="font-size: 30px; font-weight: bold; color: red; text-align: center;">{{ $output['price_old'] }}<span style="font-size: 0.6em">руб</span></div>
						</div>

					</div>

				</td>
			</tr>
		</table>


		<div style="width: 100%; font-size: 8px; padding-left: 3px;">
			@if(!empty($output['country']))
			<strong>Страна производитель:</strong> <span>{{ $output['country'] }}</span>
			@endif
		</div>

		@if($output['barcode'])

		<div style="position: absolute; right: 10px; top: 161px">
			{!! DNS1D::getBarcodeHTML($output['barcode'], "EAN13", 1.6, 20) !!}
		</div>

		<div style="position: absolute; right: 7px; top: 175px; font-size: 14px; letter-spacing: 3px;">
			{{ $output['barcode'] }}
		</div>

		@endif

	</div>

</div>

</body>
</html>
{{-- @php dd($output) @endphp --}}
