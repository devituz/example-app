<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Falcon | Dashboard &amp; Web App Template</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/img/favicons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/img/favicons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/img/favicons/favicon-16x16.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/img/favicons/favicon.ico')}}">
    <link rel="manifest" href="{{asset('assets/img/favicons/manifest.json')}}">
    <meta name="msapplication-TileImage" content="{{asset('assets/img/favicons/mstile-150x150.png')}}">
    <meta name="theme-color" content="#ffffff">
    <script src="{{asset('assets/js/config.js')}}"></script>
    <script src="{{asset('vendors/overlayscrollbars/OverlayScrollbars.min.js')}}"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap"
        rel="stylesheet">
    <link href="{{asset('vendors/overlayscrollbars/OverlayScrollbars.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/theme-rtl.min.css')}}" rel="stylesheet" id="style-rtl">
    <link href="{{asset('assets/css/theme.min.css')}}" rel="stylesheet" id="style-default">
    <link href="{{asset('assets/css/user-rtl.min.css')}}" rel="stylesheet" id="user-style-rtl">
    <link href="{{asset('assets/css/user.min.css')}}" rel="stylesheet" id="user-style-default">
    <script>
        var isRTL = JSON.parse(localStorage.getItem('isRTL'));
        if (isRTL) {
            var linkDefault = document.getElementById('style-default');
            var userLinkDefault = document.getElementById('user-style-default');
            linkDefault.setAttribute('disabled', true);
            userLinkDefault.setAttribute('disabled', true);
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            var linkRTL = document.getElementById('style-rtl');
            var userLinkRTL = document.getElementById('user-style-rtl');
            linkRTL.setAttribute('disabled', true);
            userLinkRTL.setAttribute('disabled', true);
        }
    </script>
</head>
<body>

<main class="main" id="top">
    <div class="container" data-layout="container">
        <script>
            var isFluid = JSON.parse(localStorage.getItem('isFluid'));
            if (isFluid) {
                var container = document.querySelector('[data-layout]');
                container.classList.remove('container');
                container.classList.add('container-fluid');
            }
        </script>
        <nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
            @include('admin.toggle.toogle')
            <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
                <div class="navbar-vertical-content scrollbar">
                    <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
                        <li class="nav-item">
                            @include('admin.menu.menu')
                            @include('admin.chat.chat')
                        </li>
                    </ul>


                    @include('admin.ads.ads')


                </div>
            </div>
        </nav>
        <div class="content">

            @include('admin.navbar.navbar')


            @include('admin.footer.footer')


        </div>
    </div>
</main>


<script src="{{asset('vendors/popper/popper.min.js')}}"></script>
<script src="{{asset('vendors/bootstrap/bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/anchorjs/anchor.min.js')}}"></script>
<script src="{{asset('vendors/is/is.min.js')}}"></script>
<script src="{{asset('vendors/echarts/echarts.min.js')}}"></script>
<script src="{{asset('vendors/fontawesome/all.min.js')}}"></script>
<script src="{{asset('vendors/lodash/lodash.min.js')}}"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
<script src="{{asset('vendors/list.js/list.min.js')}}"></script>
<script src="{{asset('assets/js/theme.js')}}"></script>

</body>

</html>
