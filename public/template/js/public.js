$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function loadMore()
{
    const page = $('#page').val();
    $.ajax({
        type : 'POST',
        dataType : 'JSON',
        data : { page },
        url : '/services/load-auction',
        success : function (result) {
            if (result.html !== '') {
                $('#loadAuction').append(result.html);
                $('#page').val(page + 1);
            } else {
                alert('Đã load xong auction');
                $('#button-loadMore').css('display', 'none');
            }
        }
    })
}
