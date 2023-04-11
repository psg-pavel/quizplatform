<?php

include_once(__DIR__.'/library.php');
include_once(DIR_INCLUDES.'functions.php');
include_once(DIR_INCLUDES.'functions_bot.php');
include_once(DIR_AUTH.'config.php');
include('auth/check.php');

if (isset($_GET['quiz_id'])){
	$quiz_id = $_GET['quiz_id'];
	if ($quiz_id){
	    $mydata = array();
	    $select = mysqli_query($conn, "SELECT qd.data_id, qd.name, qd.phone, qd.date_added, qd.text, qd.quiz_id, qz.setting FROM quiz_data qd LEFT JOIN quiz qz ON (qd.quiz_id=qz.quiz_id) WHERE qd.quiz_id='".$quiz_id."' ORDER BY qd.data_id DESC");
	    if ($select){
	    	while ($res = mysqli_fetch_array($select)) { 

	    		$answers = json_decode($res['text'] ,true);
	    		$questions = json_decode($res['setting'] ,true);
	    		$answer_text = '';

	    		for ($i=0; $i<count($questions); $i++){
					if (isset($answers['radio_'.$i])){
						$answer_text .= ($i+1).': '.$questions[$i]['name'].' - '.$answers['radio_'.$i].'<br>';
						if (isset($answers['text_'.$i]) && $answers['text_'.$i] != ''){
							$answer_text .= ' ('.$answers['text_'.$i].') <br>';	
						}
					}
				}

	    		$mydata[] = array(
	    			'date'		 	 => $res['date_added'],
	    			'user_name'		 => $res['name'],
	    			'phone'			 => $res['phone'],
	    			'answers'	 	 => $answer_text
	    		);
	    	} 
	    }
	}
}

?>
<html>
	<head> 
		<meta charset="utf-8" content="text/html" />
		<title> Simple Stat </title>
		<link rel="stylesheet" type="text/css" href="/styles.css" />
	</head>

	<body>
		<? include_once(DIR_INCLUDES.'header.php'); ?>
		<? include_once(DIR_INCLUDES.'menu.php'); ?>
		<? include_once(DIR_INCLUDES.'support.php'); ?>
		<? if ( getMyquiz($auth_login, $quiz_id)) {?>
		<div class="center" style="padding-bottom: 100px;">
			<h3>Посетители:</h3>
			<div style="background: white; padding: 5px; margin: 3px; height: 25px;">
				<div style="width: 15%; margin-top: 5px; float: left;">Дата</div>
				<div style="width: 15%; margin-top: 5px; float: left;">Имя</div>
				<div style="width: 15%; margin-top: 5px; float: left;">Телефон</div>
				<div style="width: 55%; margin-top: 5px; float: left;">Путь посещения</div>
			</div>
			<?if ($auth_role == '1') {
			    include_once(DIR_SETTING.'tabs1.php'); 
			} else {
				foreach ($mydata as $string){?>
					<div style="background: white; padding: 5px; margin: 3px; height: 120px;">
						<div style="width: 15%; margin-top: 20px; float: left;"><?echo $string['date'];?>&nbsp;</div>
						<div style="width: 15%; margin-top: 20px; float: left;"><?echo $string['user_name'];?>&nbsp;</div>
						<div style="width: 15%; margin-top: 20px; float: left;"><?echo $string['phone'];?>&nbsp;</div>
						<div style="width: 55%; float: left; overflow: auto; height: 120px;"><?echo $string['answers'];?>&nbsp;</div>
					</div>
			<?	}
			}   
			?>
		</div>
		<? } ?>
		<? include_once(DIR_INCLUDES.'footer.php'); ?>
	</body>
</html>