<!-- Header -->
<style>
    .avatar {
        vertical-align: middle;
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }
</style>

<header class="header-v4">
    <!-- Header desktop -->
    <div class="container-menu-desktop">
        <!-- Topbar -->
        <div class="top-bar">
            <div class="content-topbar flex-sb-m h-full container">
            </div>
        </div>

        <div class="wrap-menu-desktop how-shadow1">
            <nav class="limiter-menu-desktop container">
                
                <!-- Logo desktop -->		
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ $logo }}" alt="IMG-LOGO">
                </a>

                <!-- Menu desktop -->
                <div class="menu-desktop">
                    <ul class="main-menu">
                        <li class="active-menu">
                            <a href="{{ route('home') }}"><b>ホームページ</b></a>
                        </li>

                        <li>
                            <b>オークション</b>
                            <ul class="sub-menu">
                                <li>
                                    <a href="{{ route('productOfCategory', ['typeId' => 1]) }}"><b>{{ config('const.categories.1') }}</b></a>
                                </li>

                                <li>
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
                            </ul>
                        </li>

                        <li>
                            <a href="{{ route('createAuction') }}"><b>販売</b></a>
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
                <div class="wrap-icon-header flex-w flex-r-m" id="avatar-header">>
                    
                </div>

            </nav>
        </div>	
    </div>
</header>
