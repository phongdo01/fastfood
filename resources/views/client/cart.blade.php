@extends('master')
@section('content')
<section class="header_text sub">
	<h4><span>Giỏ hàng</span></h4>
</section>
<section class="main-content">				
	<div class="row">
		<div class="span9">					
			<h4 class="title"><span class="text"><strong>Your</strong> Cart</span></h4>
			@if(Session::has('cart'))
			<form method="GET" action="/checkout">
				{!!csrf_field()!!}
				<div class="shopping-cart">
					<div class="column-labels">
						<label class="product-image">Image</label>
						<label class="product-details">Product</label>
						<label class="product-price">Đơn Giá</label>
						<label class="product-quantity">Số Lượng</label>
						<label class="product-removal">Remove</label>
						<label class="product-line-price">Total</label>
					</div>
					@foreach($product_cart as $product)
					<div class="product">
						<div class="product-image">
							<img src="img/{{$product['item']['image']}}">
						</div>
						<div class="product-details">
							<div class="product-title"><b>{{$product['item']['product_name']}}</b></div>
							<p class="product-description">{{$product['item']['description']}}</p>
						</div>
						<div class="product-price">
							@if($product['item']['promotion_price']!=0)
							{{($product['item']['promotion_price'])}} VNĐ
							@else
							{{($product['item']['unit_price'])}} VNĐ
							@endif
						</div>
						<div class="product-quantity">
							<input type="number" value="{{$product['qty']}}" min="1">
							<input type="hidden" class="id_item" value="{{$product['item']['id']}}" name="">
						</div>
						<div class="product-removal">
							<a href="{{route('delete-cart', $product['item']['id'])}}" class="btn btn-xs btn-danger">x</a>
						</div>
						<div class="product-line-price">
							@if($product['item']['promotion_price']!=0)
							{{(int)($product['item']['promotion_price']) * (int)($product['qty'])}} VNĐ
							@else
							{{(int)($product['item']['unit_price']) * (int)($product['qty'])}} VNĐ
							@endif
						</div>
					</div>
					@endforeach
					<div class="totals">
						<div class="totals-item">
							<label>Tổng Tiền</label>
							<div class="totals-value" id="cart-subtotal">
								{{($totalPrice)}} VNĐ
							</div>
						</div>
						<div class="totals-item">
							<label>VAT (5%):</label>
							<div class="totals-value" id="cart-tax">
								{{(float)($totalPrice)*0.05}} VNĐ
							</div>
						</div>
						<div class="totals-item totals-item-total">
							<label>Tổng Cộng:</label>
							<div class="totals-value" id="cart-total">
								{{(float)($totalPrice)*0.05 + (float)($totalPrice)}} VNĐ
							</div>
							<input type="hidden" value="{{(float)($totalPrice)*0.05 + (float)($totalPrice)}}" name="total_price">
						</div>
					</div>
				</div>
				<h4>Bạn muốn đặt online hay offline?</h4>
				<p>Lựa chọn 1 trong hình thức sau.</p>
				<label class="radio">
					<input type="radio" name="book_type" id="optionsRadios1" value="1" checked="">
					Book Online
				</label>
				<label class="radio">
					<input type="radio" name="book_type" id="optionsRadios2" value="2">
					Book Offline
				</label>
				<p class="buttons center">				
					<button class="btn btn-primary" type="submit" id="checkout">
						Tiền Hành Thanh Toán
					</button>
				</p>
			</form>
			@else
			<center><b>Giỏ hàng trống</b></center>	
			@endif				
		</div>
		<div class="span3 col">
			<div class="block">	
				<ul class="nav nav-list">
					<li class="nav-header">MENU Món</li>
					@foreach($category as $c)
					<li><a href="product/{{$c->id}}">{{$c->name}}</a></li>
					@endforeach
				</ul>
				<br/>
			</div>		
		</div>
	</div>
</section>
<script type="text/javascript">
	$(document).ready(function(){
		// block enter form
		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});

		/* Set rates + misc */
		var taxRate = 0.05;
		var fadeTime = 300;

		/* Assign actions */
		$('.product-quantity input').change( function() {
			updateQuantity(this);
		});

		$('.product-removal button').click( function() {
			removeItem(this);
		});


		/* Recalculate cart */
		function recalculateCart()
		{
			var subtotal = 0;

			/* Sum up row totals */
			$('.product').each(function () {
				subtotal += parseFloat($(this).children('.product-line-price').text());
			});

			/* Calculate totals */
			var tax = subtotal * taxRate;
			var total = subtotal + tax;

			/* Update totals display */
			$('.totals-value').fadeOut(fadeTime, function() {
				$('#cart-subtotal').html(subtotal.toFixed() + " VNĐ");
				$('#cart-tax').html(tax.toFixed() + " VNĐ");
				$('#cart-total').html(total.toFixed() + " VNĐ");
				$("input[name = 'total_price']").val(total);
				if(total == 0){
					$('.checkout').fadeOut(fadeTime);
				}else{
					$('.checkout').fadeIn(fadeTime);
				}
				$('.totals-value').fadeIn(fadeTime);
			});
		}


		/* Update quantity */
		function updateQuantity(quantityInput)
		{
			/* Calculate line price */
			var productRow = $(quantityInput).parent().parent();
			var price = parseFloat(productRow.children('.product-price').text());
			var quantity = $(quantityInput).val();
			var linePrice = price * quantity;

			/* Update line price display and recalc cart totals */
			productRow.children('.product-line-price').each(function () {
				$(this).fadeOut(fadeTime, function() {
					$(this).text(linePrice.toFixed() + " VNĐ");
					recalculateCart();
					$(this).fadeIn(fadeTime);
				});
			});  

			//update session cart
			var productRow = $(quantityInput).parent().parent();
			var quantity = $(quantityInput).val();
			var id = $(quantityInput).parent().children('.id_item').val();
			$.ajax({
                url: "{{ asset('/update-cart') }}",
                method: "GET",
                data: { 
                	'unit': quantity,
                	'id': id,
                },
                success: function(data) {
                    console.log('get full list success');
                }
            });
		}
	});
</script>
@endsection