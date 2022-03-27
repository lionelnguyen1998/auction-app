
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
