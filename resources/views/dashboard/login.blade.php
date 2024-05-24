@extends('layouts.appswhitedashboard')

@section('title', 'Login')

@section('content')
    <div class="uk-section uk-flex uk-flex-middle uk-animation-fade" uk-height-viewport>
        <div class="uk-width-1-1">
            <div class="uk-container">
                <div class="uk-grid-margin uk-grid uk-grid-stack uk-login-body" uk-grid>
                    <div class="uk-width-1-1@m">
                        <div class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
                            <div style="text-align: center">
                                <img class="img-login-logo" src="{{ asset('assets/icons/dragon-1.png') }}">
                            </div>
                            <h3 class="uk-card-title uk-text-center draken-title-form-f">Realize seu Login</h3>
                            <form>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: mail"></span>
                                        <input class="uk-input uk-form-large" type="text" id="mail"
                                               placeholder="E-mail">
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                        <input class="uk-input uk-form-large" type="password" id="password"
                                               placeholder="Senha">
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <button type="button" id="btn-submit"
                                            class="uk-button uk-button-primary uk-button-large uk-width-1-1">Realizar
                                        Login
                                        <svg style="width: 1.5em; margin-left: 0.5em;" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                               stroke-linejoin="round"></g>
                                            <g id="SVGRepo_iconCarrier">
                                                <path d="M18 20.75H15C14.8011 20.75 14.6103 20.671 14.4697 20.5303C14.329 20.3897 14.25 20.1989 14.25 20C14.25 19.8011 14.329 19.6103 14.4697 19.4696C14.6103 19.329 14.8011 19.25 15 19.25H18C18.2969 19.2758 18.5924 19.1863 18.8251 19.0001C19.0579 18.814 19.21 18.5453 19.25 18.25V5.77997C19.21 5.48462 19.0579 5.21599 18.8251 5.0298C18.5924 4.84362 18.2969 4.75415 18 4.77997H15C14.8011 4.77997 14.6103 4.70096 14.4697 4.5603C14.329 4.41965 14.25 4.22889 14.25 4.02997C14.25 3.83106 14.329 3.6403 14.4697 3.49964C14.6103 3.35899 14.8011 3.27997 15 3.27997H18C18.3468 3.26522 18.693 3.31899 19.019 3.43821C19.3449 3.55742 19.6442 3.73974 19.8996 3.97473C20.155 4.20972 20.3616 4.49277 20.5075 4.80768C20.6535 5.12259 20.7359 5.46319 20.75 5.80997V18.22C20.7359 18.5668 20.6535 18.9074 20.5075 19.2223C20.3616 19.5372 20.155 19.8202 19.8996 20.0552C19.6442 20.2902 19.3449 20.4725 19.019 20.5917C18.693 20.711 18.3468 20.7647 18 20.75Z"
                                                      fill="#fff"></path>
                                                <path d="M11 16.75C10.9015 16.7504 10.8038 16.7312 10.7128 16.6934C10.6218 16.6557 10.5393 16.6001 10.47 16.53C10.3296 16.3893 10.2507 16.1987 10.2507 16C10.2507 15.8012 10.3296 15.6106 10.47 15.47L13.94 12L10.47 8.52997C10.3375 8.38779 10.2654 8.19975 10.2688 8.00545C10.2723 7.81115 10.351 7.62576 10.4884 7.48835C10.6258 7.35093 10.8112 7.27222 11.0055 7.26879C11.1998 7.26537 11.3878 7.33749 11.53 7.46997L15.53 11.47C15.6705 11.6106 15.7494 11.8012 15.7494 12C15.7494 12.1987 15.6705 12.3893 15.53 12.53L11.53 16.53C11.4608 16.6001 11.3782 16.6557 11.2872 16.6934C11.1962 16.7312 11.0986 16.7504 11 16.75Z"
                                                      fill="#fff"></path>
                                                <path d="M15 12.75H4C3.80109 12.75 3.61032 12.671 3.46967 12.5303C3.32902 12.3897 3.25 12.1989 3.25 12C3.25 11.8011 3.32902 11.6103 3.46967 11.4697C3.61032 11.329 3.80109 11.25 4 11.25H15C15.1989 11.25 15.3897 11.329 15.5303 11.4697C15.671 11.6103 15.75 11.8011 15.75 12C15.75 12.1989 15.671 12.3897 15.5303 12.5303C15.3897 12.671 15.1989 12.75 15 12.75Z"
                                                      fill="#fff"></path>
                                            </g>
                                        </svg>
                                    </button>
                                </div>
                                <!-- <div class="uk-text-small uk-text-center">
                                    Not registered? <a href="#">Create an account</a>
                                </div> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $("#btn-submit").click(function (e) {
            // preventDefault
            e.preventDefault();

            // values inputs
            var email = $("#mail").val();
            var password = $("#password").val();

            $.ajax({
                dataType: 'json',
                ContentType: 'application/json',
                type: 'POST',
                url: "{{ route('LoginUserPost.post') }}",
                data: {password: password, email: email},
                success: function (data) {
                    if (data.errors == undefined) {
                        if (data.status == "true") {
                            // Interval redirect
                            UIkit.notification(data.log, 'success');
                            setTimeout(function () {
                                window.location = "" + data.redirect + "";
                            }, 1000);
                        } else {
                            UIkit.notification(data.log, 'danger');
                        }
                    } else {
                        $.each(data.errors, function (index, value) {
                            UIkit.notification(value, 'danger');
                        });
                    }
                }
            });
        });
    </script>
@endsection
