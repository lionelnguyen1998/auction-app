@php
$reportType  = config('const.report_type');
$index = $contacts['report_type']
@endphp
<h4>
   タイトル: {{ $reportType[$index] }}
</h4>
<h4>* * * * * * * * * * * *</h4>
<h4>
   差出人: {{ $contacts['name'] }}
</h4>
<h4>* * * * * * * * * * * *</h4>
<h4>
   メールアドレス: {{ $contacts['email'] }}
</h4>
<h4>* * * * * * * * * * * *</h4>
<h4>
   電話番号: {{ $contacts['phone'] }}
</h4>
<h4>* * * * * * * * * * * *</h4>
<h4>
   写真: {{ $contacts['file'] }}
</h4>
<h4>* * * * * * * * * * * *</h4>
<div>
    <h4>内容</h4>
    <p>
        {{ $contacts['content'] }}
    </p>
</div>