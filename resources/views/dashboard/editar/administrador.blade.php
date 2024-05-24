@extends('layouts.apps')

@section('title', 'Detalhes do Usuário')
@section('function_name', 'admin_manager')

@section('content')
    <div class="">
        <div>
            <h1 style="margin-top: 0em; margin-bottom: 0em;" class="title-dash-admin-draken">Detalhes do Administrador: <strong>{{$user_details[0]->name}}</strong></h1>
            <p style="margin-top: 0em;">Gostaria de editar os detalhes desse administrador?</p>
        </div>
        <hr>
        <div class="uk-card uk-card-default uk-width-1-1@m">
            <div class="uk-card-header">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    <div class="uk-width-auto">
                        <img class="uk-border-circle" width="40" height="40" src="{{ asset('assets/icons/users/149071.png') }}" alt="Avatar">
                    </div>
                    <div class="uk-width-expand">
                        <h3 class="uk-card-title uk-margin-remove-bottom">{{$user_details[0]->name}}</h3>
                        <p class="uk-text-meta uk-margin-remove-top"><time datetime="2016-04-01T19:00">{{$user_details[0]->created_at}}</time></p>
                    </div>
                </div>
            </div>
            <div class="uk-card-body">
                <div uk-filter="target: .js-filter">
                    <ul class="uk-subnav uk-subnav-pill">
                        <li class="uk-active" uk-filter-control="[data-color='white']"><a href="#">Detalhes da Conta</a></li>
                        <li uk-filter-control="[data-color='blues']"><a href="#">Permissões</a></li>
                        <li uk-filter-control="[data-color='blue']"><a href="#">Wallets</a></li>
                    </ul>
                    <ul class="js-filter uk-child-width-1-1 uk-child-width-1-1@m" uk-grid>
                        <li data-color="white">
                            <form class="uk-grid-small" uk-grid>
                                <div class="uk-width-1-2@s">
                                    <label>Nome</label>
                                    <input class="uk-input" type="text" placeholder="Digite seu Nome" id="name" value="{{$user_details[0]->name}}">
                                </div>
                                <div class="uk-width-1-2@s">
                                    <label>E-mail</label>
                                    <input class="uk-input" type="text" placeholder="Digite seu E-mail" id="email" value="{{$user_details[0]->email}}" disabled>
                                </div>
                                <div class="uk-width-1-2@s">
                                    <label>Telefone</label>
                                    <input class="uk-input" type="text" placeholder="Digite Telefone" id="phone" value="{{$user_details[0]->phone}}">
                                </div>
                                <div class="uk-width-1-2@s">
                                    <label>CPF</label>
                                    <input class="uk-input" type="text" placeholder="Digite seu CPF" id="cpf" value="{{$user_details[0]->cpf}}">
                                </div>
                                <div class="uk-width-1-1@s">
                                    <button id="btn-submit" class="uk-button uk-button-primary uk-button-primary-100-draken">Atualizar Administrador</button>
                                </div>
                            </form>
                        </li>
                        <li data-color="blue">
                            <form class="uk-grid-small" uk-grid>
                            </form>
                        </li>
                        <li data-color="blues">
                            <form class="uk-grid-small" uk-grid>
                                <div class="uk-width-1-1@s">
                                    <label>Tipo de Usuário</label>
                                    <select class="uk-select" id="user_type">
                                        <option value="simple_user" <?php if($user_details[0]->type == "simple_user") { echo "selected"; }  ?>>Usuário Simples</option>
                                        <option value="customize_admin" <?php if($user_details[0]->type == "customize_admin") { echo "selected"; }  ?>>Usuário Personalizado</option>
                                    </select>
                                </div>
                                <div class="uk-width-1-1@s">
                                    <label>Roles (Permissões)</label>
                                    <?php $roles_user = json_decode($user_details[0]->roules); ?>
                                    <input class="uk-input" type="text" placeholder="Digite as Permissões" id="roules" value="{{ $roles_user->roles->acess; }}">
                                </div>
                                <div class="uk-width-1-1@s">
                                    <div class="uk-card uk-card-default uk-card-body">
                                        <h3 class="uk-card-title" style="text-transform: uppercase; font-size: 1em;">Funções para Roles (Permissões):</h3>
                                        <hr>
                                        <p style="font-width: 500;">{{ env('APP_FUNCTIONS') }}</p>
                                    </div>
                                </div>
                                <div class="uk-width-1-1@s">
                                    <button id="btn-submit-1" class="uk-button uk-button-primary uk-button-primary-100-draken">Atualizar Administrador</button>
                                </div>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var seuCampoCpf = $("#cpf");
            seuCampoCpf.mask('000.000.000-00', {reverse: true});

            var seuCampoPhone = $("#phone");
            seuCampoPhone.mask('00 00000-0000', {reverse: true});
        });

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $("#btn-submit").click(function(e){
            // preventDefault
            e.preventDefault();

            // values inputs
            var name = $("#name").val();
            var phone = $("#phone").val();
            var cpf = $("#cpf").val();
            var user_type = $("#user_type option:selected").val();
            var roules = $("#roules").val();
            var id_user = "{{$user_details[0]->id}}";

            $.ajax({
                dataType: 'json',
                ContentType: 'application/json',
                type: 'POST',
                url: "{{ route('updaterUserPostList.post') }}",
                data: {
                    name: name,
                    phone: phone,
                    cpf: cpf,
                    id_user: id_user,
                    user_type: user_type,
                    roules: roules
                },
                success: function (data) {
                    if(data.errors == undefined) {
                        if(data.status == "true") {
                            UIkit.notification(data.log, 'success');

                            // Url redirect change type
                            if(user_type == "simple_user") {
                                setTimeout(function () {
                                    url_native_js = "{{ env('APP_URL') }}";
                                    window.location = "" + url_native_js + "dashboard/" + data.redirect + "";
                                }, 1000);
                            }

                        } else {
                            UIkit.notification(data.log, 'danger');
                        }
                    } else {
                        $.each(data.errors, function(index, value) {
                            UIkit.notification(value, 'danger');
                        });
                    }
                }
            });
        });

        $("#btn-submit-1").click(function(e){
            // preventDefault
            e.preventDefault();

            // values inputs
            var name = $("#name").val();
            var phone = $("#phone").val();
            var cpf = $("#cpf").val();
            var user_type = $("#user_type option:selected").val();
            var roules = $("#roules").val();

            var id_user = "{{$user_details[0]->id}}";

            $.ajax({
                dataType: 'json',
                ContentType: 'application/json',
                type: 'POST',
                url: "{{ route('updaterUserPostList.post') }}",
                data: {
                    name: name,
                    phone: phone,
                    cpf: cpf,
                    id_user: id_user,
                    user_type: user_type,
                    roules: roules
                },
                success: function (data) {
                    if(data.errors == undefined) {
                        if(data.status == "true") {
                            UIkit.notification(data.log, 'success');

                            // Url redirect change type
                            if(user_type == "simple_user") {
                                setTimeout(function () {
                                    url_native_js = "{{ env('APP_URL') }}";
                                    window.location = "" + url_native_js + "dashboard/" + data.redirect + "";
                                }, 1000);
                            }

                        } else {
                            UIkit.notification(data.log, 'danger');
                        }
                    } else {
                        $.each(data.errors, function(index, value) {
                            UIkit.notification(value, 'danger');
                        });
                    }
                }
            });
        });
    </script>
@endsection
