<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" sizes="96x96" href="<%= webpackConfig.output.publicPath %>favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=1, shrink-to-fit=no" name="viewport">
    <title>{{ $title }}</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    
  </head>
  <body class="sidebar-collapse">
    <div id="app"></div>
    <!-- built files will be auto injected -->
    <script src="{{ mix('js/app.js') }}" defer></script>
  </body>
</html>
