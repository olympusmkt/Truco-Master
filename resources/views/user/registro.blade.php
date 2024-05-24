@extends('layouts.app')

@section('title', 'Registro')

@section('content')

    <div class="uk-section uk-flex uk-flex-middle uk-animation-fade"uk-height-viewport style="align-content: space-around; flex-wrap: wrap; flex-direction: column;">
        <div class="uk-width-1-1">
            <div class="uk-container">
                <div class="uk-grid-margin uk-grid uk-grid-stack uk-login-body" uk-grid>
                    <div class="uk-width-1-1@m">
                        <div class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
                            <h3 class="uk-card-title draken-title-form-f uk-text-center">Realize seu Registro</h3>
                            <form>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: mail"></span>
                                        <input class="uk-input uk-form-large" type="text" id="mail" placeholder="E-mail">
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: user"></span>
                                        <input class="uk-input uk-form-large" type="text" id="name" placeholder="Nome">
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                        <input class="uk-input uk-form-large" type="password" id="password" placeholder="Senha">
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                        <input class="uk-input uk-form-large" type="password" id="confirm_password" placeholder="Confirmar Senha">
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <button type="submit" id="btn-submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1">Realizar Registro</button>
                                </div>
                                <div class="uk-text-small uk-text-center">
                                    Já tem uma conta? <a href="login">Realize seu login!</a>
                                </div>
                            </form>
                        </div>
                    </div>
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
                url: "{{ route('RegisterPublicUserPost.post') }}",
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
