@extends('layouts.apps')

@section('title', 'Adicionar Usuário')
@section('function_name', 'user_manager')

@section('content')
    <div>
        <div>
            <h1 style="margin-top: 0em; margin-bottom: 0em;" class="title-dash-admin-draken">Adicionar Usuário</h1>
            <p style="margin-top: 0em;">Gostaria de adicionar algum usuário?</p>
        </div>
        <hr>
        <div>
            <div>
                <div class="uk-card uk-card-default uk-card-body">
                    <form>
                        <div class="uk-margin">
                            <div class="uk-inline uk-width-1-1">
                                <span class="uk-form-icon" uk-icon="icon: mail"></span>
                                <input class="uk-input" type="text" id="mail" placeholder="E-mail">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <div class="uk-inline uk-width-1-1">
                                <span class="uk-form-icon" uk-icon="icon: user"></span>
                                <input class="uk-input" type="text" id="name" placeholder="Nome">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <div class="uk-inline uk-width-1-1">
                                <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                <input class="uk-input" type="password" id="password" placeholder="Senha">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <div class="uk-inline uk-width-1-1">
                                <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                <input class="uk-input" type="password" id="confirm_password" placeholder="Confirmar Senha">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <button type="submit" id="btn-submit" class="uk-button uk-button-primary uk-width-1-1">Adicionar Usuário</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $("#btn-submit").click(function(e){
            // preventDefault
            e.preventDefault();

            // values inputs
            var name = $("#name").val();
            var password = $("#password").val();
            var confirm_password = $("#confirm_password").val();
            var email = $("#mail").val();

            $.ajax({
                dataType: 'json',
                ContentType: 'application/json',
                type: 'POST',
                url: "{{ route('RegisterUserPostDashboard.post') }}",
                data: {name: name, password: password, confirm_password: confirm_password, email: email},
                success: function (data) {
                    if(data.errors == undefined) {
                        if(data.status == "true") {
                            // Interval redirect
                            UIkit.notification(data.log, 'success');
                            setTimeout(function() {
                                window.location= ""+data.redirect+"";
                            }, 1000);
                        } else {
                            UIkit.notification('Aconteceu algum erro, tente novamente mais tarde.', 'danger');
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
