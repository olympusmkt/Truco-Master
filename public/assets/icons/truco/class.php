<?php 

//get numbers, players
function get_number($start, $cards_numbers, $array_Number, $array_Simbol, $Type_User, $id_user, $total) {
	$rand_keys = array_rand($array_Number, $cards_numbers);
	$rand_objs = array_rand($array_Simbol, $cards_numbers);
	$create_json = "";
	$create_json_outer = "";

	//starts counts
	$i = $start;
	$item_count = 0;

	while ($i <= $cards_numbers - 1) {  
		$item_count++; $item_rands = rand('1000', '10000');

		if ($Type_User == "user") { 
			//echo "<img src='".asset('assets/icons/truco/red_back.png')."'>";
			//$create_json_outer .= ''.$array_Number[$rand_keys[''.$i.'']].''.$array_Simbol[$rand_objs[$i]].', ';
		} else { 
			$create_json .= ''.$array_Number[$rand_keys[''.$i.'']].''.$array_Simbol[$rand_objs[$i]].', ';
		}
		$i++;
	}

	if ($Type_User != "user") { 
		$all_json_id_Itensx = substr($create_json, 0, -2);
		$item_formated = explode(", ", $all_json_id_Itensx);
		foreach ($item_formated as $key_rows_1) { $item_count++; $item_rands = rand('1000', '10000'); ?>
			<img id="item_lol_user_<?php echo $item_count; ?>_<?php echo $item_rands; ?>" onclick="add_moviment('<?php echo $key_rows_1; ?>', 'item_lol_user_<?php echo $item_count; ?>_<?php echo $item_rands; ?>')" label_id='<?php echo $key_rows_1; ?>' src="<?php echo asset('assets/icons/truco/cards/'.$key_rows_1.'.png'); ?>">
		<?php }
	} else {
		$is = 1;
		while ($is <= $total) {
			echo "<img src='".asset('assets/icons/truco/red_back.png')."'>";
			$is++;
		}
	}

	// valido se está vázio ou não
	if ($create_json != "") {

		//arrays itens
		$all_json_id_Itens = substr($create_json, 0, -2);
		$code_rons = Session('get_room_confirmed');

		//selecionar users da partida
		$select_confirmed_user = DB::table("player_truco_start_confirmed")->Where('players_1', '=', ''.$id_user.'')->where('code_room', '=',''.$code_rons.'')->get();

		// Count users page
		$select_confirmed_user_total = $select_confirmed_user->count();

		if ($select_confirmed_user_total > 0) {

			foreach ($select_confirmed_user as $key_players) {

				//atualizo caso esteja em null
				if ($key_players->cards == null) {
					// Updated_at date
	                $updated_at = date('Y-m-d H:i:s');

	                // Insert into user new
	                $request_updater_user = DB::update('update player_truco_start_confirmed set cards = ?, cards_history = ?, updated_at = ? where id = ?', [
	                    $all_json_id_Itens,
	                    $all_json_id_Itens,
	                    $updated_at,
	                    $key_players->id,
	                ]);
				}

			}
		}
	}
}

function get_number_fix($cards, $id_user) {
	$item_count = 0;
	$item_formated = explode(", ", $cards);
	foreach ($item_formated as $key_rows_1) { $item_count++; $item_rands = rand('1000', '10000'); ?>
		<img id="item_lol_user_<?php echo $item_count; ?>_<?php echo $item_rands; ?>" onclick="add_moviment('<?php echo $key_rows_1; ?>', 'item_lol_user_<?php echo $item_count; ?>_<?php echo $item_rands; ?>')" label_id='<?php echo $key_rows_1; ?>' src="<?php echo asset('assets/icons/truco/cards/'.$key_rows_1.'.png'); ?>">
	<?php }
}

