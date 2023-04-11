<?php 

function getNetxblock($block_id){
	include_once($_SERVER['DOCUMENT_ROOT'].'/library.php');
	include(DIR_AUTH.'config.php');
	$blocks = array();
    $select3 = mysqli_query($conn, "SELECT * FROM bot_blocks blcks LEFT JOIN bot_types tps ON (tps.type_id=blcks.type) WHERE blcks.block_id='".(int)$block_id."'");
    if ($select3){
    	while ($result = mysqli_fetch_array($select3)) { 
    		$buts = null;
    		if($result['type'] == 2){
    			$select2 = mysqli_query($conn, "SELECT bmi.button, bmi.button_id, bmi.next_block_id FROM bot_blocks_menu_items bmi LEFT JOIN bot_blocks blcks ON (bmi.block_id=blcks.block_id) WHERE blcks.block_id='".(int)$block_id."'");
    			if ($select2){
    				$buts = array();
    				while ($result2 = mysqli_fetch_array($select2)) { 
    					$buts[] = array(
    						'button'		=> $result2['button'],
    						'button_id'		=> $result2['button_id'],
    						'next_block_id'	=> $result2['next_block_id']
    					);
    				}
    			}
			}

    		$blocks = array(
    			'block_id' 			=> $result['block_id'],
	    		'type' 	   			=> $result['type'],
	    		'type_description' 	=> $result['type_description'],
	    		'message'  			=> $result['message'],
	    		'next_block_id'		=> $result['next_block_id'],
	    		'buttons'  			=> $buts
    		);	
    		break;
    	} 
    }
    return $blocks;
}

function phone_to_base($chat_id, $text){
	include_once($_SERVER['DOCUMENT_ROOT'].'/library.php');
	include(DIR_AUTH.'config.php');
	mysqli_query($conn, "UPDATE `bot_users` SET phone='".$text."' WHERE chat_id='".$chat_id."'");
}

function to_base($bot_id, $block_id){
	include_once($_SERVER['DOCUMENT_ROOT'].'/library.php');
	include(DIR_AUTH.'config.php');
	mysqli_query($conn, "UPDATE `bots` SET current_block='".$block_id."' WHERE bot_id='".$bot_id."'");

}
function user_to_base($chat_id, $user_name, $name, $bot_id){
	include_once($_SERVER['DOCUMENT_ROOT'].'/library.php');
	include(DIR_AUTH.'config.php');
	$select = mysqli_query($conn, "SELECT * FROM bot_users WHERE chat_id='".(int)$chat_id."' AND bot_id='".(int)$bot_id."'");
    if ($select){
    	while($res = mysqli_fetch_array($select)){
    		$us_id = $res['user_id'];
    		$return .= $us_id;
    		break;
    	}
    }
    if (isset($us_id)){
    	if ($us_id){
    		mysqli_query($conn, "UPDATE `bot_users` SET chat_id='".$chat_id."', user_login='".$user_name."', user_name='".$name."' WHERE user_id='".$us_id."'");
    	} else {
    	mysqli_query($conn, "INSERT INTO `bot_users` (`user_id`, `chat_id`, `user_login`, `user_name`, `phone`, `bot_id`) VALUES (NULL, '".(int)$chat_id."', '".$user_name."', '".$name."', NULL, ".$bot_id.");");
    	}
	} else {
    	mysqli_query($conn, "INSERT INTO `bot_users` (`user_id`, `chat_id`, `user_login`, `user_name`, `phone`, `bot_id`) VALUES (NULL, '".(int)$chat_id."', '".$user_name."', '".$name."', NULL, ".$bot_id.");");
    }
}
function getUser($user_login){
	$return = array();
	include_once($_SERVER['DOCUMENT_ROOT'].'/library.php');
	include(DIR_AUTH.'config.php');
	$select = mysqli_query($conn, "SELECT DISTINCT * FROM bot_users WHERE user_login='".$user_login."'");
    if (!empty($select)){
    	while($res = mysqli_fetch_array($select)){
    		$return['user_login'] = $res['user_login'];
    		$return['user_name']  = $res['user_name'];
    		$return['chat_id']    = $res['chat_id'];
    		$return['user_id']    = $res['user_id'];
    		$return['phone']      = $res['phone'];
    		break;
    	}
    }
    return $return;
}
function steps_to_base($chat_id, $text, $bot_id){
	include_once(__DIR__.'/../auth/config.php');
	$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);
	mysqli_set_charset($conn, 'utf8');
	$select = mysqli_query($conn, "SELECT DISTINCT * FROM bot_users_data WHERE chat_id='".$chat_id."' AND date='".date('Y-m-d')."' AND bot_id='".$bot_id."'");
    if ($select){
    	while($res = mysqli_fetch_array($select)){
    		$steps = $res['steps'];
    		break;
    	}
    }
    if (isset($steps)){
    	if ($steps){
    		$steps .= " -> ". $text;
    		mysqli_query($conn, "UPDATE `bot_users_data` SET steps='".$steps."' WHERE chat_id='".$chat_id."' AND date='".date('Y-m-d')."' AND bot_id='".$bot_id."'");
    	} else {
    	$steps .= " -> ". $text;
    		mysqli_query($conn, "UPDATE `bot_users_data` SET steps='".$steps."' WHERE chat_id='".$chat_id."' AND date='".date('Y-m-d')."' AND bot_id='".$bot_id."'");
    	}
    } else {
    	mysqli_query($conn, "INSERT INTO `bot_users_data` (`chat_id`, `date`, `steps`, `bot_id`) VALUES ('".$chat_id."', '".date('Y-m-d')."', '".$text."', '".$bot_id."');");
    }
}

function getStepText($data, $button_array){
	$return = '';
	if (is_array($button_array)){
		foreach ($button_array as $button){
			if ($button[0]['callback_data'] == $data){
				$return = $button[0]['text'];
			}
		}
	}
	return $return;
}

// функция отправки сообщени в от бота в диалог с юзером
function sendMessage($chat_id, $message, $replyMarkup=false, $entities=false){
	$mute=false;
    $url = URL . "/sendMessage";
    $post_fields = array(
        'chat_id'   			=> $chat_id,
        'text'      			=> urldecode($message),
        'disable_notification'  => $mute,
        //'parse_mode'			=> $pmode,
        'reply_markup'			=> $replyMarkup,
        'entities'				=> $entities
    );
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array( "Content-Type:multipart/form-data" ));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    $response = curl_exec($ch);
    curl_close($ch);
    usleep(500);
    $res = json_decode($response, TRUE);
    return $res; //int id сообщения.
}

/*------------сборка сообщения---------------*/
function message_construct($type, $text, $buttons=null, $next_block_id=null, $main){
	$return = array();
	if ($type == 1){
		$return['chat_id'] = $main['chat_id'];
		$return['text'] = $text;
		$bts = array(0 =>array(0 => array('text' => 'Ok', 'callback_data' => $next_block_id)));
		$reply_markup =  array('inline_keyboard' => $bts);
		$return['reply_markup'] = json_encode($reply_markup);

	} elseif($type == 2) {
		$return['chat_id'] = $main['chat_id'];
		$bts = array();
		foreach($buttons as $button){
			$bts[][] = array('text' => urldecode($button['button']), 'callback_data' => $button['next_block_id']);
		}
		$return['text'] = $text;
		$reply_markup =  array('inline_keyboard' => $bts);
		$return['reply_markup'] = json_encode($reply_markup);
	} elseif($type == 5) {
		$return['chat_id'] = $main['chat_id'];
		$return['text'] = $text;
	} elseif($type == 4) {
		$return['chat_id'] = $main['manager'];
		$return['text'] = $main['steps_to_send'];
	}
	return $return;
}

function steps_to_send($chat_id){
	$return = '';
	include_once($_SERVER['DOCUMENT_ROOT'].'/library.php');
	include(DIR_AUTH.'config.php');
	$select = mysqli_query($conn, "SELECT DISTINCT * FROM bot_users_data WHERE chat_id='".$chat_id."' AND date='".date('Y-m-d')."'");
    if ($select){
    	while($res = mysqli_fetch_array($select)){
    		$steps = explode(' -> ', $res['steps']);
    		break;
    	}
    }
    // передаю не более 20 последних шагов, для исключения перегруза сообщения
    $limit = 20;
    if (count($steps) <= $limit){
    	$return = implode(' -> ', $steps);
    } else {
    	for($i=(count($steps)-$limit); $i<count($steps); $i++){
    		$return .=$steps[$i].' -> ';
    	}
    }
    return $return;
}

function getManager($login, $bot_id){
	$return = false;
	include_once($_SERVER['DOCUMENT_ROOT'].'/library.php');
	include(DIR_AUTH.'config.php');
	$select = mysqli_query($conn, "SELECT * FROM bot_users WHERE user_login='".$login."' AND bot_id='".$bot_id."'");
    if (!empty($select)){
    	while($res = mysqli_fetch_array($select)){
    		$user_id = $res['user_id'];
    		break;
    	}
    }
    if (isset($user_id)){
    	if (!empty($user_id)){
    		$return = true;
    	}
    }
    return $return;
}

function getMybot($login, $bot_id){
	$return = false;
	include_once($_SERVER['DOCUMENT_ROOT'].'/library.php');
	include(DIR_AUTH.'config.php');
	$select = mysqli_query($conn, "SELECT * FROM bots WHERE login='".$login."' AND bot_id='".$bot_id."'");
    if (!empty($select)){
    	while($res = mysqli_fetch_array($select)){
    		$user_id = $res['login'];
    		break;
    	}
    }
    if (isset($user_id)){
    	if (!empty($user_id)){
    		$return = true;
    	}
    }
    return $return;
}

function getMychats($login, $bot_id){
	$return = array();
	include_once($_SERVER['DOCUMENT_ROOT'].'/library.php');
	include(DIR_AUTH.'config.php');
	$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);
	mysqli_set_charset($conn, 'utf8');
	$select = mysqli_query($conn, "SELECT * FROM `bots` LEFT JOIN `bot_users` ON (bots.bot_id=bot_users.bot_id) WHERE bots.login='".$login."' AND bots.bot_id='".$bot_id."'");
    if (!empty($select)){
    	while($res = mysqli_fetch_array($select)){
    		$return[] = $res['chat_id'];
    	}
    }
    return $return;
}
function getFirst($login, $bot_id){
	$return = null;
	include_once($_SERVER['DOCUMENT_ROOT'].'/library.php');
	include(DIR_AUTH.'config.php');
	$select = mysqli_query($conn, "SELECT * FROM `bots` WHERE login='".$login."' AND bot_id='".$bot_id."'");
    if (!empty($select)){
    	while($res = mysqli_fetch_array($select)){
    		$return = $res['first_block_id'];
    		break;
    	}
    }
    return $return;
}
function get_questions($setting){

	$return = array();

	if (is_array($setting)) {
		foreach ($setting as $question) {

			$answ = array();
			if (isset($question['answers']) && !empty($question['answers'])) {

				foreach ($question['answers'] as $answer) {
					$answ[] = array(
						'name'	=> $answer['name'],
						'type'  => $answer['type'],
						'next' 	=> $answer['next']
					);
				}	
			}

			$return[] = array(
				'sort_order' => $question['sort_order'],
				'name'		 => $question['name'],
				'answers'	 => $answ
			);
		}	
	}

	return $return;
}

function getMyquiz($login, $quiz_id){
	$return = false;
	include_once($_SERVER['DOCUMENT_ROOT'].'/library.php');
	include(DIR_AUTH.'config.php');
	$select = mysqli_query($conn, "SELECT * FROM quiz WHERE login='".$login."' AND quiz_id='".$quiz_id."'");
    if (!empty($select)){
    	while($res = mysqli_fetch_array($select)){
    		$user_id = $res['login'];
    		break;
    	}
    }
    if (isset($user_id)){
    	if (!empty($user_id)){
    		$return = true;
    	}
    }
    return $return;
}

?>