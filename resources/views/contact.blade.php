@include('head')
<body class="animsition">
	@include('headerPage')
	@include('cart2')
    
	<!--Contact-->
    <section class="bg0 p-t-104 p-b-116">
		<div class="container">
			<div class="flex-w flex-tr">
				<div class="size-210 bor10 p-lr-70 p-t-55 p-b-70 p-lr-15-lg w-full-md">
					<form action="{{ route('contactUs') }}" method="POST">
						<h4 class="mtext-105 cl2 txt-center p-b-30">
							お問い合わせ
						</h4>

						<div class="bor8 m-b-20 how-pos4-parent">
							<input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="text" name="name" placeholder="お名前をご記入ください。" value="{{ old('name') }}">
						</div>
						@if($errors->has('name'))
							<label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('name')}}</label><br/>
						@endif

						<div class="bor8 m-b-20 how-pos4-parent">
							<input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="text" name="email" placeholder="メールアドレスをご記入してください。" value="{{ old('email') }}">
						</div>
						@if($errors->has('email'))
							<label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('email')}}</label><br/>
						@endif

						<div class="bor8 m-b-20 how-pos4-parent">
							<input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="text" name="phone" placeholder="電話番号をご記入ください。" value="{{ old('phone') }}">
						</div>
						@if($errors->has('phone'))
							<label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('phone')}}</label><br/>
						@endif

						<div class="bor8 m-b-30">
							<textarea class="stext-111 cl2 plh3 size-120 p-lr-28 p-tb-25" name="content" placeholder="お問い合わせ内容をご記入ください。"></textarea>
						</div>
						@if($errors->has('content'))
							<label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('content')}}</label><br/>
						@endif

						<button class="flex-c-m stext-101 cl0 size-121 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer">
							送信
						</button>
						@csrf
					</form>
				</div>

				<div class="size-210 bor10 flex-w flex-col-m p-lr-93 p-tb-30 p-lr-15-lg w-full-md">
					<div class="flex-w w-full p-b-42">
						<span class="fs-18 cl5 txt-center size-211">
							<span class="lnr lnr-phone-handset"></span>
						</span>

						<div class="size-212 p-t-2">
							<span class="mtext-110 cl2">
								電話番号
							</span>

							<p class="stext-115 cl1 size-213 p-t-18">
								0332741666
							</p>
						</div>
					</div>

					<div class="flex-w w-full p-b-42">
						<span class="fs-18 cl5 txt-center size-211">
							<span class="lnr lnr-envelope"></span>
						</span>

						<div class="size-212 p-t-2">
							<span class="mtext-110 cl2">
								メールアドレス
							</span>

							<p class="stext-115 cl1 size-213 p-t-18">
								lionelnguyen1998@gmail.com
							</p>
						</div>
					</div>

					<div class="flex-w w-full">
						<span class="fs-18 cl5 txt-center size-211">
						</span>

						<div class="size-212 p-t-2">
							<span class="mtext-110 cl2">
								平日 9時00分～18時00分
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>	
	
	
	<!-- Map -->
	<div class="map">
        <div id="map" style="height: 100%; width: 100%;">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14898.763660245146!2d105.83685507841687!3d21.00502340917457!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ad5569f4fbf1%3A0x5bf30cadcd91e2c3!2zQ-G7lW5nIFRy4bqnbiDEkOG6oWkgTmdoxKlhIC0gxJDhuqFpIEjhu41jIELDoWNoIEtob2EgSMOgIE7hu5lp!5e0!3m2!1svi!2s!4v1645605534507!5m2!1svi!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
	</div>

@include('footer')
