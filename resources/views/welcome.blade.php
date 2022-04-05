<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">
    <!-- Css Styles -->
    <link rel="stylesheet" href="/templateJs/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="/templateJs/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="/templateJs/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="/templateJs/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="/templateJs/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="/templateJs/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="/templateJs/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="/templateJs/css/style.css" type="text/css">
  
     <!-- templateJs Main JS File -->
     <script src="/templateJs/js/jquery-3.3.1.min.js"></script>
    <script src="/templateJs/js/bootstrap.min.js"></script>
    <script src="/templateJs/js/jquery.nice-select.min.js"></script>
    <script src="/templateJs/js/jquery-ui.min.js"></script>
    <script src="/templateJs/js/jquery.slicknav.js"></script>
    <script src="/templateJs/js/mixitup.min.js"></script>
    <script src="/templateJs/js/owl.carousel.min.js"></script>
   <script src="/templateJs/js/main.js"></script>
</head>
<body>
    <!-- React root DOM -->
    <div id="root">
    </div>
    <!-- React JS -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
