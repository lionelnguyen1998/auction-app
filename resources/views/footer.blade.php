
<!--===============================================================================================-->
<!-- <script src="/template/vendor/jquery/jquery-3.2.1.min.js"></script> -->
 <!-- jQuery -->
 <script src="/template/admin/plugins/jquery/jquery.min.js"></script>
<!--===============================================================================================-->
<script src="/template/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
<script src="/template/vendor/bootstrap/js/popper.js"></script>
<script src="/template/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="/template/vendor/select2/select2.min.js"></script>
<script>
    $(".js-select2").each(function(){
        $(this).select2({
            allowClear: true,
            placeholder: '選択してください',
            dropdownParent: $(this).next('.dropDownSelect2')
        });
    })
</script>
<!--===============================================================================================-->
<script src="/template/vendor/daterangepicker/moment.min.js"></script>
<script src="/template/vendor/daterangepicker/daterangepicker.js"></script>
<script>
    $(function() {
        $('input[name="start_date"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1998,
            maxYear: parseInt(moment().format('YYYY'),10),
            timePicker: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
            format: 'YYYY/MM/DD hh:mm A'
            }
        }, function(start, end, label) {
            var years = moment().diff(start, 'years');
        });

        $('input[name="end_date"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1998,
        maxYear: parseInt(moment().format('YYYY'),10),
        timePicker: true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
        format: 'YYYY/MM/DD hh:mm A'
        }
        }, function(start, end, label) {
            var years = moment().diff(start, 'years');
        });
    });
</script>
<!--===============================================================================================-->
<script src="/template/vendor/slick/slick.min.js"></script>
<script src="/template/js/slick-custom.js"></script>
<!--===============================================================================================-->
<script src="/template/vendor/parallax100/parallax100.js"></script>
<script>
    $('.parallax100').parallax100();
</script>
<!--===============================================================================================-->
<script src="/template/vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
<script>
    $('.gallery-lb').each(function() { // the containers for all your galleries
        $(this).magnificPopup({
            delegate: 'a', // the selector for gallery item
            type: 'image',
            gallery: {
                enabled:true
            },
            mainClass: 'mfp-fade'
        });
    });
</script>
<!--===============================================================================================-->
<script src="/template/vendor/isotope/isotope.pkgd.min.js"></script>
<!--===============================================================================================-->
<script src="/template/vendor/sweetalert/sweetalert.min.js"></script>
<script>
    $('.js-addwish-b2').on('click', function(e){
        e.preventDefault();
    });

    $('.js-addwish-b2').each(function(){
        var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
        $(this).on('click', function(){
            swal(nameProduct, "is added to wishlist !", "success");

            $(this).addClass('js-addedwish-b2');
            $(this).off('click');
        });
    });

    $('.js-addwish-detail').each(function(){
        var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

        $(this).on('click', function(){
            swal(nameProduct, "is added to wishlist !", "success");

            $(this).addClass('js-addedwish-detail');
            $(this).off('click');
        });
    });

    /*---------------------------------------------*/

    $('.js-addcart-detail').each(function(){
        var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
        $(this).on('click', function(){
            swal(nameProduct, "is added to cart !", "success");
        });
    });

</script>
<!--===============================================================================================-->
<script src="/template/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script>
    $('.js-pscroll').each(function(){
        $(this).css('position','relative');
        $(this).css('overflow','hidden');
        var ps = new PerfectScrollbar(this, {
            wheelSpeed: 1,
            scrollingThreshold: 1000,
            wheelPropagation: false,
        });

        $(window).on('resize', function(){
            ps.update();
        })
    });
</script>
<!--===============================================================================================-->
<script src="/template/js/main.js"></script>
<script src="/template/js/public.js"></script>

<!-- Toastr -->
<script src="/template/admin/plugins/toastr/toastr.min.js"></script>
<!-- vuejs -->
<script src="/js/app.js"></script>

<script>
    /*Upload File User */
    @for ($i = 1; $i <= 5; $i ++)
        $('#uploaduser-{{$i}}').change(function () {
            const form = new FormData();
            form.append('file', $(this)[0].files[0]);

            $.ajax({
                processData: false,
                contentType: false,
                type: 'POST',
                dataType: 'JSON',
                data: form,
                url: "",
                success: function (results) {
                    if (results.error === false) {
                            $('#image_show_user-{{$i}}').html('<a href="' + results.url + '" target="_blank">' +
                                '<img src="' + results.url + '" width="100px"></a>')
                            $('#thumbuser-{{$i}}').val(results.url);
                    } else {
                        alert('Upload File Lỗi');
                    }
                }
            });
        });
    @endfor

    $('#uploadAvatar').change(function () {
        const form = new FormData();
        form.append('file', $(this)[0].files[0]);

        $.ajax({
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'JSON',
            data: form,
            url: "",
            success: function (results) {
                if (results.error === false) {
                        $('#image_show_avatar').html('<a href="' + results.url + '" target="_blank">' +
                            '<img src="' + results.url + '" width="100px"></a>')
                        $('#avatar').val(results.url);
                } else {
                    alert('Upload File Lỗi');
                }
            }
        });
    });

    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function () {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })
        @if(Session::has('message'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true
            }
            toastr.success("{{ session('message') }}");
        @endif

        @if(Session::has('error'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true
            }
            toastr.error("{{ session('error') }}");
        @endif

        @if(Session::has('info'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true
            }
            toastr.info("{{ session('info') }}");
        @endif

        @if(Session::has('warning'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true
            }
            toastr.warning("{{ session('warning') }}");
        @endif

</script>
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
        const localStorageUser = JSON.parse(localStorage.getItem('token'));
        const inMemmoryToken = localStorageUser.access_token;
		fetch(auctionApi, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json, text/plain',
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + `${inMemmoryToken}`
                }
            }) 
			.then(function(response) {
				return response.json();
			})
            .then(res => {
                const avatar = document.getElementById('avatar-header');
                console.log(res)
                if (res.user_info) {
                    avatar.innerHTML = `
                    <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11">
                        <div data-toggle="dropdown" aria-hidden="true">
                            <img src="${res.user_info.avatar}" alt="Avatar" class="avatar">
                        </div>        
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="#" style="padding-bottom:20px">おはいよ! ${res.user_info.name}</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="">編集</a>
                            <a class="dropdown-item" href="{{ route('logoutUser') }}">ログアウト</a>
                        </div>	
                    </div>
                    <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart" data-notify="2">
                        <i class="zmdi zmdi-notifications-active"></i>
                    </div>

                    <a href="#" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11">
                        <i class="zmdi zmdi-favorite-outline"></i>
                    </a>
                
                    <a href="" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11">
                        <i class="zmdi zmdi-spellcheck"></i>
                    </a>
                    `;
                } else {
                    avatar.innerHTML = `
                    <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11">
                        <div data-toggle="dropdown" aria-hidden="true">
                            <i class="zmdi zmdi-account"></i>
                        </div>        
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="{{ route('registerUser') }}">登録</a>
                            <a class="dropdown-item" href="{{ route('loginUser') }}">ログイン</a>
                        </div>	
                    </div>
                    `;
                }
            })
			.then(callback);
	}


	function getAuctionByStatus(id, callback) {
		fetch(auctionApi + '/' + id) 
			.then(function(response) {
				return response.json();
			})
			.then(callback);
	}

	function renderAuctions(auctions) {
		var auctions = auctions.data.auctions;
		var listAuctionBlock = document.querySelector('#list-auctions');
		var htmls = auctions.map(function(auction) {
		var status;
			if (auction.statusId == 1) {
				status = `<p class="btn btn-success">${ auction.status }</p>`;
			} else if (auction.statusId == 2) {
				status = `<p class="btn btn-warning">${ auction.status }</p>`;
			} else if (auction.statusId == 3) {
				status = `<p class="btn btn-danger">${ auction.status }</p>`;
			} else {
				status = `<p class="btn btn-info">${ auction.status }</p>`;
			}
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
								${status}
							</span>
						</div>

						<div class="block2-txt-child2 flex-r p-t-3">
							<a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
								<img class="icon-heart1 dis-block trans-04" src="template/images/icons/icon-heart-01.png" alt="ICON">
								<img class="icon-heart2 dis-block trans-04 ab-t-l" src="template/images/icons/icon-heart-02.png" alt="ICON">
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
<script>
    
</script>
