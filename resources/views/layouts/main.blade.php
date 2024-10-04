<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />
    <!-- Favicon icon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">

    <!-- prism css -->
    @include('layouts.css.style_css')



</head>
<body>

    <!-- [ navigation menu ] start -->
    @include('layouts.navbar')
    <!-- [ navigation menu ] end -->

    <!-- [ Header ] start -->
    @include('layouts.header')
    <!-- [ Header ] end -->

    <br>

    <!-- [ Main Content ] start -->
    @yield('content')
    <!-- [ Main Content ] end -->

    @include('layouts.footer')

    <!-- Warning Section Ends -->

        <!-- Required Js -->
        @include('layouts.js.style_js')
        <!-- Close Js -->
</body>
</html>
