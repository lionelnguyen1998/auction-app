@php
$reportType  = config('const.report_type');
$index = $contacts['report_type']
@endphp
<h4>
   タイトル/Chủ đề: {{ $reportType[$index] }}
</h4>
<h4>* * * * * * * * * * * *</h4>
<h4>
   差出人/Người gửi: {{ $contacts['name'] }}
</h4>
<h4>* * * * * * * * * * * *</h4>
<h4>
   メールアドレス/Địa chỉ Email: {{ $contacts['email'] }}
</h4>
<h4>* * * * * * * * * * * *</h4>
<h4>
   電話番号/Số điện thoại: {{ $contacts['phone'] }}
</h4>
<h4>* * * * * * * * * * * *</h4>
<h4>
   写真/Hình ảnh:{{ $contacts["file"] }}
</h4>
<h4>* * * * * * * * * * * *</h4>
<div>
    <h4>内容/Nội dung</h4>
    <p>
        {{ $contacts['content'] }}
    </p>
</div>