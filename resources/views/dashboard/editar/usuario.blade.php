@extends('layouts.apps')

@section('title', 'Detalhes do Usuário')
@section('function_name', 'user_manager')

@section('content')
    <div class="">
        <div>
            <h1 style="margin-top: 0em; margin-bottom: 0em;" class="title-dash-admin-draken">Detalhes do Usuário: <strong>{{$user_details[0]->name}}</strong></h1>
            <p style="margin-top: 0em;">Gostaria de editar os detalhes desse usuário?</p>
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
                        <li uk-filter-control="[data-color='blue']"><a href="#">Wallets</a></li>
                    </ul>
                    <ul class="js-filter uk-child-width-1-1 uk-child-width-1-1@m" uk-grid>
                        <li data-color="white">
                            <hr>
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
                                    <label>Tipo de Usuário</label>
                                    <select class="uk-select" id="user_type">
                                        <option value="simple_user" <?php if($user_details[0]->type == "simple_user") { echo "selected"; }  ?>>Usuário Simples</option>
                                        <option value="customize_admin" <?php if($user_details[0]->type == "customize_admin") { echo "selected"; }  ?>>Usuário Personalizado</option>
                                    </select>
                                </div>
                                <div class="uk-width-1-1@s">
                                    <button type="button" onclick="function_get_updater();" class="uk-button uk-button-primary uk-button-primary-100-draken">Atualizar Usuário</button>
                                </div>
                            </form>
                        </li>
                        <li data-color="blue">
                            <hr>
                            <form class="uk-grid-small" uk-grid>
                                <?php 
                                $row_1_del = $user_details[0]->saldo / 100;
                                $row_1_wallet = number_format($row_1_del, 2, ',', '.');

                                $row_2_del = $user_details[0]->afiliado / 100;
                                $row_2_wallet = number_format($row_2_del, 2, ',', '.');

                                $row_3_del = $user_details[0]->demo / 100;
                                $row_3_wallet = number_format($row_3_del, 2, ',', '.');

                                $row_4_del = $user_details[0]->bonus / 100;
                                $row_4_wallet = number_format($row_4_del, 2, ',', '.');
                                ?>
                                <div class="uk-width-1-4@s">
                                    <label>Saldo</label>
                                    <input onKeyPress="return(moeda(this,'.',',',event))" class="uk-input" type="text" placeholder="Saldo" id="saldo" value="<?php echo $row_1_wallet; ?>">
                                </div>
                                <div class="uk-width-1-4@s">
                                    <label>Bônus</label>
                                    <input onKeyPress="return(moeda(this,'.',',',event))" class="uk-input" type="text" placeholder="Bônus" id="bonus" value="<?php echo $row_4_wallet; ?>">
                                </div>
                                <div class="uk-width-1-4@s">
                                    <label>Afiliado</label>
                                    <input onKeyPress="return(moeda(this,'.',',',event))" class="uk-input" type="text" placeholder="Afiliado" id="afiliado" value="<?php echo $row_2_wallet; ?>">
                                </div>
                                <div class="uk-width-1-4@s">
                                    <label>Demo</label>
                                    <input onKeyPress="return(moeda(this,'.',',',event))" class="uk-input" type="text" placeholder="Demo" id="demo" value="<?php echo $row_3_wallet; ?>">
                                </div>
                                <div class="uk-width-1-1@s">
                                    <button type="button" onclick="function_get_updater()" id="btn-submit" class="uk-button uk-button-primary uk-button-primary-100-draken">Atualizar Wallets</button>
                                </div>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        function moeda(a, e, r, t) {
            let n = ""
              , h = j = 0
              , u = tamanho2 = 0
              , l = ajd2 = ""
              , o = window.Event ? t.which : t.keyCode;
            if (13 == o || 8 == o)
                return !0;
            if (n = String.fromCharCode(o),
            -1 == "0123456789".indexOf(n))
                return !1;
            for (u = a.value.length,
            h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
                ;
            for (l = ""; h < u; h++)
                -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
            if (l += n,
            0 == (u = l.length) && (a.value = ""),
            1 == u && (a.value = "0" + r + "0" + l),
            2 == u && (a.value = "0" + r + l),
            u > 2) {
                for (ajd2 = "",
                j = 0,
                h = u - 3; h >= 0; h--)
                    3 == j && (ajd2 += e,
                    j = 0),
                    ajd2 += l.charAt(h),
                    j++;
                for (a.value = "",
                tamanho2 = ajd2.length,
                h = tamanho2 - 1; h >= 0; h--)
                    a.value += ajd2.charAt(h);
                a.value += r + l.substr(u - 2, u)
            }
            return !1
        }

        $(document).ready(function () {
            var seuCampoCpf = $("#cpf");
            seuCampoCpf.mask('000.000.000-00', {reverse: true});

            var seuCampoPhone = $("#phone");
            seuCampoPhone.mask('00 00000-0000', {reverse: true});
        });

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        function function_get_updater() {

            // values inputs
            var name = $("#name").val();
            var phone = $("#phone").val();
            var cpf = $("#cpf").val();
            var id_user = "{{$user_details[0]->id}}";
            var user_type = $("#user_type").val();
            var expirar = $("#expirar").val();

            var saldo = $("#saldo").val();
            var afiliado = $("#afiliado").val();
            var demo = $("#demo").val();
            var bonus = $("#bonus").val();

            $.ajax({
                dataType: 'json',
                ContentType: 'application/json',
                type: 'POST',
                url: "{{ route('updaterUserPostListPublic.post') }}",
                data: {
                    name: name,
                    phone: phone,
                    cpf: cpf,
                    user_type: user_type,
                    id_user: id_user,
                    saldo: saldo, 
                    afiliado: afiliado,
                    demo: demo,
                    bonus: bonus
                },
                success: function (data) {

                    if(data.errors == undefined) {
                        if(data.status == "true") {
                            UIkit.notification(data.log, 'success');

                            // Url redirect change type
                            if(user_type == "customize_admin") {
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
        }
    </script>
@endsection
