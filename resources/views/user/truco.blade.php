@extends('layouts.app')
@section('title', 'Truco Game Player')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/icons/truco/style.css') }}" />
    <?php require ''.base_path() .'/public/assets/icons/truco/class.php'; ?>
    <link href="https://fonts.googleapis.com/css2?family=Arvo:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

 <!--    <a href="{{ env('APP_URL') }}" style="background-color: #fff; padding: 0.5em 1em; position: fixed; bottom: 1em; text-align: center; left: 1em; cursor: pointer; border-radius: 5px;">
        <span style="text-transform: uppercase; font-size: 0.6em; font-weight: bold; color: #333; margin-top: -1em!important;">Voltar</span>
        <br>
        <img style="width: 2.9em; margin-top: 0.5em;" src="<?php echo asset('assets/icons/truco/truco-1.png'); ?>">
    </a> -->
    <?php 
    $tempo = 10;

    if (Session::has('get_room')) { ?>
        <?php if (Session::has('get_room_confirmed')) { ?>
            <div class="uk-container" id="container-truco">
                <div class="uk-child-width-1-1@m uk-grid-small uk-grid-match" uk-grid>
                    <input type="text" style="display: none;" id="moviments" value="<?php if($select_moviments_total == "") { echo "0"; } else { echo $select_moviments_total; } ?>">
                    <input type="text" style="display: none;" id="moviments_partid" value="1">
                    <input type="text" style="display: none;"  id="input_count_timer" value="false">
                    <div>
                        <div id="count_user_active" style="background-color: #fff; padding: 0.5em 1em; position: fixed; bottom: 1em; text-align: center; left: 1em; cursor: pointer; border-radius: 5px; width: 12em;">
                            <p style="margin-bottom: 0em; margin-top: 0em; font-size: 0.8em;"><span style="font-size: 0.9em;">Esperando Jogada</span><br><span style="font-weight: bold;" id="playrer_gamer" class="uk-badge">Jogador</span></p>
                            <div id="sessao" style="color: #333; font-weight: 400;">00:00</div>
                        </div>
                        <script type="text/javascript">
                            // Temp values
                            var tempo = new Number();
                            var no_click = "0";
                            tempo = "<?php echo $tempo; ?>";

                            function startCountdown(){
                                if((tempo - 1) >= 0){
                                  var min = parseInt(tempo/60);
                                  var seg = tempo%60;

                                  // Format
                                  if(min < 10){
                                    min = "0"+min;
                                    min = min.substr(0, 2);
                                  }
                                  if(seg <=9){
                                    seg = "0"+seg;
                                  }

                                  // values html
                                  horaImprimivel = '' + min + ':' + seg;
                                  $("#sessao").html(horaImprimivel);
                                  setTimeout('startCountdown()',1000);
                                  tempo--;

                                } else {

                                    // click random
                                    if (no_click == "0") { 
                                        var item_counts = 0;
                                        $(".class_player img").each(function(index) { item_counts++;
                                            if (item_counts == "1") {
                                                $("#sessao").html('00:00');
                                                alert("click wow");
                                                // var value_atual = $('#input_count_timer').val();

                                                // if (value_atual == "true") {
                                                //     $(this).click();
                                                //     $('#input_count_timer').val('false');
                                                // }
                                            }
                                        });
                                    }
                                    
                                }
                            }
                        </script>
                        <?php 
                        if ($total_users_online > 0) { ?>
                            @foreach ($select_confirmeds as $select_confirmeds_row)
                                <?php 
                                //selecionar user
                                $select_user_profiler = DB::table("users")->Where('id', '!=', ''.$select_confirmeds_row->players_1.'')->get();

                                // Count users page
                                $count_select_user_profiler = $select_user_profiler->count();

                                if ($count_select_user_profiler > 0) {
                                    foreach ($select_user_profiler as $key_profilers) {
                                        $name_user = $key_profilers->name;
                                    }
                                } else {
                                    $name_user = "Desconhecido";
                                }
                                ?>
                                <div class="player_top class_player_user">
                                    <span style="color: #ffffff8f; text-transform: uppercase;">Jogador</span>
                                    <h2 style="margin-top: 0em;"><span uk-icon="user"></span> Jogador: <strong><?php echo $name_user; ?></strong></h2>
                                    <div id="total_movis_player_1">
                                        <?php get_number('0', $Initial_Card, $Number, $Simbol, 'user', '0', $total_movi); ?>
                                    </div>
                                </div>
                            @endforeach
                        <?php } ?>
                    </div>

                    <div>
                        <div class="item-jogadas">
                            <center>
                                <button style="opacity: 0.2;" onclick="get_start_cancel('<?php echo Session::has('get_room'); ?>');" class="uk-button uk-button-danger"><span uk-icon="close"></span></button>
                            </center>
                            <div class="cards-confirmeds" style="text-align: center;">
                                <?php if ($select_moviments_total > 0) {
                                    foreach ($select_moviments as $key_rows) {
                                        echo "<img src='".asset('assets/icons/truco/cards/'.$key_rows->card.'.png')."'>";
                                    }
                                } ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="player_bottom class_player" style="opacity: 0.5; pointer-events: none;">
                            <span style="color: #ffffff8f; text-transform: uppercase;">Você</span>
                            <h2 style="margin-top: 0em;">
                                <span uk-icon="user"></span> Jogador: <strong>{{$user->name}}</strong>
                            </h2>
                            <?php 
                            if ($total_user_online > 0) {
                                foreach ($select_confirmeds_user as $key_yous) {

                                    if ($key_yous->cards == null) {
                                        get_number('0', $Initial_Card, $Number, $Simbol, 'you', $id, '3'); 
                                    } else {
                                        get_number_fix($key_yous->cards, $id); 
                                    }
                                    
                                }
                            } else {
                                get_number('0', $Initial_Card, $Number, $Simbol, 'you', $id, '3'); 
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="uk-section uk-flex uk-flex-middle uk-animation-fade"uk-height-viewport style="align-content: space-around; flex-wrap: wrap; flex-direction: column;">
                <div class="uk-width-1-1">
                    <div class="uk-container">
                        <div class="uk-grid-margin uk-grid uk-grid-stack uk-login-body" uk-grid>
                            <div class="uk-width-1-1@m">
                                <div class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
                                    <h3 class="uk-card-title draken-title-form-f uk-text-center" style="font-size: 1em!important;">Procurando Jogadores</h3>
                                    <div style="text-align: center; display: inline-flex; width: 100%; justify-content: center; align-items: center; margin-top: 1em; margin-bottom: 1em;" class="item-prelo-game">
                                        <div class="main-circle">
                                            <div class="green-circle">
                                                <div class="brown-circle"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <center>
                                            <span id="timer" style="color: #333; text-transform: uppercase; font-weight: 500;"></span>
                                        </center>
                                    </div>
                                    <div style="margin-top: 1em;">
                                        <center>
                                            <button onclick="get_start_cancel('<?php echo Session::has('get_room'); ?>');" class="uk-button uk-button-danger">Cancelar Partida <span style="margin-left: 0.5em;" uk-icon="close"></span></button>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo Session::has('get_room_time'); ?>
            <script type="text/javascript">
                setInterval(function() {
                    get_start('<?php echo Session::has('get_room'); ?>');
                }, 1000);

                setInterval(function() {
                    get_start_synchronize(<?php echo Session::has('get_room'); ?>);
                }, 6000);
            </script>
        <?php } ?>
    <?php } else { 
        echo '<script>window.location="'.env('APP_URL').'";</script>';
        ?>
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
                                    <div>
                                        <label>Valor</label>
                                        <input class="uk-input" type="text" placeholder="Valor da Aposta" id="values" value="20,00">
                                    </div>
                                    <div class="uk-margin">
                                        <button type="submit" id="btn-submit-game" class="uk-button uk-button-primary uk-button-large uk-width-1-1">Iniciar <span style="margin-left: 0.5em;" uk-icon="play-circle"></span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>


    <div style="display: none;">
        <audio controls id="player_1">
            <source src="<?php echo asset('assets/icons/truco/blacjjack.mp3'); ?>" type="audio/mpeg" autoplay>
            Your browser does not support the audio element.
        </audio>
        <button id="player_1_button">SOUND THEME PLAYER 1</button>
    </div>
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        //Players game
        var audio_card = new Audio("<?php echo asset('assets/icons/truco/flipcard-91468.mp3'); ?>");

        function add_moviment(event, item) {

            // disabled user
            $('.class_player').css('pointer-events', 'none');
            $('.class_player').css('opacity', '0.5');

            // isso para user que vai jogar atualmente:
            // valido se o input dele ta em false. se tiver mudo para -> true
            $('#input_count_timer').val('false');
            tempo = "0";
            $("#sessao").html('00:00');

            audio_card.play();
            $('#'+item+'').fadeOut("normal", function() {

                //moviments id
                id_item_show = $('#moviments').val();
                moviments_partid = $('#moviments_partid').val();

                //remove cards
                $('#'+item+'').remove();
                $('.cards-confirmeds').append('<img src="<?php echo env('APP_URL'); ?>assets/icons/truco/cards/'+event+'.png">');

                // prrevent item json
                $.ajax({
                    dataType: 'json',
                    ContentType: 'application/json',
                    type: 'POST',
                    url: "{{ route('Add_Request_Room_Moviment_User.post') }}",
                    data: { card: event, id_item: '<?php echo Session('get_room_confirmed'); ?>', moviments: id_item_show, moviments_partid: moviments_partid },
                    success: function (data) {

                        if(data.errors == undefined) {
                            if(data.status == "true") {

                                setTimeout(function () {
                                    $('#moviments').val(""+data.value+"");
                                }, 1500);
                                
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
        }

        <?php if (Session::has('get_room_confirmed')) { ?>
            setInterval(function () {
                id_item_show = $('#moviments').val();
                moviments_partid = $('#moviments_partid').val();

                $.ajax({
                    dataType: 'json',
                    ContentType: 'application/json',
                    type: 'POST',
                    url: "{{ route('Get_Moviments_Updater.post') }}",
                    data: { id_item_show: id_item_show, code: <?php echo Session('get_room_confirmed'); ?>, moviments_partid: moviments_partid }, 
                    success: function (data) {
                        console.log(data);
                        if(data.errors == undefined) {
                            if(data.status == "true") {
                                if (data.log == "null") {
                                    moviments_partid = $('#moviments_partid').val(data.total_movit_atual);

                                    if(data.logged == data.user_card_player) {
                                        //caso seja do user logado
                                        $('.class_player').css('pointer-events', 'auto');
                                        $('.class_player').css('opacity', '1');

                                        // isso para user que vai jogar atualmente:
                                        // valido se o input dele ta em false. se tiver mudo para -> true
                                        var get_value = $('#input_count_timer').val();
                                        if (get_value == "false") {
                                            $('#input_count_timer').val('true');
                                            tempo = "<?php echo $tempo; ?>";
                                            startCountdown();
                                        }
                                        $('#playrer_gamer').html(data.user_card_player_name);
                                        
                                    } else {
                                        // para outros usuarios
                                        $('.class_player').css('pointer-events', 'none');
                                        $('.class_player').css('opacity', '0.5');
                                        
                                        // isso para user que vai jogar atualmente:
                                        // valido se o input dele ta em false. se tiver mudo para -> true
                                        var get_value = $('#input_count_timer').val();
                                        if (get_value == "false") {
                                            $('#input_count_timer').val('true');
                                            tempo = "<?php echo $tempo; ?>";
                                            startCountdown();
                                        }
                                        $('#playrer_gamer').html(data.user_card_player_name);

                                    }

                                } else {
                                    if (data.player == data.logged) {

                                    } else {
                                        total_mos = 0;
                                        $("#total_movis_player_1 img").each(function(){ total_mos++;
                                            if (total_mos == 1) {
                                                $(this).remove();
                                            }
                                        });
                                        $('.cards-confirmeds').append('<img src="<?php echo env('APP_URL'); ?>assets/icons/truco/cards/'+data.card+'">');
                                        audio_card.play();

                                        // isso para user que vai jogar atualmente:
                                        // valido se o input dele ta em false. se tiver mudo para -> true
                                        $('#input_count_timer').val('false');
                                        tempo = "0";
                                        $("#sessao").html('00:00');
                                       
                                    }

                                    $('#moviments').val(data.moviments);
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
            }, 2000);
        <?php } ?>

        // Creating a function that will change 
        let mysound = document.getElementById("player_1");
        let icon = document.getElementById("player_1_button");

        // pause to play and vice-versa
        icon.onclick = function () {
            mysound.play();
            mysound.volume = 0.10; // 75%
        }


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

        function get_start(id_item) {
            $.ajax({
                dataType: 'json',
                ContentType: 'application/json',
                type: 'POST',
                url: "{{ route('Add_Request_Truco_Room_Start.post') }}",
                data: { id_item: id_item },
                success: function (data) {

                    console.log(data);

                    if(data.errors == undefined) {
                        if(data.status == "true") {
                            // Interval redirect
                            UIkit.notification(data.log, 'success');
                            setTimeout(function() {
                                window.location= "{{ env('APP_URL') }}"+data.redirect+"";
                            }, 500);
                        } else if(data.status == "timer") {
                            // Interval redirect
                            $("#timer").html(data.log);
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

        function get_start_cancel(id_item) {
            $.ajax({
                dataType: 'json',
                ContentType: 'application/json',
                type: 'POST',
                url: "{{ route('Add_Request_Truco_Room_Cancel.post') }}",
                data: { id_item: id_item },
                success: function (data) {

                    if(data.errors == undefined) {
                        if(data.status == "true") {
                            // Interval redirect
                            UIkit.notification(data.log, 'success');
                            setTimeout(function() {
                                window.location = ""+data.redirect+"";
                            }, 500);
                        } else if(data.status == "timer") {
                            // Interval redirect
                            $("#timer").html(data.log);
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

        function get_start_synchronize(id_item) {
            $.ajax({
                dataType: 'json',
                ContentType: 'application/json',
                type: 'POST',
                url: "{{ route('Get_start_Synchronize.post') }}",
                data: { id_item: id_item },
                success: function (data) {
                    console.log(data);
                }
            });
        }

        setInterval(function () {
            $("#player_1_button").click();
        }, 1000);
    </script>
@endsection


