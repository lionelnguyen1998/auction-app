<!-- Slider -->
<section class="section-slide">
		<div class="wrap-slick1">
			<div class="slick1">
				@if (isset ($slider[0]['image']) )
					<div class="item-slick1" style="background-image: url({{ $slider[0]['image'] }});">
						<div class="container h-full">
							<div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
								<div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
									<span class="ltext-101 cl2 respon2">
										<b>オークション２０２２</b>
									</span>
								</div>
									
								<div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
									<h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
										<b>一番新しい</b>
									</h2>
								</div>
									
								<div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
									<a href="{{ route('createAuction') }}" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
										販売
									</a>
								</div>
							</div>
						</div>
					</div>
				@endif
				@if (isset ($slider[1]['image']) )
					<div class="item-slick1" style="background-image: url({{ $slider[1]['image'] }});">
						<div class="container h-full">
							<div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
								<div class="layer-slick1 animated visible-false" data-appear="rollIn" data-delay="0">
									<span class="ltext-101 cl2 respon2">
										<b>オークション２０２２</b>
									</span>
								</div>
									
								<div class="layer-slick1 animated visible-false" data-appear="lightSpeedIn" data-delay="800">
									<h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
										<b>一番速い</b>
									</h2>
								</div>
									
								<div class="layer-slick1 animated visible-false" data-appear="slideInUp" data-delay="1600">
									<a href="{{ route('createAuction') }}" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
										販売
									</a>
								</div>
							</div>
						</div>
					</div>
				@endif
				@if (isset ($slider[2]['image']) )
					<div class="item-slick1" style="background-image: url({{ $slider[2]['image'] }});">
						<div class="container h-full">
							<div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
								<div class="layer-slick1 animated visible-false" data-appear="rotateInDownLeft" data-delay="0">
									<span class="ltext-101 cl2 respon2">
										<b>オークション２０２２</b>
									</span>
								</div>
									
								<div class="layer-slick1 animated visible-false" data-appear="rotateInUpRight" data-delay="800">
									<h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
										<b>節約</b>
									</h2>
								</div>
									
								<div class="layer-slick1 animated visible-false" data-appear="rotateIn" data-delay="1600">
									<a href="{{ route('createAuction') }}" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
										販売
									</a>
								</div>
							</div>
						</div>
					</div>
				@endif
			</div>
		</div>
	</section>
