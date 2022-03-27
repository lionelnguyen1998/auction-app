<script>
	var auctionApi = 'http://localhost:8080/api/auctions';

	function start() {
		getAuctions(function(auctions) {
			renderAuctions(auctions);
		});
	}

	start();

	// get list auctions
	function getAuctions(callback) {
		fetch(auctionApi) 
			.then(function(response) {
				return response.json();
			})
			.then(callback);
	}

	function getAuctionStatus(callback) {
		fetch(auctionApi + '/' + 2) 
			.then(function(response) {
				return response.json();
			})
			.then(callback);
	}

	function renderAuctions(auctions) {
		var auctions = auctions.data.auctions;
		var listAuctionBlock = document.querySelector('#list-auctions');
		var htmls = auctions.map(function(auction) {
			return `
			<div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item">
				<div class="block2">
					<div class="block2-pic hov-img0">
						<img src="${ auction.category.image}" alt="IMG-PRODUCT">

						<a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
							Quick View
						</a>
					</div>

					<div class="block2-txt flex-w flex-t p-t-14">
						<div class="block2-txt-child1 flex-col-l ">
							<a href="" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
							${ auction.title }
							</a>
							<span class="stext-105 cl3">
								<p class="btn btn-success">${ auction.status }</p>
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
			`;
		});
		listAuctionBlock.innerHTML = htmls.join('');
	}
</script>
<style>
	.container, .container-sm, .container-md, .container-lg, .container-xl {
		max-width: 1200px !important;
	}
</style>
@extends('main')
@section('content')

@include('slider')

	<!-- Product -->
	<section class="bg0 p-t-23 p-b-140">
		<div class="container">
			<div class="p-b-10">
				<h3 class="ltext-103 cl5">
					オークション一覧
				</h3>
			</div>

			<div class="flex-w flex-sb-m p-b-52">
				<div class="flex-w flex-l-m filter-tope-group m-tb-10">
					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1">
						全て 
					</button>

					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5">
						{{ config('const.status.1') }}
					</button>

					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" onclick="getAuctionStatus()">
						{{ config('const.status.2') }}
					</button>

					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5">
						{{ config('const.status.3') }}
					</button>
				</div>

				<div class="flex-w flex-c-m m-tb-10">
					<div class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
						<i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
						<i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
						 Filter
					</div>

					<div class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-tb-4 js-show-search">
						<i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
						<i class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
						Search
					</div>
				</div>
				
				<!-- Search product -->
				<div class="dis-none panel-search w-full p-t-10 p-b-15">
					<div class="bor8 dis-flex p-l-15">
						<button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
							<i class="zmdi zmdi-search"></i>
						</button>

						<input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="search-product" placeholder="Search">
					</div>	
				</div>

				<!-- Filter -->
				<div class="dis-none panel-filter w-full p-t-10">
                    <div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
                        <div class="filter-col1 p-r-15 p-b-27">
                            <div class="mtext-102 cl2 p-b-15">
                                Sort By
                            </div>

                            <ul>
                                <li class="p-b-6">
                                    <a href="{{ request()->url() }}" class="filter-link stext-106 trans-04">
                                        Default
                                    </a>
                                </li>

                                <li class="p-b-6">
                                    <a href="{{ request()->fullUrlWithQuery(['starting-price' => 'asc']) }}" class="filter-link stext-106 trans-04">
                                        Giá khởi điểm: Low to High
                                    </a>
                                </li>

                                <li class="p-b-6">
                                    <a href="{{ request()->fullUrlWithQuery(['starting-price' => 'desc']) }}" class="filter-link stext-106 trans-04">
                                        Giá khởi điểm: High to Low
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="filter-col2 p-r-15 p-b-27">
                            <div class="mtext-102 cl2 p-b-15">
                                Giá khởi điểm
                            </div>

                            <ul>
                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04 filter-link-active">
                                        All
                                    </a>
                                </li>

                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04">
                                        $0.00 - $50.00
                                    </a>
                                </li>

                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04">
                                        $50.00 - $100.00
                                    </a>
                                </li>
                            </ul>
                        </div>


                    </div>
                </div>
			</div>

			<div class="row isotope-grid">
				<div id="list-auctions">

				</div>
			</div>
			<!-- Load more -->
			<div class="flex-c-m flex-w w-full p-t-45" id="button-loadMore">
				<input type="hidden" value="1" id="page">
                <a onclick="loadMore()" class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04">
                    Load More
                </a>
			</div>
		</div>
	</section>
	<!-- Back to top -->
	<div class="btn-back-to-top" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="zmdi zmdi-chevron-up"></i>
		</span>
	</div>
@endsection