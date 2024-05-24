@extends('layouts.app')

@section('title', 'Home')

@section('content')
<style type="text/css">
	html, body {
		/*background-image: url("https://i.pinimg.com/originals/c1/25/42/c125428ccdb6c1e9976eb677f4358d93.jpg");*/
		background-repeat: no-repeat; background-size: cover; background-attachment: fixed;
		background: linear-gradient(-45deg, rgba(0 0 0 / 90%), rgba(26 192 109 / 73%)) fixed, url(https://i.pinimg.com/originals/c1/25/42/c125428ccdb6c1e9976eb677f4358d93.jpg) fixed;
        background-size: cover;
	}
</style>
<div class="uk-section uk-flex uk-flex-middle uk-animation-fade"uk-height-viewport style="align-content: space-around; flex-wrap: wrap; flex-direction: column;">
    <div class="uk-width-1-1">
        <div class="uk-container">
            <div class="uk-grid-margin uk-grid uk-grid-stack uk-login-body" uk-grid>
                <div class="uk-width-1-1@m">
                    <div class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
                        <h3 class="uk-card-title draken-title-form-f uk-text-center">Iniciar Partida</h3>
                        <form>
                            <div class="uk-margin">
                                <div class="uk-inline uk-width-1-1">
                                    <label>Tipo de Truco</label>
                                    <select class="uk-select" id="type">
                                        <option>Paulistinha</option>
                                        <option>Mineririnho</option>
                                    </select>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <div class="uk-inline uk-width-1-1">
                                    <label>Tipo de Partida</label>
                                    <select class="uk-select" id="players">
                                        <option value="2">Solo</option>
                                        <option value="4">Dupla</option>
                                    </select>
                                </div>
                            </div>
                            <div style="display: none;">
                                <label>Valor</label>
                                <input disabled class="uk-input" type="text" placeholder="Valor da Aposta" id="values" value="5,00">
                            </div>
                            <?php 
        //                     function item_formated($valor) {
        //                     	$valor = $valor;
								// $porcentagem = env('APP_PORCENT');
								// $somado = $valor + ($valor / 100 * $porcentagem);
								// $subtraido = $valor - ($valor / 100 * $porcentagem);
								// var_dump($subtraido); // 200 - 30% = 140
        //                     }
        //                     item_formated('500');
                            ?>
                            <div class="iten-buttons">
                            	<button id="value_5" onclick="item_value('5,00', 'value_5')" type="button" class="uk-button uk-button-primary selected_button">Apostas de R$ 5,00 <img src="{{ asset('assets/icons/truco/321321.webp') }}"></button>
                            	<button id="value_10" onclick="item_value('10,00', 'value_10')" type="button" class="uk-button uk-button-primary">Apostas de R$ 10,00  <img src="{{ asset('assets/icons/truco/321321.webp') }}"></button>
                            	<button id="value_25" onclick="item_value('25,00', 'value_25')" type="button" class="uk-button uk-button-primary">Apostas de R$ 25,00  <img src="{{ asset('assets/icons/truco/321321.webp') }}"></button>
                            	<button id="value_50" onclick="item_value('50,00', 'value_50')" type="button" class="uk-button uk-button-primary">Apostas de R$ 50,00  <img src="{{ asset('assets/icons/truco/321321.webp') }}"></button>
                            </div>
                            <script type="text/javascript">
                            	function item_value(vars, item) {
                            		if (item == "value_5") {
                            			$('#value_5').addClass('selected_button');
                            			$('#value_10').removeClass('selected_button');
                            			$('#value_25').removeClass('selected_button');
                            			$('#value_50').removeClass('selected_button');
                            		} else if (item == "value_10") {
                            			$('#value_5').removeClass('selected_button');
                            			$('#value_10').addClass('selected_button');
                            			$('#value_25').removeClass('selected_button');
                            			$('#value_50').removeClass('selected_button');
                            		} else if (item == "value_25") {
                            			$('#value_5').removeClass('selected_button');
                            			$('#value_10').removeClass('selected_button');
                            			$('#value_25').addClass('selected_button');
                            			$('#value_50').removeClass('selected_button');
                            		} else if (item == "value_50") {
                            			$('#value_5').removeClass('selected_button');
                            			$('#value_10').removeClass('selected_button');
                            			$('#value_25').removeClass('selected_button');
                            			$('#value_50').addClass('selected_button');
                            		}
                            		$("#values").val(vars);
                            	}
                            </script>
                            <style type="text/css">
                            	.iten-buttons button {
                            		width: 100%;
                            		margin-bottom: 1em;
                            		cursor: pointer;
                            		opacity: 0.4;
                            	}

                            	.iten-buttons button:hover {
                            		opacity: 1!important;
                            		transition: 1s;
                            	}

                            	.selected_button {
                            		opacity: 1!important;
                            	}

                            	.iten-buttons {
                            		margin-top: 2em;
                            	}

                            	.iten-buttons img {
                            		width: 5em;
                            	}
                            </style>
                            <?php if (auth()->check()) { ?>
	                            <div class="uk-margin">
	                                <button type="submit" id="btn-submit-game" class="uk-button uk-button-primary uk-button-large uk-width-1-1">Iniciar Partida <span style="margin-left: 0.5em;" uk-icon="play-circle"></span></button>
	                            </div>
	                        <?php } else { ?>
	                        	<div class="uk-margin">
	                                <button onclick="location.href='user/login'" type="button" class="uk-button uk-button-primary uk-button-large uk-width-1-1">Realizar Login</button>
	                            </div>
	                        <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	$.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

	$("#btn-submit-game").click(function(e){
        // PreventDefault
        e.preventDefault();

        // values inputs
        var type = $("#type").val();
        var players = $("#players").val();
        var values = $("#values").val();

        // prrevent item json
        $.ajax({
            dataType: 'json',
            ContentType: 'application/json',
            type: 'POST',
            url: "{{ route('Add_Request_Truco_Room.post') }}",
            data: { type: type, players: players, values: values },
            success: function (data) {

                if(data.errors == undefined) {
                    if(data.status == "true") {
                        // Interval redirect
                        UIkit.notification(data.log, 'success');
                        setTimeout(function() {
                            window.location= "{{ env('APP_URL') }}"+data.redirect+"";
                        }, 500);
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
