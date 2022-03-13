<!-- Header -->
<style>
    .avatar {
        vertical-align: middle;
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }
</style>
<header>
    <!-- Topbar -->
        <div class="top-bar">
            <div class="content-topbar flex-sb-m h-full container">
                <div class="left-top-bar">
                    Free shipping for standard order over $100
                </div>

                <div class="right-top-bar flex-w h-full">
                    <a href="#" class="flex-c-m trans-04 p-lr-25">
                        Help & FAQs
                    </a>

                    <a href="#" class="flex-c-m trans-04 p-lr-25">
                        My Account
                    </a>

                    <a href="#" class="flex-c-m trans-04 p-lr-25">
                        EN
                    </a>

                    <a href="#" class="flex-c-m trans-04 p-lr-25">
                        USD
                    </a>
                </div>
            </div>
        </div>

		<!-- Header desktop -->
		<div class="container-menu-desktop">

			<div class="wrap-menu-desktop">
				<nav class="limiter-menu-desktop container">
					<a href="{{ route('home') }}" class="logo">
						<img src="{{ $logo }}" alt="IMG-LOGO">
					</a>
					<!-- Menu desktop -->
					<div class="menu-desktop">
						<ul class="main-menu">
							<li class="active-menu">
								<a href="{{ route('home') }}"><b>ホームページ</b></a>
							</li>

							<li >
								<a href="{{ route('productOfCategory', ['typeId' => 1]) }}"><b>{{ config('const.categories.1') }}</b></a>
							</li>

							<li class="label1" data-label1="hot">
								<a href="{{ route('productOfCategory', ['typeId' => 2]) }}"><b>{{ config('const.categories.2') }}</b></a>
							</li>

							<li>
								<a href="{{ route('productOfCategory', ['typeId' => 3]) }}"><b>{{ config('const.categories.3') }}</b></a>
							</li>

							<li>
								<a href="{{ route('productOfCategory', ['typeId' => 4]) }}"><b>{{ config('const.categories.4') }}</b></a>
							</li>

							<li>
								<a href="{{ route('productOfCategory', ['typeId' => 5]) }}"><b>{{ config('const.categories.5') }}</b></a>
							</li>
							<li>
								<a href="{{ route('productOfCategory', ['typeId' => 5]) }}"><b>情報</b></a>
							</li>
							<li>
								<a href="{{ route('contact') }}"><b>お間に合わせ</b></a>
							</li>
						</ul>
					</div>	

					<!-- Icon header -->
					<div class="wrap-icon-header flex-w flex-r-m">
						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
							<i class="zmdi zmdi-search"></i>
						</div>

						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11">
							@if (auth()->user())
								<div data-toggle="dropdown" aria-hidden="true">
									<img src="{{ auth()->user()->avatar }}" alt="Avatar" class="avatar">
								</div> 
							@else
								<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11" data-toggle="dropdown" aria-hidden="true">
									<i class="zmdi zmdi-account"></i>
								</div>
							@endif
							<div class="dropdown-menu" role="menu">
								@if (auth()->user())
									<a class="dropdown-item" href="#" style="padding-bottom:20px">おはいよ! {{ auth()->user()->name }}</a>
									
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="{{ route('editUser', ['userId' => auth()->user()->user_id]) }}">編集</a>
									<a class="dropdown-item" href="{{ route('logoutUser') }}">ログアウト</a>
								@else 
									<a class="dropdown-item" href="{{ route('registerUser') }}">登録</a>
									<a class="dropdown-item" href="{{ route('loginUser') }}">ログイン</a>
								@endif
							</div>	
						</div>

						@if (auth()->user())
							<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart" data-notify="2">
								<i class="zmdi zmdi-notifications-active"></i>
							</div>

							<a href="#" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11">
								<i class="zmdi zmdi-favorite-outline"></i>
							</a>
						
							<a href="{{ route('listAuctions', ['userId' => auth()->user()->user_id]) }}" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11">
								<i class="zmdi zmdi-spellcheck"></i>
							</a>
						@endif
					</div>
				</nav>
			</div>	
		</div>

		<!-- Header Mobile -->
		<div class="wrap-header-mobile">
			<!-- Logo moblie -->		
			<div class="logo-mobile">
				<a href="index.html"><img src="template/images/logoAuction.jpg" alt="IMG-LOGO"></a>
			</div>

			<!-- Icon header -->
			<div class="wrap-icon-header flex-w flex-r-m m-r-15">
				<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search">
					<i class="zmdi zmdi-search"></i>
				</div>

				<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart" data-notify="2">
					<i class="zmdi zmdi-shopping-cart"></i>
				</div>

				<a href="#" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti" data-notify="0">
					<i class="zmdi zmdi-favorite-outline"></i>
				</a>
			</div>

			<!-- Button show menu -->
			<div class="btn-show-menu-mobile hamburger hamburger--squeeze">
				<span class="hamburger-box">
					<span class="hamburger-inner"></span>
				</span>
			</div>
		</div>


		<!-- Menu Mobile -->
		<div class="menu-mobile">
			<ul class="main-menu-m">
				<li>
					<a href="index.html">Home</a>
					<ul class="sub-menu-m">
						<li><a href="index.html">Homepage 1</a></li>
						<li><a href="home-02.html">Homepage 2</a></li>
						<li><a href="home-03.html">Homepage 3</a></li>
					</ul>
					<span class="arrow-main-menu-m">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</span>
				</li>

				<li>
					<a href="product.html">Shop</a>
				</li>

				<li>
					<a href="shoping-cart.html" class="label1 rs1" data-label1="hot">Features</a>
				</li>

				<li>
					<a href="blog.html">Blog</a>
				</li>

				<li>
					<a href="about.html">About</a>
				</li>

				<li>
					<a href="contact.html">Contact</a>
				</li>
			</ul>
		</div>

		<!-- Modal Search -->
		<div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
			<div class="container-search-header">
				<button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
					<i class="fa fa-times" aria-hidden="true"></i>
				</button>

				<form class="wrap-search-header flex-w p-l-15">
					<button class="flex-c-m trans-04">
						<i class="zmdi zmdi-search"></i>
					</button>
					<input class="plh3" type="text" name="search" placeholder="検索...">
				</form>
			</div>
		</div>
	</header>
