<!DOCTYPE html>
<html>
<head>
	<title>{{ env('APP_NAME') }} - @yield('title')</title>
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
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/icons/public/icon.png') }}">

    <!-- Metas -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style type="text/css">
        .uk-button-primary { background-color: {{ env('APP_COLOR_PUBLIC') }}!important;}
        .uk-subnav-pill>.uk-active>a { background-color: {{ env('APP_COLOR_PUBLIC') }}!important; }
        .uk-badge { background-color: {{ env('APP_COLOR_PUBLIC') }}; }
        .header-user-draken { background-color: #212425!important; border-bottom: solid 1px #ffffff30; }
        .uk-card-default { background-color: #323637; }
        .uk-card-default .uk-card-title { color: #fff; }
        .uk-card-default label { color: #fff; }
        .uk-card-default .uk-card-header { border-bottom: 1px solid #e5e5e51f; }
        .uk-card-default .uk-card-footer { border-top: 1px solid #e5e5e51f; }
    </style>
</head>

<script type="text/javascript">
    function function_get_updater() {
        $.ajax({
            type: 'GET',
            url: "{{ env('APP_URL') }}Get_Wallet_Api/saldo",
            success: function (data) {
                $("#money_user").html(data.value);
                $("#money_user_mobile").html(data.value);
            }
        });
    }
    setInterval(function() {
        function_get_updater();
    }, 2000);
</script>

<!-- Loadin HTML -->
<div class="wrapper" id="wrapper-loading">
    <div class="main-circle">
        <div class="green-circle">
            <div class="brown-circle"></div>
        </div>
    </div>
</div>

<!-- Navbar HTML -->
<header class="header-user-draken">
    <nav class="uk-container uk-navbar">
        <div class="uk-navbar-left">
            <ul class="uk-navbar-nav">
                <li class="uk-active">
                    <a href="{{ env('APP_URL') }}"><img style="width: 5em;" src="{{ asset('assets/icons/public/logo.png') }}"></a>
                </li>
            </ul>
        </div>
        <div class="uk-navbar-right" uk-navbar>
            <ul class="uk-navbar-nav uk-visible@s">
                <?php if (!auth()->check()) { ?>
                   <li><a class="uk-text-large" href="{{ env('APP_URL') }}">Home</a></li>
                   <li><a class="uk-text-large" href="{{ env('APP_URL') }}user/login">Login</a></li>
                   <li><a class="uk-text-large" href="{{ env('APP_URL') }}user/registro">Registro</a></li>
                <?php  } else { ?>
                    <?php 
                    // Count functions
                    $functions_router = DB::table('functions')->where('type', '=','simple_user')->get();
                    $functions_router_total = $functions_router->count();

                    if ($functions_router_total > 0) {
                        foreach ($functions_router as $functions_router_row) {
                            echo '<li>
                            <a>'.$functions_router_row->name.'</a>
                            <div class="uk-navbar-dropdown">
                                <ul class="uk-nav uk-navbar-dropdown-nav">';
                            $json_item = json_decode($functions_router_row->arrays);
                            foreach ($json_item->function as $key) {
                                if ($key->navbar == "true") {
                                    if ($key->name != "Editar ".$functions_router_row->name."") {

                                        $link_fixed = substr($key->link, 1);
                                        if ($key->name == "Listar ".$functions_router_row->name."") {
                                            echo ' <li><a href="'.env('APP_URL').''.$link_fixed.'">'.$key->name.'</a>';
                                        } else {
                                            echo ' <li><a href="'.env('APP_URL').''.$link_fixed.'">'.$key->name.'</a>';
                                        }
                                    } 
                                }
                                
                            }

                            echo '</ul></div></li>';
                        }
                    }
                    ?>
                    <li><a class="uk-text-large" href="{{ env('APP_URL') }}user/truco">Jogar</a></li>
                    <li><a><span class="saldo_class"><svg style="margin-top: -0.2em;" fill="#ffffff" width="19px" height="19px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M12.32 8a3 3 0 0 0-2-.7H5.63A1.59 1.59 0 0 1 4 5.69a2 2 0 0 1 0-.25 1.59 1.59 0 0 1 1.63-1.33h4.62a1.59 1.59 0 0 1 1.57 1.33h1.5a3.08 3.08 0 0 0-3.07-2.83H8.67V.31H7.42v2.3H5.63a3.08 3.08 0 0 0-3.07 2.83 2.09 2.09 0 0 0 0 .25 3.07 3.07 0 0 0 3.07 3.07h4.74A1.59 1.59 0 0 1 12 10.35a1.86 1.86 0 0 1 0 .34 1.59 1.59 0 0 1-1.55 1.24h-4.7a1.59 1.59 0 0 1-1.55-1.24H2.69a3.08 3.08 0 0 0 3.06 2.73h1.67v2.27h1.25v-2.27h1.7a3.08 3.08 0 0 0 3.06-2.73v-.34A3.06 3.06 0 0 0 12.32 8z"></path></g></svg> R$<span id="money_user"></span></span></a></li>
                    <li>
                        <a class="uk-navbar-item">
                            <img class="uk-border-circle" src="{{ asset('assets/icons/users/149071.png') }}" width="40" height="40">
                        </a>
                        <div class="uk-navbar-dropdown">
                            <ul class="uk-nav uk-navbar-dropdown-nav">
                                <li>
                                    <a href="{{ env('APP_URL') }}user/minha-conta"><span class="uk-margin-small-right" uk-icon="icon: user"></span>Minha Conta</a>
                                </li>
                                <li class="uk-nav-divider"></li>
                                <li>
                                    <a href="{{ env('APP_URL') }}user/sair"><span class="uk-margin-small-right" uk-icon="icon: sign-out"></span>Sair</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            </ul>
            <a href="#" class="uk-navbar-toggle uk-hidden@s" uk-navbar-toggle-icon uk-toggle="target: #sidenav"></a>
        </div>
    </nav>
</header>

<div id="sidenav" uk-offcanvas="flip: true" class="uk-offcanvas">
    <div class="uk-offcanvas-bar">
        <ul class="uk-nav">
            <?php if (!auth()->check()) { ?>
               <li><a class="uk-text-large" href="{{ env('APP_URL') }}">Home</a></li>
               <li><a class="uk-text-large" href="{{ env('APP_URL') }}user/login">Login</a></li>
               <li><a class="uk-text-large" href="{{ env('APP_URL') }}user/registro">Registro</a></li>
            <?php  } else { ?>
                <li><a class="uk-text-large" href="{{ env('APP_URL') }}user/truco">Jogar</a></li>
                <li><a><span class="saldo_class"><svg style="margin-top: -0.2em;" fill="#ffffff" width="19px" height="19px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M12.32 8a3 3 0 0 0-2-.7H5.63A1.59 1.59 0 0 1 4 5.69a2 2 0 0 1 0-.25 1.59 1.59 0 0 1 1.63-1.33h4.62a1.59 1.59 0 0 1 1.57 1.33h1.5a3.08 3.08 0 0 0-3.07-2.83H8.67V.31H7.42v2.3H5.63a3.08 3.08 0 0 0-3.07 2.83 2.09 2.09 0 0 0 0 .25 3.07 3.07 0 0 0 3.07 3.07h4.74A1.59 1.59 0 0 1 12 10.35a1.86 1.86 0 0 1 0 .34 1.59 1.59 0 0 1-1.55 1.24h-4.7a1.59 1.59 0 0 1-1.55-1.24H2.69a3.08 3.08 0 0 0 3.06 2.73h1.67v2.27h1.25v-2.27h1.7a3.08 3.08 0 0 0 3.06-2.73v-.34A3.06 3.06 0 0 0 12.32 8z"></path></g></svg> R$<span id="money_user_mobile"></span></span></a></li>
                <?php 
                // Count functions
                $functions_router = DB::table('functions')->where('type', '=','simple_user')->get();
                $functions_router_total = $functions_router->count();

                if ($functions_router_total > 0) {
                    $json_item = json_decode($functions_router_row->arrays);
                    foreach ($json_item->function as $key) {
                        if ($key->navbar == "true") {
                            if ($key->name != "Editar ".$functions_router_row->name."") {

                                $link_fixed = substr($key->link, 1);
                                if ($key->name == "Listar ".$functions_router_row->name."") {
                                    echo ' <li><a href="'.env('APP_URL').''.$link_fixed.'">'.$key->name.'</a>';
                                } else {
                                    echo ' <li><a href="'.env('APP_URL').''.$link_fixed.'">'.$key->name.'</a>';
                                }
                            } 
                        }
                        
                    }   
                }
                ?>
               <li><a class="uk-text-large" href="{{ env('APP_URL') }}user/minha-conta">Minha Conta</a></li>
               <li><a class="uk-text-large" href="{{ env('APP_URL') }}user/sair">Sair</a></li>
            <?php } ?>
        </ul>
    </div>
</div>


<body>
	@yield('content')
</body>

<script>
    function_get_updater();
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
