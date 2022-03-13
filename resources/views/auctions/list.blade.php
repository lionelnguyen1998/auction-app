@include('head')
<style>
    .block2-pic img {
        height: 200px;
        /* width: auto; */
    }
	.size-109 {
		width: 74px !important;
		height: 74px !important;
	}
	.m-r-18, .m-lr-18, .m-all-18 {
		margin-left: 100px;
	}
</style>
<body class="animsition">
	@include('headerPage')
	@include('cart2')
	
    <!-- Title page -->
	<section class="bg-img1 p-lr-15 p-tb-92" style="background-image: url('https://dbk.vn/uploads/ckfinder/images/1-content/background-dep-47.jpg');">
        
		<div class="size-210 bor10 flex-w flex-col-m p-lr-93 p-tb-30 p-lr-15-lg w-full-md">
			@if (auth()->user()->user_id != $inforUser->user_id)
				<div class="wrap-pic-s size-109 bor0 of-hidden m-r-18 m-t-6">
					<img src="{{ $inforUser->avatar }}" alt="AVATAR">
				</div>
			@endif
			<br>
			<div class="flex-w w-full p-b-42">
				<span class="fs-18 cl5 txt-center size-211">
					<span class="lnr lnr-user"></span>
				</span>
				<div class="size-212 p-t-2">
					<span class="mtext-110 cl2">
						Name: {{ $inforUser->name }}
					</span>
				</div>
			</div>

			<div class="flex-w w-full p-b-42">
				<span class="fs-18 cl5 txt-center size-211">
					<span class="lnr lnr-phone-handset"></span>
				</span>

				<div class="size-212 p-t-2">
					<span class="mtext-110 cl2">
						Phone: {{ $inforUser->phone }}
					</span>
				</div>
			</div>

			<div class="flex-w w-full p-b-42">
				<span class="fs-18 cl5 txt-center size-211">
					<span class="lnr lnr-envelope"></span>
				</span>

				<div class="size-212 p-t-2">
					<span class="mtext-110 cl2">
						Email: {{ $inforUser->email }}
					</span>
				</div>
			</div>

			<div class="flex-w w-full">
				<span class="fs-18 cl5 txt-center size-211">
					<span class="lnr lnr-map-marker"></span>
				</span>

				<div class="size-212 p-t-2">
					<span class="mtext-110 cl2">
						Address: {{ $inforUser->address }}
					</span>
				</div>
			</div>
		</div>
      
	</section>	

	<!-- Product -->
	<div class="bg0 m-t-23 p-b-140">
		<div class="container">
			<div class="flex-w flex-sb-m p-b-52">
				<div class="flex-w flex-l-m filter-tope-group m-tb-10">
					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" data-filter="*">
						全て
					</button>

					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".status1">
						{{ config('const.status.1') }}
					</button>

					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".status2">
						{{ config('const.status.2') }}
					</button>

					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".status3">
						{{ config('const.status.3') }}
					</button>

                    <button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".status4">
						{{ config('const.status.4') }}
					</button>
				</div>
			</div>

			<div class="row isotope-grid">
                @foreach ($auctions as $key => $auction)
                @php
                    $status = config('const.status');
                    $index = $auction["auction_status"]["status"];
                @endphp
                
				<!--dang dien ra-->
				@if ($index == 1)
                    <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item status1">
                        <!-- Block2 -->
                        <div class="block2">
                            <div class="block2-pic hov-img0">
                                <img src="{{ $auction["category"]["image"] }}" alt="IMG-PRODUCT">
                            </div>

                            <div class="block2-txt flex-w flex-t p-t-14">
                                <div class="block2-txt-child1 flex-col-l ">
                                    <a href="{{ route('detailAuctions', ['auctionId' => $auction['auction_id']]) }}" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                        {{ $auction['title'] }}
                                    </a>

                                    <span class="stext-105 cl3">
                                        @if ($index == 1)
                                            <p class="btn btn-success">{{ $status[$index] }}</p>
                                        @elseif ($index == 2)
                                            <p class="btn btn-warning">{{ $status[$index] }}</p>
                                        @else
                                            <p class="btn btn-danger">{{ $status[$index] }}</p>
                                        @endif
                                    </span>
                                </div>

                                <div class="block2-txt-child2 flex-r p-t-3">
                                    <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                                        <img class="icon-heart1 dis-block trans-04" src="/template/images/icons/icon-heart-01.png" alt="ICON">
                                        <img class="icon-heart2 dis-block trans-04 ab-t-l" src="/template/images/icons/icon-heart-02.png" alt="ICON">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
				@endif
				<!--sap dien ra-->
				@if ($index == 2)
					<div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item status2">
                        <!-- Block2 -->
                        <div class="block2">
                            <div class="block2-pic hov-img0">
                                <img src="{{ $auction["category"]["image"] }}" alt="IMG-PRODUCT">
                            </div>

                            <div class="block2-txt flex-w flex-t p-t-14">
                                <div class="block2-txt-child1 flex-col-l ">
                                    <a href="{{ route('detailAuctions', ['auctionId' => $auction['auction_id']]) }}" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                        {{ $auction['title'] }}
                                    </a>

                                    <span class="stext-105 cl3">
                                        @if ($index == 1)
                                            <p class="btn btn-success">{{ $status[$index] }}</p>
                                        @elseif ($index == 2)
                                            <p class="btn btn-warning">{{ $status[$index] }}</p>
                                        @else
                                            <p class="btn btn-danger">{{ $status[$index] }}</p>
                                        @endif
                                    </span>
                                </div>

                                <div class="block2-txt-child2 flex-r p-t-3">
                                    <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                                        <img class="icon-heart1 dis-block trans-04" src="/template/images/icons/icon-heart-01.png" alt="ICON">
                                        <img class="icon-heart2 dis-block trans-04 ab-t-l" src="/template/images/icons/icon-heart-02.png" alt="ICON">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
				@endif
				<!--da ket thuc-->
				@if ($index == 3)
					<div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item status3">
                        <!-- Block2 -->
                        <div class="block2">
                            <div class="block2-pic hov-img0">
                                <img src="{{ $auction["category"]["image"] }}" alt="IMG-PRODUCT">
                            </div>

                            <div class="block2-txt flex-w flex-t p-t-14">
                                <div class="block2-txt-child1 flex-col-l ">
                                    <a href="{{ route('detailAuctions', ['auctionId' => $auction['auction_id']]) }}" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                        {{ $auction['title'] }}
                                    </a>

                                    <span class="stext-105 cl3">
                                        @if ($index == 1)
                                            <p class="btn btn-success">{{ $status[$index] }}</p>
                                        @elseif ($index == 2)
                                            <p class="btn btn-warning">{{ $status[$index] }}</p>
                                        @else
                                            <p class="btn btn-danger">{{ $status[$index] }}</p>
                                        @endif
                                    </span>
                                </div>

                                <div class="block2-txt-child2 flex-r p-t-3">
                                    <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                                        <img class="icon-heart1 dis-block trans-04" src="/template/images/icons/icon-heart-01.png" alt="ICON">
                                        <img class="icon-heart2 dis-block trans-04 ab-t-l" src="/template/images/icons/icon-heart-02.png" alt="ICON">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
				@endif
                @if ($index == 4)
					<div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item status4">
                        <!-- Block2 -->
                        <div class="block2">
                            <div class="block2-pic hov-img0">
                                <img src="{{ $auction["category"]["image"] }}" alt="IMG-PRODUCT">
                            </div>

                            <div class="block2-txt flex-w flex-t p-t-14">
                                <div class="block2-txt-child1 flex-col-l ">
                                    <a href="{{ route('detailAuctions', ['auctionId' => $auction['auction_id']]) }}" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                        {{ $auction['title'] }}
                                    </a>

                                    <span class="stext-105 cl3">
                                        @if ($index == 1)
                                            <p class="btn btn-success">{{ $status[$index] }}</p>
                                        @elseif ($index == 2)
                                            <p class="btn btn-warning">{{ $status[$index] }}</p>
                                        @elseif ($index == 3)
                                            <p class="btn btn-danger">{{ $status[$index] }}</p>
                                        @else
                                            <p class="btn btn-info">{{ $status[$index] }}</p>
                                        @endif
                                    </span>
                                </div>

                                <div class="block2-txt-child2 flex-r p-t-3">
                                    <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                                        <img class="icon-heart1 dis-block trans-04" src="/template/images/icons/icon-heart-01.png" alt="ICON">
                                        <img class="icon-heart2 dis-block trans-04 ab-t-l" src="/template/images/icons/icon-heart-02.png" alt="ICON">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
				@endif
                @endforeach
			</div>

			<!-- Load more -->
			<div class="flex-c-m flex-w w-full p-t-45">
				<a href="#" class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04">
					もっと見る
				</a>
			</div>
		</div>
	</div>
@include('footer')
