<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Session;


class TrucoController extends Controller {

    // Dashboard add function 
    public function Add_Request_Truco_Room(Request $request) {

        //Validation inputs
        $validator = Validator::make($request->all(),
            [
                'type' => 'required',
                'players' => 'required',
                'values' => 'required'
            ], [
                'required' => 'O campo :attribute é obrigatório'
            ], [
                'type'     => 'Tipo de Truco',
                'players'     => 'Tipo de Partida',
                'values'     => 'Valores'

            ]
        );

        // Validation erros
        if ($validator->fails()){
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        } else {

            // Keys values static
            $created_at = date('Y-m-d H:i:s');

            // Get user id
            $id = Auth::user()->id;
            $user = User::find($id);

            // Insert into user new
            $request_user = DB::insert(
                'insert into player_truco_start (type, players, value_room, players_1, status_room, created_at) values (?, ?, ?, ?, ?, ?)',
                [
                    $request->type,
                    $request->players,
                    $request->values,
                    $id,
                    '1',
                    $created_at
                ]
            );
            $id_insert = DB::getPdo()->lastInsertId();
            session(['get_room' => $id_insert]);


            // Auth validation redirects
            return response()->json(['status' => 'true', 'log' => 'Iniciando partida, aguarde um momento..', 'redirect' => 'user/truco']);
        }
    }

    // Dashboard add function 
    public function Add_Request_Truco_Room_Start(Request $request) {

        //Validation inputs
        $validator = Validator::make($request->all(),
            [
                'id_item' => 'required'
            ], [
                'required' => 'O campo :attribute é obrigatório'
            ], [
                'id_item'     => 'ID DO ITEM'

            ]
        );

        // Validation erros
        if ($validator->fails()){
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        } else {

            // Get user id
            $id = Auth::user()->id;
            $user = User::find($id);

            // Seletct - encontrar criação de sala, valida 
            $select_rooms = DB::table("player_truco_start")->where('players_1', '!=',''.$id.'')->where('status_room', '=','1')->limit('1')->get();
            $vinculate_user = DB::table("player_truco_start")->where('players_1', '=',''.$id.'')->where('status_room', '=','2')->limit('1')->get();

            // Count Seletct
            $count_rooms = $select_rooms->count();
            $vinculate_user_total = $vinculate_user->count();

            // Time de expiração
            $time_temp = 30;

            if (Session::has('get_room')) {

                //validation isse time out
                if (Session::has('get_room_time')) {
                    // Fix values publics
                    $value_fixed = session('get_room_time');

                    if ($value_fixed > 1) {

                        // Values formated
                        $value_fixed_new = 1 - $value_fixed;
                        $urL_explode = explode("-", $value_fixed_new);

                        // Create session new
                        session(['get_room_time' => $urL_explode[1]]);
                    }
                } else {
                    session(['get_room_time' => $time_temp]);
                }

                // get room time 
                $value_fixed_reload = session('get_room_time');

                //valido se o id_room_confirmed 
                if ($vinculate_user_total > 0) {
                    foreach ($vinculate_user as $vinculate_user_rels) {

                        if ($vinculate_user_rels->confirmed == null) {
                            // Nenhuma função para null -> vazio
                        } else {
                            // Criando session de game 
                            session(['get_room_confirmed' => $vinculate_user_rels->confirmed]);
                            // Redirect truco session validation
                            return response()->json(['status' => 'true', 'log' => 'Conectando jogadores..', 'redirect' => 'user/truco']);
                        } 
                    }
                }

                // Validation -> finaliza o loading
                if ($value_fixed_reload == 1) {

                    // Removendo item da db
                    $updated_at = date('Y-m-d H:i:s');
                    $value_id = session('get_room');

                    // Insert into user new
                    $request_updater_user = DB::update('update player_truco_start set status_room = ?, updated_at = ? where id = ?', [
                        '0',
                        $updated_at,
                        $value_id
                    ]);
                    
                    // Remove session
                    session()->forget('get_room');
                    session()->forget('get_room_time');

                    // Validandoitem expirado
                    return response()->json(['status' => 'timer', 'log' => '1 segundo']);

                } else {
                    return response()->json(['status' => 'timer', 'log' => ''.$value_fixed_reload.' segundos<br>('.$count_rooms.') Jogadores encontrados']);
                }

            } else {    
                // Validandoitem expirado
                return response()->json(['status' => 'true', 'log' => 'Nenhum jogador encontrado, tente novamente..', 'redirect' => 'user/truco']);
            }  
        }
    }

     // Dashboard add function 
    public function Get_start_Synchronize(Request $request) {

        //Validation inputs
        $validator = Validator::make($request->all(),
            [
                'id_item' => 'required'
            ], [
                'required' => 'O campo :attribute é obrigatório'
            ], [
                'id_item'     => 'ID DO ITEM'

            ]
        );

        // Validation erros
        if ($validator->fails()){
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        } else {

            // Get user id
            $id = Auth::user()->id;
            $user = User::find($id);
            $rand_room = rand(1000000, 1900000);

            //Seletct - encontrar criação de sala, valida 
            $select_rooms = DB::table("player_truco_start")->where('status_room', '=','1')->limit('2')->get();

            // Count Seletct
            $count_rooms = $select_rooms->count();

            if ($count_rooms >= 2) {
                //realizo o update do status
                foreach ($select_rooms as $select_rooms_keys) {

                    // Null deve add a tabela confirmed -> 
                    $created_at = date('Y-m-d H:i:s');
                    //Insert into user new
                    $request_user = DB::insert(
                        'insert into player_truco_start_confirmed (start_id, players_1, code_room, created_at) values (?, ?, ?, ?)',
                        [
                            $select_rooms_keys->id,
                            $select_rooms_keys->players_1,
                            $rand_room,
                            $created_at
                        ]
                    );
                    $id_insert_confir = DB::getPdo()->lastInsertId();

                    // Atualizando status
                    $updated_at = date('Y-m-d H:i:s');
                    // Insert into user new
                    $request_updater_user = DB::update('update player_truco_start set status_room = ?, updated_at = ?, confirmed = ? where id = ?', [
                        '2',
                        $updated_at,
                        $rand_room,
                        $select_rooms_keys->id
                    ]);


                    // Criando session de game 
                    session(['get_room_confirmed' => $rand_room]);
                }
            }

            return response()->json(['status' => 'true', 'log' => 'Conectando jogadores..']);
        }
    }

    // Dashboard add function 
    public function Add_Request_Truco_Room_Cancel(Request $request) {

        //Validation inputs
        $validator = Validator::make($request->all(),
            [
                'id_item' => 'required'
            ], [
                'required' => 'O campo :attribute é obrigatório'
            ], [
                'id_item'     => 'ID DO ITEM'

            ]
        );

        // Validation erros
        if ($validator->fails()){
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        } else {

            // Removendo item da db
            $updated_at = date('Y-m-d H:i:s');
            $value_id = session('get_room');

            // Insert into user new
            $request_updater_user = DB::update('update player_truco_start set status_room = ?, updated_at = ? where id = ?', [
                '-0',
                $updated_at,
                $value_id
            ]);

            // Remove session
            session()->forget('get_room');
            session()->forget('get_room_time');
            session()->forget('get_room_confirmed');

            // Validandoitem expirado
            return response()->json(['status' => 'true', 'log' => 'Partida cancelada, aguarde um momento..', 'redirect' => ''.env('APP_URL').'']);
            
        }
    }

    // Dashboard add function 
    public function Add_Request_Room_Moviment_User(Request $request) {

        //Validation inputs
        $validator = Validator::make($request->all(),
            [
                'id_item' => 'required'
            ], [
                'required' => 'O campo :attribute é obrigatório'
            ], [
                'id_item'     => 'ID DO ITEM'

            ]
        );

        // Validation erros
        if ($validator->fails()){
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        } else {

            // Removendo item da db
            $updated_at = date('Y-m-d H:i:s');
            $value_id = session('get_room');

            // Get user id
            $id = Auth::user()->id;
            $user = User::find($id);

            // Null deve add a tabela confirmed -> 
            $created_at = date('Y-m-d H:i:s');
            //Insert into user new
            $request_user = DB::insert(
                'insert into player_truco_start_confirmed_moviments (players_1, code_room, confirmed_id, card, created_at, moviments_partid) values (?, ?, ?, ?, ?, ?)',
                [
                    $id,
                    $request->id_item,
                    'null',
                    $request->card,
                    $created_at,
                    $request->moviments_partid
                ]
            );
            $id_insert_confir = DB::getPdo()->lastInsertId();

            //selecionar users da partida
            $select_confirmeds = DB::table("player_truco_start_confirmed")->Where('players_1', '=', ''.$id.'')->where('code_room', '=',''.$request->id_item.'')->get();

            // Count users page
            $select_confirmeds_total = $select_confirmeds->count();

            // If validation
            if ($select_confirmeds_total > 0) {
                foreach ($select_confirmeds as $key_info) {

                    //Formated json
                    $item_formated = explode(", ", $key_info->cards);

                    //Formated arrays
                    $new_cards = "";
                    foreach ($item_formated as $key_rows_1) {
                        if ($key_rows_1 != $request->card) {
                            $new_cards .= "".$key_rows_1.", ";
                        }
                    }

                    //New value cards
                    $all_json_id_Itensx = substr($new_cards, 0, -2);

                    // Removendo item da db
                    $updated_at = date('Y-m-d H:i:s');

                    // Insert into user new
                    $request_updater_user = DB::update('update player_truco_start_confirmed set cards = ?, updated_at = ? where id = ?', [
                        $all_json_id_Itensx,
                        $updated_at,
                        $key_info->id
                    ]);
                }
            }

            $item_values_mov = $request->moviments+1;
            // Validandoitem expirado
            return response()->json(['status' => 'true', 'log' => 'Movimento da carta ('.$request->card.' / '.$all_json_id_Itensx.') realizado com sucesso..', 'redirect' => 'user/truco', 'value' => $item_values_mov]);
        }
    }

    // Dashboard add function 
    public function Get_Moviments_Updater(Request $request) {

        //Validation inputs
        $validator = Validator::make($request->all(),
            [
                'id_item_show' => 'required'
            ], [
                'required' => 'O campo :attribute é obrigatório'
            ], [
                'id_item_show'     => 'MOVIMENT'

            ]
        );

        // Validation erros
        if ($validator->fails()){
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        } else {

            // Get user id
            $id = Auth::user()->id;
            $user = User::find($id);


            //selecionar moviments
            $select_moviments = DB::table("player_truco_start_confirmed_moviments")->where('code_room', '=',''.$request->code.'')->get();

            // Count moviments
            $select_moviments_total = $select_moviments->count();


            if ($select_moviments_total > $request->id_item_show) {


                //selecionar moviments
                $select_moviments_details = DB::table("player_truco_start_confirmed_moviments")->where('code_room', '=',''.$request->code.'')->orderBy('id', 'ASC')->get();

                // Count moviments
                $select_moviments_detail_totaç = $select_moviments_details->count();

                foreach ($select_moviments_details as $key_cards) {
                    # code...
                }

                // Validandoitem expirado
                return response()->json(
                    [
                        'status' => 'true', 
                        'log' => 'success', 
                        'card' => ''.$key_cards->card.'.png',
                        'moviments' => ''.$select_moviments_total.'',
                        'player' => ''.$key_cards->players_1.'',
                        'logged' => ''.$id.'',
                        'total_movis_player_1' => ''.$select_moviments_total.''
                    ]
                );
            } else {

                //selecionar moviments
                $select_moviment_ultimo = DB::table("player_truco_start_confirmed_moviments")->where('moviments_partid', '=',''.$request->moviments_partid.'')->where('code_room', '=',''.$request->code.'')->get();
                $total_ultimo = $select_moviment_ultimo->count();

                // variaveis vazias
                $moviments_inner = 1;
                $get_type_game_players = 2; /// depois será global 2, ou 4
                $info = "null";

                // formated count moviments input
                if ($get_type_game_players == $total_ultimo) {
                    $total_movit_atual = $request->moviments_partid + 1;
                } else {
                    $total_movit_atual = $request->moviments_partid;
                }

                // validação e foreach
                $users = "false";
                if ($total_ultimo > 0) {
                    $users = "";
                    foreach ($select_moviment_ultimo as $select_moviment_ultimo_rows) {
                        $users .= "".$select_moviment_ultimo_rows->players_1.", ";
                    }
                    $users = substr($users,0,-2);
                }



                $user_player_card = 0;
                $user_player_card_name = "Jogador";
                if ($users == "false") {

                    $count_initia_player = DB::table("player_truco_start_confirmed")->where('code_room', '=',''.$request->code.'')->limit('1')->get();
                    $count_initia_player_total = $count_initia_player->count();

                    if ($count_initia_player_total > 0) {
                        foreach ($count_initia_player as $key_count_initia_player) {
                            $user_player_card = $key_count_initia_player->players_1;

                            $select_details = DB::table("users")->where('id', '=',''.$user_player_card.'')->get();
                            $select_details_total = $select_details->count();

                            if ($select_details_total > 0) {
                                foreach ($select_details as $ke_row) {
                                    $user_player_card_name = $ke_row->name;
                                }
                            }

                        }
                    }

                } else {

                    $count_initia_player = DB::table("player_truco_start_confirmed")->where('code_room', '=',''.$request->code.'')->where('players_1','<>', $users)->limit('1')->get();
                    $count_initia_player_total = $count_initia_player->count();

                    if ($count_initia_player_total > 0) {
                        foreach ($count_initia_player as $key_count_initia_player) {
                            $user_player_card = $key_count_initia_player->players_1;

                            $select_details = DB::table("users")->where('id', '=',''.$user_player_card.'')->get();
                            $select_details_total = $select_details->count();

                            if ($select_details_total > 0) {
                                foreach ($select_details as $ke_row) {
                                    $user_player_card_name = $ke_row->name;
                                }
                            }
                        }
                    }

                }

                return response()->json(
                    [
                        'status' => 'true', 
                        'log' => 'null',
                        'total_moviments_players' => ''.$total_ultimo.'',
                        'total_movit_atual' => ''.$total_movit_atual.'',
                        'user_card_player' => ''.$user_player_card.'',
                        'user_card_player_name' => ''.$user_player_card_name.'',
                        'logged' => ''.$id.'',
                        'users' => ''.$users.''
                    ]
                );
            }

            
            
        }
    }

    // Public page source function
    public function start_Truco(){

        if (!auth()->check()) {
            // User is not authenticated, redirect to the login page
            return redirect('user/login');
        } else {

            // Get user id
            $id = Auth::user()->id;
            $user = User::find($id);

            // Variables itens
            // Eliminados do array -> '8', '9', '10'
            $Initial_Card = "3";
            $Simbol = array('C', 'D', 'H', 'S');
            $Number = array('A', '2', '3', '4', '5', '6', '7', 'J', 'Q', 'K');
            $Simbol_Icon = array('&spades;', '&clubs;', '&hearts;', '&diams;');

            // Remove session
            // session()->forget('get_room');
            // session()->forget('get_room_time');
            // session()->forget('get_room_confirmed');

            // Fix variaveis
            $select_confirmeds = "";
            $total_users_online = "";
            $select_confirmeds_user = "";
            $total_user_online = "";
            $select_moviments = "";
            $select_moviments_total = "";
            $select_movi_all = "";
            $total_select_movi_all = "";
            $total_movi = 0;

            // Validação de sessison item
            if (Session::has('get_room')) {
                if (Session::has('get_room_confirmed')) { 

                    // Room id items
                    $room_id = session('get_room_confirmed');

                    //selecionar confirmeds !=
                    $select_confirmeds = DB::table("player_truco_start_confirmed")->Where('players_1', '!=', ''.$id.'')->where('code_room', '=',''.$room_id.'')->get();
                    $total_users_online = $select_confirmeds->count();

                    //selecionar confirmeds = 
                    $select_confirmeds_user = DB::table("player_truco_start_confirmed")->Where('players_1', '=', ''.$id.'')->where('code_room', '=',''.$room_id.'')->get();
                    $total_user_online = $select_confirmeds_user->count();


                    //selecionar moviments
                    $select_moviments = DB::table("player_truco_start_confirmed_moviments")->where('code_room', '=',''.$room_id.'')->get();
                    $select_moviments_total = $select_moviments->count();

                    //selecionar confirmeds
                    $select_total_users = DB::table("player_truco_start_confirmed")->Where('players_1', '!=', ''.$id.'')->where('code_room', '=',''.$room_id.'')->get();
                    $select_total_users_total = $select_total_users->count();

                    // Valido os confirmeds de outro user
                    if ($select_total_users_total > 0) {
                        foreach ($select_total_users as $key_values) {

                            $select_movi_user = DB::table("player_truco_start_confirmed_moviments")->Where('players_1', '=', ''.$key_values->players_1.'')->where('code_room', '=',''.$room_id.'')->get();
                            $select_movi_user_total = $select_movi_user->count();

                            if ($select_movi_user_total == '1') {
                                $total_movi = "2";
                            } else if ($select_movi_user_total == '2') {
                                $total_movi = "1";
                            } else if ($select_movi_user_total == '3') {
                                $total_movi = "3";
                            } else {
                                $total_movi = "3";
                            }
                        }
                    }
                }
            } 
            
            // Validation simple roles
            return view('user/truco', compact('user', 'Initial_Card', 'Simbol', 'Number', 'Simbol_Icon', 'total_users_online', 'select_confirmeds', 'id', 'select_confirmeds_user', 'total_user_online', 'select_moviments', 'select_moviments_total', 'total_movi'));
        }
    }

    // Public function profile auth user
    public function home_user(){

        if (Session::has('get_room_confirmed')) {
            return redirect('user/truco');
        } else {
            return view('/welcome');
        }
    }

    // Dashboard add function 
    public function Get_Wallet_Api($type) {

        if (!auth()->check()) {
            $id = 0;
        } else {
            // Get user id
            $id = Auth::user()->id;
            $user = User::find($id);
        }

        //selecionar moviments
        $select_user = DB::table("users")->where('id', '=',''.$id.'')->get();

        // Count moviments
        $select_user_total = $select_user->count();

        if ($select_user_total > 0) {
            foreach ($select_user as $key_select_user) {

                if ($type == "saldo") {
                    $valuex = $key_select_user->saldo / 100;
                    $value = number_format($valuex, 2, ',', '.');
                }
                
            }
        } else {
            $value = "0,00";
        }

        return response()->json(
            [
                'wallet' => $type, 
                'value' => $value
            ]
        );
    }


}

