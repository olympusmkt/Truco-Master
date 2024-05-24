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

<?php
// Get functions acess roles
$functions_for_user = $roles->roles->acess;
$functions_for_user_arrays = explode(", ", $functions_for_user);

// Tag count atrib
$admin_manager = "0";
$user_manager = "0";
$filler_manager = "0";
$source_manager = "0";
$spyadmin_manager = "0";

// For validation values
foreach ($functions_for_user_arrays as $row_user) {
    if ($row_user == "admin_manager") {
        $admin_manager++;
    } else if ($row_user == "user_manager") {
        $user_manager++;
    } else if ($row_user == "filler_manager") {
        $filler_manager++;
    } else if ($row_user == "source_manager") {
        $source_manager++;
    } else if ($row_user == "spyadmin_manager") {
        $spyadmin_manager++;
    }
}
?>

<!-- Loadin HTML -->
<div class="wrapper" id="wrapper-loading">
    <div class="main-circle">
        <div class="green-circle">
            <div class="brown-circle"></div>
        </div>
    </div>
</div>

<body>
    <!-- menu section 1 -->
    <div class="uk-background-primary uk-light uk-preserve-color uk-hidden@m" uk-sticky id="bg-draken-dash-admin">
        <div class="uk-container">
            <nav class="uk-navbar" uk-navbar>
                <div class="uk-navbar-left">
                    <a class="uk-navbar-toggle" uk-navbar-toggle-icon uk-toggle href="#offcanvas"></a>
                </div>
                <div class="uk-navbar-center">
                    <a class="uk-navbar-item uk-logo" href="{{ env('APP_URL') }}dashboard">
                        <img class="logo-dash" src="{{ asset('assets/icons/dragon-2.png') }}"> DRAKEN CODE
                    </a>
                </div>
                <div class="uk-navbar-right">
                    <ul class="uk-navbar-nav">
                        <li>
                            <a href="#">
                                <img class="uk-border-circle" data-src="{{ asset('assets/icons/users/149071.png') }}" width="30" height="30" alt="Jane Doe">
                            </a>
                            <div class="uk-navbar-dropdown">
                                <ul class="uk-nav uk-navbar-dropdown-nav">
                                    <li>
                                        <a href="minha-conta"><span class="uk-margin-small-right" uk-icon="icon: user"></span>Minha Conta</a>
                                    </li>
                                    <li class="uk-nav-divider"></li>
                                    <li>
                                        <a href="sair"><span class="uk-margin-small-right" uk-icon="icon: sign-out"></span>Sair</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <!-- menu section 2 -->
    <header class="uk-background-primary uk-navbar-container uk-navbar-transparent uk-light uk-preserve-color uk-visible@m" uk-sticky id="bg-draken-dash-admin">
        <div class="uk-container uk-container-expand">
            <nav class="uk-navbar" uk-navbar>
                <div class="uk-navbar-left">
                    <a class="uk-navbar-item uk-logo" href="{{ env('APP_URL') }}dashboard">
                        <img class="logo-dash" src="{{ asset('assets/icons/dragon-2.png') }}"> DRAKEN CODE
                    </a>
                    <div class="uk-navbar-item">
                        <span uk-version></span>
                        <span lenis-version></span>
                    </div>
                </div>
                <div class="uk-navbar-right">
                    <ul class="uk-navbar-nav">
                        <!--<li>
                            <a class="uk-navbar-toggle" uk-search-icon href="#"></a>
                            <div class="uk-drop" uk-drop="mode: click; pos: left-center; offset: 0">
                                <form class="uk-search uk-search-navbar uk-width-1-1">
                                    <input class="uk-search-input" type="search" placeholder="Search..." autofocus>
                                </form>
                            </div>
                        </li>
                        <li>
                            <a class="uk-navbar-item" href="#"><span uk-icon="icon: mail"></span></a>
                        </li>-->
                        <li>
                            <a class="uk-navbar-item" href="#"><span uk-icon="icon: bell"></span></a>
                        </li>
                    </ul>
                    <ul class="uk-navbar-nav">
                        <li>
                            <a class="uk-navbar-item">
                                <img class="uk-border-circle" src="{{ asset('assets/icons/users/149071.png') }}" width="40" height="40">
                            </a>
                            <div class="uk-navbar-dropdown">
                                <ul class="uk-nav uk-navbar-dropdown-nav">
                                    <li>
                                        <a href="{{ env('APP_URL') }}dashboard/minha-conta"><span class="uk-margin-small-right" uk-icon="icon: user"></span>Minha Conta</a>
                                    </li>
                                    <li class="uk-nav-divider"></li>
                                    <li>
                                        <a href="{{ env('APP_URL') }}dashboard/sair"><span class="uk-margin-small-right" uk-icon="icon: sign-out"></span>Sair</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header >

    <div class="uk-grid uk-grid-collapse">
        <aside class="uk-width-medium uk-position-relative uk-visible@m">
            <div class="uk-tile uk-tile-default uk-tile-small uk-width-medium uk-position-fixed uk-box-shadow-medium" uk-overflow-auto style="top: 80px; bottom: 0;" data-lenis-prevent>
                <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
                    <li class="uk-nav-header">Dashboard</li>
                    <?php if ($admin_manager > 0) { ?>
                        <li class="uk-parent" id="admin_manager">
                            <a href="#"><span class="uk-margin-small-right" uk-icon="icon: user"></span>Gerenciar Administradores <span uk-nav-parent-icon></span></a>
                            <ul class="uk-nav-sub">
                                <li><a href="{{ env('APP_URL') }}dashboard/administradores">Listar Administradores</a></li>
                                <li><a href="{{ env('APP_URL') }}dashboard/adicionar-administrador">Adicionar Administrador</a></li>

                            </ul>
                        </li>
                    <?php } ?>
                    <?php if ($user_manager > 0) { ?>
                        <li class="uk-parent" id="user_manager">
                            <a href="#"><span class="uk-margin-small-right" uk-icon="icon: users"></span>Gerenciar Usuários <span uk-nav-parent-icon></span></a>
                            <ul class="uk-nav-sub">
                                <li><a href="{{ env('APP_URL') }}dashboard/usuarios">Listar Usuários</a></li>
                                <li><a href="{{ env('APP_URL') }}dashboard/adicionar-usuario">Adicionar Usuário</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    
                    <?php 
                    // Count functions
                    $functions_router = DB::table('functions')->where('type', '=','customize_admin')->get();
                    $functions_router_total = $functions_router->count();

                    if ($functions_router_total > 0) {
                        echo '<li class="uk-nav-header">Funções</li>';
                        foreach ($functions_router as $functions_router_row) {
                            echo '<li class="uk-parent" id="'.$functions_router_row->function.'_manager">
                            <a href="#"><span class="uk-margin-small-right" uk-icon="icon: bookmark"></span>'.$functions_router_row->name.'<span uk-nav-parent-icon></span></a>
                            <ul class="uk-nav-sub">';
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
                            echo '</ul></li>';
                        }
                    }
                    ?>
                    <?php if ($source_manager > 0) { ?>
                        <li class="uk-nav-header">Source</li>
                        <li class="uk-parent" id="source_manager">
                            <a href="#"><span class="uk-margin-small-right" uk-icon="icon: cog"></span>Gerenciar Funções <span uk-nav-parent-icon></span></a>
                            <ul class="uk-nav-sub">
                                <li><a href="{{ env('APP_URL') }}dashboard/funcoes">Minhas Funções</a></li>
                                <li><a href="{{ env('APP_URL') }}dashboard/source">Adicionar Funções</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if ($filler_manager > 0) { ?>
                        <li class="uk-nav-header">Uploads</li>
                        <li id="filler_manager">
                            <a href="{{ env('APP_URL') }}dashboard/gerenciador-de-arquivos"><span class="uk-margin-small-right" uk-icon="icon: folder"></span>Gerenciar Uploads</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </aside>
        <main class="uk-section uk-section-small uk-section-muted uk-width-expand">
            <div class="uk-container uk-container-expand">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- menu section mobile -->
    <div id="offcanvas" uk-offcanvas="overlay: true" data-lenis-prevent>
        <div class="uk-offcanvas-bar">
            <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
                <li class="uk-nav-header">Dashboard</li>
                <?php if ($admin_manager > 0) { ?>
                    <li class="uk-parent" id="admin_manager_mobile">
                        <a href="#"><span class="uk-margin-small-right" uk-icon="icon: user"></span>Gerenciar Administradores <span uk-nav-parent-icon></span></a>
                        <ul class="uk-nav-sub">
                            <li><a href="{{ env('APP_URL') }}dashboard/administradores">Listar Administradores</a></li>
                            <li><a href="{{ env('APP_URL') }}dashboard/adicionar-administrador">Adicionar Administrador</a></li>

                        </ul>
                    </li>
                <?php } ?>
                <?php if ($user_manager > 0) { ?>
                    <li class="uk-parent" id="user_manager_mobile">
                        <a href="#"><span class="uk-margin-small-right" uk-icon="icon: users"></span>Gerenciar Usuários <span uk-nav-parent-icon></span></a>
                        <ul class="uk-nav-sub">
                            <li><a href="{{ env('APP_URL') }}dashboard/usuarios">Listar Usuários</a></li>
                            <li><a href="{{ env('APP_URL') }}dashboard/adicionar-usuario">Adicionar Usuário</a></li>
                        </ul>
                    </li>
                <?php } ?>

                <?php 
                // Count functions
                $functions_router = DB::table('functions')->where('type', '=','customize_admin')->get();
                $functions_router_total = $functions_router->count();

                if ($functions_router_total > 0) {
                    echo '<li class="uk-nav-header">Funções</li>';
                    foreach ($functions_router as $functions_router_row) {
                        echo '<li class="uk-parent" id="'.$functions_router_row->function.'_manager_mobile">
                        <a href="#"><span class="uk-margin-small-right" uk-icon="icon: bookmark"></span>'.$functions_router_row->name.'<span uk-nav-parent-icon></span></a>
                        <ul class="uk-nav-sub">';
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
                        echo '</ul></li>';
                    }
                }
                ?>

                <?php if ($source_manager > 0) { ?>
                        <li class="uk-nav-header">Source</li>
                        <li class="uk-parent" id="source_manager_mobile">
                            <a href="#"><span class="uk-margin-small-right" uk-icon="icon: cog"></span>Gerenciar Funções <span uk-nav-parent-icon></span></a>
                            <ul class="uk-nav-sub">
                                <li><a href="{{ env('APP_URL') }}dashboard/funcoes">Minhas Funções</a></li>
                                <li><a href="{{ env('APP_URL') }}dashboard/source">Adicionar Funções</a></li>
                            </ul>
                        </li>
                    <?php } ?>

                <?php if ($filler_manager > 0) { ?>
                    <li class="uk-nav-header">Uploads</li>
                    <li id="filler_manager_mobile">
                        <a href="{{ env('APP_URL') }}dashboard/gerenciador-de-arquivos"><span class="uk-margin-small-right" uk-icon="icon: folder"></span>Gerenciador de Arquivos</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</body>

<script>
    //var sticky base config
    var sticky = UIkit.sticky('.sticky', { offset: 100, bottom: 0 });
    var function_name = '@yield('function_name')';

    // active pc
    $("#"+function_name+"").toggleClass("uk-open");
    $("#"+function_name+" > .uk-nav-sub").removeAttr('hidden');

    // active mobile
    $("#"+function_name+"_mobile").toggleClass("uk-open");
    $("#"+function_name+"_mobile > .uk-nav-sub").removeAttr('hidden');

    //set interval loading jquery
    setTimeout(function() {
        $('#wrapper-loading').fadeOut(500, function(){
            $('#wrapper-loading').remove();
        });
    }, 1000);
</script>
</html>
