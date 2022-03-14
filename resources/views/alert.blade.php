@if (Session::has('error'))
    <div class="alert alert-danger" style="color:red">
        <b>{{ Session::get('error') }}</b>
    </div>
@endif


@if (Session::has('success'))
    <div class="alert alert-success" style="color:green">
        <b>{{ Session::get('success') }}</b>
    </div>
@endif
