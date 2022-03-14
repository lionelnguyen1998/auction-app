<!DOCTYPE html>
<html lang="en">
<head>
    @include('head')
</head>

<body class="animsition">

<!-- Header -->
@include('headerPage')

<!-- Report -->
@include('report')

@yield('content')

@include('footer')

</body>
</html>
