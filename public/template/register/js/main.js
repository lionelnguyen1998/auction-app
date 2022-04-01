$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#uploadAvatar').change(function () {
    const form = new FormData();
    form.append('file', $(this)[0].files[0]);

    $.ajax({
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'JSON',
        data: form,
        url: 'uploadAvatar/services',
        success: function (results) {
            console.log(results);
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
