@extends('layouts.app')

@section('title', 'Minha Conta')

@section('content')
    <div class="uk-container top-manager-draken">
        <div class="uk-card uk-card-default uk-width-1-1@m">
            <div class="uk-card-header">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    <div class="uk-width-auto">
                        <img class="uk-border-circle" width="40" height="40" src="{{ asset('assets/icons/users/149071.png') }}" alt="Avatar">
                    </div>
                    <div class="uk-width-expand">
                        <h3 class="uk-card-title uk-margin-remove-bottom">{{$user->name}}</h3>
                        <p class="uk-text-meta uk-margin-remove-top"><time datetime="2016-04-01T19:00">{{$user->created_at}}</time></p>
                    </div>
                </div>
            </div>
            <div class="uk-card-body">
                <div uk-filter="target: .js-filter">
                    <ul class="uk-subnav uk-subnav-pill">
                        <li class="uk-active" uk-filter-control="[data-color='white']"><a href="#">Detalhes da Conta</a></li>
                        <li uk-filter-control="[data-color='blue']"><a href="#">Alterar Senha</a></li>
                    </ul>
                    <ul class="js-filter uk-child-width-1-1 uk-child-width-1-1@m" uk-grid>
                        <li data-color="white">
                            <form class="uk-grid-small" uk-grid>
                                <div class="uk-width-1-2@s">
                                    <label>Nome</label>
                                    <input class="uk-input" type="text" placeholder="Digite seu Nome" id="name" value="{{$user->name}}">
                                </div>
                                <div class="uk-width-1-2@s">
                                    <label>E-mail</label>
                                    <input class="uk-input" type="text" placeholder="Digite seu E-mail" id="email" value="{{$user->email}}" disabled>
                                </div>
                                <div class="uk-width-1-2@s">
                                    <label>Telefone</label>
                                    <input class="uk-input" type="text" placeholder="Digite Telefone" id="phone" value="{{$user->phone}}">
                                </div>
                                <?php
                                    if ($user->cpf == NULL) {
                                        $validation_cpf_disabled = "";
                                    } else {
                                        if ($user->cpf == "") {
                                            $validation_cpf_disabled = "";
                                        } else {
                                            $validation_cpf_disabled = "disabled";
                                        }
                                    }
                                ?>
                                <div class="uk-width-1-2@s">
                                    <label>CPF</label>
                                    <input class="uk-input" type="text" placeholder="Digite seu CPF" id="cpf" {{$validation_cpf_disabled}} value="{{$user->cpf}}">
                                </div>
                                <div class="uk-width-1-1@s">
                                    <button id="btn-submit" class="uk-button uk-button-primary uk-button-primary-100-draken">Atualizar</button>
                                </div>
                            </form>
                        </li>
                        <li data-color="blue">
                            <form class="uk-grid-small" uk-grid>
                                <div class="uk-width-1-1@s">
                                    <label>Senha atual</label>
                                    <input class="uk-input" type="password" placeholder="Digite sua senha atual" id="current_password">
                                </div>
                                <div class="uk-width-1-2@s">
                                    <label>Nova Senha</label>
                                    <input class="uk-input" type="password" placeholder="Digite sua nova senha" id="new_password">
                                </div>
                                <div class="uk-width-1-2@s">
                                    <label>Confirmar Nova Senha</label>
                                    <input class="uk-input" type="password" placeholder="Confirme sua nova senha" id="new_password_confirmation">
                                </div>
                                <div class="uk-width-1-1@s">
                                    <button id="btn-submit-pass" class="uk-button uk-button-primary uk-button-primary-100-draken">Atualizar Senha</button>
                                </div>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="uk-card-footer">
                <a href="sair" class="uk-button uk-button-text">Sair</a>
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

            $.ajax({
                dataType: 'json',
                ContentType: 'application/json',
                type: 'POST',
                url: "{{ route('updaterUserPost.post') }}",
                data: {
                    name: name,
                    phone: phone,
                    cpf: cpf
                },
                success: function (data) {
                    if(data.errors == undefined) {
                        if(data.status == "true") {
                            UIkit.notification(data.log, 'success');cpf
                            $("#cpf").attr('disabled','disabled');
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

        $("#btn-submit-pass").click(function(e){
            // preventDefault
            e.preventDefault();

            // values inputs
            var current_password = $("#current_password").val();
            var new_password = $("#new_password").val();
            var new_password_confirmation = $("#new_password_confirmation").val();

            $.ajax({
                dataType: 'json',
                ContentType: 'application/json',
                type: 'POST',
                url: "{{ route('updaterPassUserPost.post') }}",
                data: {
                    current_password: current_password,
                    new_password: new_password,
                    new_password_confirmation: new_password_confirmation
                },
                success: function (data) {
                    if(data.errors == undefined) {
                        if(data.status == "true") {
                            UIkit.notification(data.log, 'success');
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
