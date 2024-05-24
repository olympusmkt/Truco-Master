<!DOCTYPE html>
<html>
<head>
    <title>{{ env('APP_NAME_PRIVATE') }} - @yield('title')</title>
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/uikit.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />

    <!-- UIkit JS -->
    <script src="{{ asset('assets/js/uikit.min.js') }}"></script>
    <script src="{{ asset('assets/js/uikit-icons.min.js') }}"></script>

    <!-- Extra JS -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.mask.js') }}"></script>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/icons/dragon-1.png') }}">

    <!-- Metas -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name='viewport' content='width=device-width, initial-scale=1'>
</head>

<!-- Loadin HTML -->
<div class="wrapper" id="wrapper-loading">
    <div class="main-circle">
        <div class="green-circle">
            <div class="brown-circle"></div>
        </div>
    </div>
</div>

<body>
@yield('content')
</body>

<script>
    //var sticky base config
    var sticky = UIkit.sticky('.sticky', { offset: 100, bottom: 0 });

    //set interval loading jquery
    setTimeout(function() {
        $('#wrapper-loading').fadeOut(500, function(){
            $('#wrapper-loading').remove();
        });
    }, 1000);
</script>
</html>
