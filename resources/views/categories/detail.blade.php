@include('head')
<style>
   
</style>
<body class="animsition">
	@include('headerPage')
	@include('cart2')

	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="index.html" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<a href="product.html" class="stext-109 cl8 hov-cl1 trans-04">
				auctions1
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				detail
			</span>
		</div>
	</div>
		

	<!-- Product Detail -->
	<section class="sec-product-detail bg0 p-t-65 p-b-60">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-lg-7 p-b-30">
					<div class="p-l-25 p-r-30 p-lr-0-lg">
						<div class="wrap-slick3 flex-sb flex-w">
							<div class="wrap-slick3-dots"></div>
							<div class="wrap-slick3-arrows flex-sb-m flex-w"></div>

							<div class="slick3 gallery-lb">
								@if (is_null($images))
									@foreach ($images as $key => $imageItem)
										<div class="item-slick3" data-thumb="{{ $imageItem }}">
											<div class="wrap-pic-w pos-relative">
												<img src="{{ $imageItem }}" alt="IMG-PRODUCT">

												<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="{{ $imageItem }}">
													<i class="fa fa-expand"></i>
												</a>
											</div>
										</div>
									@endforeach
								@endif
							</div>
						</div>
					</div>
				</div>
					
				<div class="col-md-6 col-lg-5 p-b-30">
					<div class="p-r-50 p-t-5 p-lr-0-lg">
						<h4 class="mtext-105 cl2 js-name-detail p-b-14">
							{{ $auction[0]["items"][0]["name"] }}
						</h4>
                        <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100" data-tooltip="Like">
                            <i class="zmdi zmdi-favorite" style="font-size:large"></i>
                        </a>
						<!--  -->
						<div class="p-t-33">
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>始める時間</b> <p class="float-right">{{ $auction[0]["start_date"] }}</p>
                                </li>
                                <li class="list-group-item">
                                    <b>終わる時間</b> <p class="float-right">{{ $auction[0]["end_date"] }}</p>
                                </li>
                                <li class="list-group-item">
                                    <b>始値</b> <p class="float-right">{{ $auction[0]["items"][0]["starting_price"] }}</p>
                                </li>
                                <li class="list-group-item">
                                    <b>最後値段</b> <p class="float-right">{{ $maxPrice }}</p>
                                </li>
                                <li class="list-group-item">
                                    @php
                                        $status = config('const.status');
                                        $index = $auction[0]['auction_status']['status'];
                                    @endphp
                                    @if ($index == 1)
                                        <p class="btn btn-success" disabled>{{ $status[$index] }}</p>
                                    @elseif ($index == 2)
                                        <p class="btn btn-warning" disabled>{{ $status[$index] }}</p>
                                    @else
                                        <p class="btn btn-danger" disabled>{{ $status[$index] }}</p>
                                    @endif
                                </li>
                            </ul>
						</div>

					</div>
				</div>
			</div>

			<div class="bor10 m-t-50 p-t-43 p-b-40">
				<!-- Tab01 -->
				<div class="tab01">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item p-b-10">
							<a class="nav-link active" data-toggle="tab" href="#description" role="tab">内容</a>
						</li>

						<li class="nav-item p-b-10">
							<a class="nav-link" data-toggle="tab" href="#information" role="tab">技術の情報</a>
						</li>

                        <li class="nav-item p-b-10">
							<a class="nav-link" data-toggle="tab" href="#bid" role="tab">BID</a>
						</li>

						<li class="nav-item p-b-10">
							<a class="nav-link" data-toggle="tab" href="#comments" role="tab">コメント</a>
						</li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content p-t-43">
						<!-- - -->
						<div class="tab-pane fade show active" id="description" role="tabpanel">
							<div class="how-pos2 p-lr-15-md">
								<p class="stext-102 cl6">
									{{ $auction[0]["items"][0]["description"]}}
								</p>
							</div>
						</div>

						<!-- - -->
						<div class="tab-pane fade" id="information" role="tabpanel">
							<div class="row">
								<div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
									<ul class="p-lr-28 p-lr-15-sm">
                                        @foreach ($infors as $key => $infor)
                                            <li class="flex-w flex-t p-b-7">
                                                <span class="stext-102 cl3 size-205">
                                                    {{ $categoryValueName[$key] }}
                                                </span>

                                                <span class="stext-102 cl6 size-206">
                                                    {{ $infor }}
                                                </span>
                                            </li>
                                            <hr>
                                        @endforeach
									</ul>
								</div>
							</div>
						</div>

                        <!-- - -->
						<div class="tab-pane fade" id="bid" role="tabpanel">
							<div class="row">
								<div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
									<div class="p-b-30 m-lr-15-sm">
										@if ($index == 3)
										@else
										<!-- Add new bid -->
										<form class="w-full" action="{{ route('insertBid') }}" method="POST">
											<input type="hidden" name="auction_id" value="{{ $auction[0]['auction_id'] }}"/>
											@if (auth()->user())
                                            	<input type="hidden" name="user_id" value="{{ auth()->user()->user_id }}"/>
											@endif
                                            <input type="hidden" name="item_id" value="{{ $auction[0]['items'][0]['item_id'] }}"/>
                                            <h5 class="mtext-108 cl2 p-b-7">
												Nhap thong tin tra gia
											</h5>
											<div class="row p-b-25">
												<div class="col-sm-6 p-b-5 required">
													<label class="stext-102 cl3" for="price">BID (番号だけ入力してください)</label>
													<input class="size-111 bor8 stext-102 cl2 p-lr-20" id="price" type="text" name="price" value="{{ old('price') }}" placeholder="入力してください">
                                                    @if($errors->has('price'))
                                                        <label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('price')}}</label><br/>
                                                    @endif
												</div>

												<div class="col-sm-6 p-b-5">
													<label class="stext-102 cl3" for="phone">電話番号</label>
													<input class="size-111 bor8 stext-102 cl2 p-lr-20" id="phone" type="text" name="phone" placeholder="入力してください">
												</div>
											</div>

											<button type="submit" class="flex-c-m stext-101 cl0 size-112 bg7 bor11 hov-btn3 p-lr-15 trans-04 m-b-10">
												送信
											</button>
                                            @csrf
											<hr>
										</form>
										@endif
										<!-- Review -->
                                        @foreach ($bids as $key => $bid)
                                            @php 
                                                $avatar = $bid['users']['avatar']
                                            @endphp
                                            <div class="flex-w">
                                                <div class="wrap-pic-s size-109 bor0 of-hidden m-r-18 m-t-6">
                                                    <img src="{{ $avatar }}" alt="AVATAR">
                                                </div>

                                                <div class="size-207">
                                                    <div class="flex-w flex-sb-m p-b-17">
                                                        <span class="mtext-107 cl2 p-r-20">
                                                            {{ $bid['users']['nick_name'] }}
                                                        </span>
                                                        <span class="description">{{ date("d-m-Y H:i", strtotime($bid['updated_at'])) }}</span>
                                                    </div>

                                                    <p class="stext-102 cl6">
                                                        {{ $bid['price'] }}
                                                    </p>
                                                </div>
                                                <p style="margin-top:10px">
												@if ($index == 3 && $key == 0)
													<a class="btn btn-warning" style="margin-right:10px; color:white" href="{{ route('chat') }}">Thuong luong</a>
													<a class="btn btn-success" style="color:white" href="{{ route('acceptBid', ['bidId' => $bid['bid_id']] ) }}">Chap nhan</a>
												@endif
                                                </p>
                                            </div>
                                            <hr>
                                        @endforeach
									</div>
								</div>
							</div>
						</div>

						<!-- - -->
						<div class="tab-pane fade" id="comments" role="tabpanel">
                            <div class="row">
                                    <div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
                                        <div class="p-b-30 m-lr-15-sm">
											@if ($index == 3)
											@else
											<!-- Add new bid -->
											<form class="w-full" action="{{ route('insertComment') }}" method="POST">
												<input type="hidden" name="auction_id" value="{{ $auction[0]['auction_id'] }}"/>
												@if (auth()->user())
													<input type="hidden" name="user_id" value="{{ auth()->user()->user_id }}"/>
												@endif
												<div class="row p-b-25">
													<div class="col-sm-12 p-b-5 required">
														<label class="stext-102 cl3" for="content">コメント</label>
														<input class="size-111 bor8 stext-102 cl2 p-lr-20" id="content" type="text" name="content"　placeholder="入力してください">
													</div>
												</div>

												<button type="submit" class="flex-c-m stext-101 cl0 size-112 bg7 bor11 hov-btn3 p-lr-15 trans-04 m-b-10">
													送信
												</button>
												@csrf
												<hr>
                                            </form>
											@endif
                                            <!-- Review -->
                                            @foreach ($comments as $key => $comment)
                                                @php 
                                                    $avatar = $comment['users']['avatar']
                                                @endphp
                                                <div class="flex-w">
                                                    <div class="wrap-pic-s size-109 bor0 of-hidden m-r-18 m-t-6">
                                                        <img src="{{ $avatar }}" alt="AVATAR">
                                                    </div>

                                                    <div class="size-207">
                                                        <div class="flex-w flex-sb-m p-b-17">
                                                            <span class="mtext-107 cl2 p-r-20">
                                                                {{ $comment['users']['nick_name'] }}
                                                            </span>
                                                            <span class="description">{{ date("d-m-Y H:i", strtotime($comment['updated_at'])) }}</span>
                                                        </div>

                                                        <p class="stext-102 cl6">
                                                            {{ $comment['content'] }}
                                                        </p>
                                                    </div>
                                                    <p style="margin-top:10px">
                                                        <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100" data-tooltip="シェア"><i class="fa fa-share" aria-hidden="true"></i></a>
                                                        <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100" data-tooltip="いいね"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
                                                        <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100" data-tooltip="返事"><i class="fa fa-comments" aria-hidden="true"></i></a>
                                                    </p>
                                                </div>
                                                <hr>
                                            @endforeach
                                        </div>
                                    </div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<div class="bg6 flex-c-m flex-w size-302 m-t-73 p-tb-15">
			<span class="stext-107 cl6 p-lr-25">
				SKU: JAK-01
			</span>

			<span class="stext-107 cl6 p-lr-25">
				Categories: Jacket, Men
			</span>
		</div>
	</section>


	<!-- Related Products -->
	<section class="sec-relate-product bg0 p-t-45 p-b-105">
		<div class="container">
			<div class="p-b-45">
				<h3 class="ltext-106 cl5 txt-center">
					Related Products
				</h3>
			</div>

			<!-- Slide2 -->
			<div class="wrap-slick2">
				<div class="slick2">
					<div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
						<!-- Block2 -->
						<div class="block2">
							<div class="block2-pic hov-img0">
								<img src="images/product-01.jpg" alt="IMG-PRODUCT">

								<a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
									Quick View
								</a>
							</div>

							<div class="block2-txt flex-w flex-t p-t-14">
								<div class="block2-txt-child1 flex-col-l ">
									<a href="product-detail.html" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
										Esprit Ruffle Shirt
									</a>

									<span class="stext-105 cl3">
										$16.64
									</span>
								</div>

								<div class="block2-txt-child2 flex-r p-t-3">
									<a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
										<img class="icon-heart1 dis-block trans-04" src="images/icons/icon-heart-01.png" alt="ICON">
										<img class="icon-heart2 dis-block trans-04 ab-t-l" src="images/icons/icon-heart-02.png" alt="ICON">
									</a>
								</div>
							</div>
						</div>
					</div>

					<div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
						<!-- Block2 -->
						<div class="block2">
							<div class="block2-pic hov-img0">
								<img src="images/product-02.jpg" alt="IMG-PRODUCT">

								<a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
									Quick View
								</a>
							</div>

							<div class="block2-txt flex-w flex-t p-t-14">
								<div class="block2-txt-child1 flex-col-l ">
									<a href="product-detail.html" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
										Herschel supply
									</a>

									<span class="stext-105 cl3">
										$35.31
									</span>
								</div>

								<div class="block2-txt-child2 flex-r p-t-3">
									<a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
										<img class="icon-heart1 dis-block trans-04" src="images/icons/icon-heart-01.png" alt="ICON">
										<img class="icon-heart2 dis-block trans-04 ab-t-l" src="images/icons/icon-heart-02.png" alt="ICON">
									</a>
								</div>
							</div>
						</div>
					</div>

					<div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
						<!-- Block2 -->
						<div class="block2">
							<div class="block2-pic hov-img0">
								<img src="images/product-03.jpg" alt="IMG-PRODUCT">

								<a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
									Quick View
								</a>
							</div>

							<div class="block2-txt flex-w flex-t p-t-14">
								<div class="block2-txt-child1 flex-col-l ">
									<a href="product-detail.html" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
										Only Check Trouser
									</a>

									<span class="stext-105 cl3">
										$25.50
									</span>
								</div>

								<div class="block2-txt-child2 flex-r p-t-3">
									<a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
										<img class="icon-heart1 dis-block trans-04" src="images/icons/icon-heart-01.png" alt="ICON">
										<img class="icon-heart2 dis-block trans-04 ab-t-l" src="images/icons/icon-heart-02.png" alt="ICON">
									</a>
								</div>
							</div>
						</div>
					</div>

					<div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
						<!-- Block2 -->
						<div class="block2">
							<div class="block2-pic hov-img0">
								<img src="images/product-04.jpg" alt="IMG-PRODUCT">

								<a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
									Quick View
								</a>
							</div>

							<div class="block2-txt flex-w flex-t p-t-14">
								<div class="block2-txt-child1 flex-col-l ">
									<a href="product-detail.html" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
										Classic Trench Coat
									</a>

									<span class="stext-105 cl3">
										$75.00
									</span>
								</div>

								<div class="block2-txt-child2 flex-r p-t-3">
									<a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
										<img class="icon-heart1 dis-block trans-04" src="images/icons/icon-heart-01.png" alt="ICON">
										<img class="icon-heart2 dis-block trans-04 ab-t-l" src="images/icons/icon-heart-02.png" alt="ICON">
									</a>
								</div>
							</div>
						</div>
					</div>

					<div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
						<!-- Block2 -->
						<div class="block2">
							<div class="block2-pic hov-img0">
								<img src="images/product-05.jpg" alt="IMG-PRODUCT">

								<a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
									Quick View
								</a>
							</div>

							<div class="block2-txt flex-w flex-t p-t-14">
								<div class="block2-txt-child1 flex-col-l ">
									<a href="product-detail.html" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
										Front Pocket Jumper
									</a>

									<span class="stext-105 cl3">
										$34.75
									</span>
								</div>

								<div class="block2-txt-child2 flex-r p-t-3">
									<a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
										<img class="icon-heart1 dis-block trans-04" src="images/icons/icon-heart-01.png" alt="ICON">
										<img class="icon-heart2 dis-block trans-04 ab-t-l" src="images/icons/icon-heart-02.png" alt="ICON">
									</a>
								</div>
							</div>
						</div>
					</div>

					<div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
						<!-- Block2 -->
						<div class="block2">
							<div class="block2-pic hov-img0">
								<img src="images/product-06.jpg" alt="IMG-PRODUCT">

								<a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
									Quick View
								</a>
							</div>

							<div class="block2-txt flex-w flex-t p-t-14">
								<div class="block2-txt-child1 flex-col-l ">
									<a href="product-detail.html" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
										Vintage Inspired Classic 
									</a>

									<span class="stext-105 cl3">
										$93.20
									</span>
								</div>

								<div class="block2-txt-child2 flex-r p-t-3">
									<a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
										<img class="icon-heart1 dis-block trans-04" src="images/icons/icon-heart-01.png" alt="ICON">
										<img class="icon-heart2 dis-block trans-04 ab-t-l" src="images/icons/icon-heart-02.png" alt="ICON">
									</a>
								</div>
							</div>
						</div>
					</div>

					<div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
						<!-- Block2 -->
						<div class="block2">
							<div class="block2-pic hov-img0">
								<img src="images/product-07.jpg" alt="IMG-PRODUCT">

								<a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
									Quick View
								</a>
							</div>

							<div class="block2-txt flex-w flex-t p-t-14">
								<div class="block2-txt-child1 flex-col-l ">
									<a href="product-detail.html" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
										Shirt in Stretch Cotton
									</a>

									<span class="stext-105 cl3">
										$52.66
									</span>
								</div>

								<div class="block2-txt-child2 flex-r p-t-3">
									<a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
										<img class="icon-heart1 dis-block trans-04" src="images/icons/icon-heart-01.png" alt="ICON">
										<img class="icon-heart2 dis-block trans-04 ab-t-l" src="images/icons/icon-heart-02.png" alt="ICON">
									</a>
								</div>
							</div>
						</div>
					</div>

					<div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
						<!-- Block2 -->
						<div class="block2">
							<div class="block2-pic hov-img0">
								<img src="images/product-08.jpg" alt="IMG-PRODUCT">

								<a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
									Quick View
								</a>
							</div>

							<div class="block2-txt flex-w flex-t p-t-14">
								<div class="block2-txt-child1 flex-col-l ">
									<a href="product-detail.html" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
										Pieces Metallic Printed
									</a>

									<span class="stext-105 cl3">
										$18.96
									</span>
								</div>

								<div class="block2-txt-child2 flex-r p-t-3">
									<a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
										<img class="icon-heart1 dis-block trans-04" src="images/icons/icon-heart-01.png" alt="ICON">
										<img class="icon-heart2 dis-block trans-04 ab-t-l" src="images/icons/icon-heart-02.png" alt="ICON">
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@include('footer')
