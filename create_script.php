<?php
include_once(__DIR__.'/library.php');
include_once(DIR_INCLUDES.'functions.php');
include_once(DIR_INCLUDES.'functions_bot.php');

include_once(DIR_AUTH.'config.php');
include('auth/check.php');


$is_my = false;

if (isset($_GET['quiz_id'])){
	$quiz_id = $_GET['quiz_id'];
	if ($quiz_id){
	    $myquiz = array();
	    $select = mysqli_query($conn, "SELECT * FROM quiz qz WHERE qz.quiz_id='".$quiz_id."' AND login='".$auth_login."'");
	    if ($select){
	    	while ($res = mysqli_fetch_array($select)) { 
	    		$myquiz = array(
	    			'quiz_id'		 => $res['quiz_id'],
	    			'name'			 => $res['name'],
	    			'domain'	     => $res['domain'],
	    			'setting'	 	 => json_decode($res['setting'] ,true),
	    			'other_setting'  => json_decode($res['other_setting'] ,true),
	    			'logo'			 => $res['logo'],
	    			'background'	 => $res['background']
	    		);
	    		$is_my = true;
	    		break;
	    	} 
	    }
	}
}

$issetcheck = 0;

/*----------------------------- вопросы - ответы------------------------------*/

$questions = get_questions($myquiz['setting']);
$other_setting = $myquiz['other_setting'];
$other_setting['redirect'] = $other_setting['redirect'] ? $other_setting['redirect'] : $myquiz['domain'];

$main_text = "";
if ($is_my){
$main_text .= "<style type='text/css'>";
$main_text .= "
	#q-row{width: 80%;
				max-width: 1200px;
				min-width: 500px;
				position: absolute;
				left: 10%;}
	body {
		font-family: SANS-SERIF;
	}
	.callback {
	  background: rgba(0 60 82 / 58%);
	  -webkit-border-radius: 18px;
	  -moz-border-radius: 18px;
	  -ms-border-radius: 18px;
	  border-radius: 18px;
	  padding: 25px 40px 12px 40px;
	  margin: 15px 20px 30px 20px;
	  min-height: 480px;
	}
	.callback .cb-title {
	  color: #ffffff;
	  text-transform: uppercase;
	  text-align: left;
	  margin-bottom: 28px;
	  font-size: 18px;
	  border-radius: 15px;
	  padding: 5px;
	}
	.callback input {
	  width: 99%;
	  padding: 8px 14px 12px 14px;
	  -webkit-border-radius: 8px;
	  -moz-border-radius: 8px;
	  -ms-border-radius: 8px;
	  border-radius: 18px;
	  border: 0;
	  font-size: 19px;
	  background: #ffffff;
	  height: 38px;
	  margin-top: 11px;
    margin-bottom: 6px;
	}
	.callback input[type='radio'] {
	    width: 30px;
		height: 30px;
		border: 0;
		font-size: 25px;
		background: #ffffff;
		float: left;
		margin: 7px;
	}
	.callback input:focus {
	  outline: 0;
	  border: 0;
	  box-shadow: none;
	}
	.callback input:focus::-webkit-input-placeholder {
	  color: #fff;
	}
	.callback input:focus:-moz-placeholder {
	  color: #fff;
	}
	.callback input:focus::-moz-placeholder {
	  color: #fff;
	}
	.callback input:focus:-ms-input-placeholder {
	  color: #fff;
	}
	.callback input::-webkit-input-placeholder {
	  position: relative;
	  top: 3px;
	}
	.callback input:-moz-placeholder {
	  position: relative;
	  top: 3px;
	}
	.callback input::-moz-placeholder {
	  position: relative;
	  top: 3px;
	}
	.callback input:-ms-input-placeholder {
	  position: relative;
	  top: 3px;
	}
	.callback .fcallback {
	  width: 100%;
	  padding: 8px 14px 11px 14px;
	  -webkit-border-radius: 18px;
	  -moz-border-radius: 18px;
	  -ms-border-radius: 18px;
	  border-radius: 18px;
	  border: 0;
	  font-size: 19px;
	  text-align: center;
	  height: 38px;
	  background: ".$other_setting['color'].";
		color: ".$other_setting['text_color'].";
	  cursor: pointer;
	  -moz-transition: 0.3s;
	  -o-transition: 0.3s;
	  -webkit-transition: 0.3s;
	  transition: 0.3s;
	}
	.callback .ok-message {
	  text-align: center;
	  color: #fff;
	  margin-top: 12px;
	  font-size: 14px;
	}
	.col-sm-11 {
	    width: 95%;
	    margin: 10px;
	}
	.fcallback{
		margin-top: 10px;
	}
	@media (max-width: 767px) {

	.mob_100{
		width: 100% !important;
	}
	.callback .cb-title {
	  color: #ffffff;
	  text-transform: uppercase;
	  text-align: center;
	  margin-bottom: 25px;
	  font-size: 16px;
	}
	.callback {
	    background: rgba(0 60 82 / 58%);
	    -webkit-border-radius: 10px;
	    -moz-border-radius: 10px;
	    -ms-border-radius: 10px;
	    border-radius: 18px;
	    padding: 15px 20px 10px 20px;
	    margin: 25px auto;
	    max-width: 300px;
	  }
	.callback input {
	    width: 100%;
	    padding: 8px 14px 12px 14px;
	    -webkit-border-radius: 8px;
	    -moz-border-radius: 8px;
	    -ms-border-radius: 8px;
	    border-radius: 18px;
	    border: 0;
	    font-size: 16px;
	    background: #FFF;
	    height: 34px;
	    margin-bottom: 10px;
	  }
	.callback .fcallback {
	    font-size: 16px;
	    height: 38px;
	    padding: 6px 14px 11px 14px;
	  }
	.form-width{
	  width: 100% !important;
	  margin-left: 0 !important;
	}
	}
	.num_quest{
	    color: white;
	    text-align: left;
	}
	.hidden-block{
	    display: none;
	}
	.fwd, .bck{
	    font-size: 16px;
	    padding: 9px;
	    width: 30%;
	    border-radius: 18px;
	    border: 0;
	    text-align: center;
	    background: ".$other_setting['color'].";
	    color: ".$other_setting['text_color'].";
	    float: left;
	    cursor: pointer;
	    max-width: 100px;
	}
	.fwd{
	    float: right !important;
	}
	.answer-row{
	    float: left;
	    min-width: 45%;
	    margin-right: 10px;
	    margin-bottom: 10px;
	    background: #ffffff2e;
	    border-radius: 20px;
	    padding-top: 3px;
	    text-align: left;
	    padding-left: 5px;
	}
	.answer-row label{
	    color: white;
	    padding: 10px;
	    cursor: pointer;
	    font-size: 18px;
	    position: relative;
	    top: 9px;
	}
	.down{
	    position: relative;
	}

	.form-block{
	    border-radius: 10px;
	    padding: 3px;
	    background: #8d5a5a00;
	}

	.fade-out-block{
				animation: outBlock 0.5s linear forwards;
			}
	@keyframes outBlock {
		0% {
	    opacity: 1;
	    transform: translateY(0px);
	    z-index: 1;
	  }
	  99% {
	    opacity: 0.1;
	    transform: translateY(50px);
	    z-index: 1;
	  }
	  100%{
	  	opacity: 0;
	  	z-index: -10;
	  }
	}

	.fade-in-block {
	  display: block;
	  animation: showBlock 0.5s linear forwards;
	}


	@keyframes showBlock {
	  0% {
	    opacity: 0;
	    transform: translateY(50px);
	  }
	  100% {
	    opacity: 1;
	    transform: translateY(0px);
	  }
	}

	.red-block{
	   animation: redBlock 0.35s cubic-bezier(0.72, 0.08, 0.25, 0.84) forwards; 
	}

	@keyframes redBlock {
	  0% {
	    background: #8d5a5a00;
	  }

	  20%{
	    transform: translateX(10px);
	  }
	  40%{
	    transform: translateX(-10px);
	  }
	  60%{
	    transform: translateX(10px);
	  }
	  100% {
	    transform: translateX(0px);
	    background: #8d5a5a;
	  }
	}
	input {
	    display: inherit;
	}
</style>";



 $main_text .= "<div id='q-row'>";
 		$main_text .= "<div id='start' style='background-size: cover !important; background-position: center; position: absolute; width: 97%; background: url(".$myquiz['background']."); border-radius: 18px; margin: 15px 20px 30px 20px;  min-height: 480px; max-width: 1200px; min-width: 500px; z-index: 1; height: -webkit-fill-available;'>
					<img style='position: absolute; bottom: 20px; left:20px; max-width:400px; max-height:200px;' src='".$myquiz['logo']."'>
	        		<div style='margin-left: 50%; font-size: 25px; padding: 10px; background: #ffffffb5; height: -webkit-fill-available; border-radius: 0 18px 18px 0;'>
	        			<h3>".$other_setting['description']."</h3>
	        			<div style='position: absolute;  bottom: 20px;  right: 20px;' class='fwd hover-shadow' onclick='get_start();'> Начать >> </div>
	        		</div>
	        	</div>";
 	$main_text .= "<div class='callback'>
          <div class='cb-title'>
              Ответьте на несколько вопросов:
          </div>
	        <form action='".DIR_DOMAIN."send_quiz.php' class='row form-block' method='post' enctype='multipart/form-data' style='margin: auto;'>";
	            if (isset($questions)) { 
	              $quest_num = 0; 
	                foreach ($questions as $question) {
	                    $main_text .= "<div id='quizblock". $quest_num."' class='hidden-block'>";
	                        $main_text .= "<h4 class='cb-title'>". $question['name']. "</h4>";
	                        $main_text .= "<div class='col-sm-11 col-xs-12' style='margin-bottom: 300px;'>";

	                        $answer_num = 0; 
	                        foreach ($question['answers'] as $answer) {
	                        	$main_text .= "<div class='answer-row mob_100'";
	                        	if ($answer['type'] == 1){$main_text .= "style='width:100%;'";} 
	                        	$main_text .= "onclick='change_buttons(".$answer['next'].",".$quest_num.");'>";

	                          	$main_text .= "<span>";

                            	if ($answer['type'] == 0){
                              	$main_text .= "<input id='answer".$quest_num."-".$answer_num."' type='radio' name='radio_".$quest_num."' value='".$answer['name']."' />";
                              	$main_text .= "<label for='answer".$quest_num."-".$answer_num."' >".$answer['name']."</label>";
                              }

                              if ($answer['type'] == 2){
                              	$main_text .= "<input id='checkbox_answer".$quest_num."-".$answer_num."' type='checkbox' name='checkbox_".$quest_num."' value='".$answer['name']."'  style='width: 20%;' />";
                              	$main_text .= "<label for='checkbox_answer".$quest_num."-".$answer_num."' style='top:-9px;'>".$answer['name']."</label>";
                              }

                            $main_text .= "</span>";

                            if ($answer['type'] == 1){
                            	$main_text .= "<label for='text_answer".$quest_num."-".$answer_num."' >".$answer['name']."</label>";
                              	$main_text .= "<input id='text_answer".$quest_num."-".$answer_num."' type='text' name='text_".$quest_num."' value='' />";
                              }

	                            $main_text .= "</div>";
	                        	$answer_num++;    
	                        }
	                        $main_text .= "</div>";
	                        $main_text .= "<div class='fwd hover-shadow' onclick='red_back();'>Далее >></div>";
	                        if ($quest_num>0){
	                        	$main_text .= "<div class='bck hover-shadow'><< Назад</div>";
	                         }
	                    $main_text .= "</div>";
	                	$quest_num++;
	                }
	            }
	            $main_text .= "<div id='quizblock".$quest_num."' class='hidden-block'>";
	            $main_text .= "<div class='col-sm-11 col-xs-12'>";
	            $main_text .= "<input type='text' name='quiz_name' placeholder='Ф.И.О.' autocomplete='off' value='' class='input-name' required='required' pattern='.{3,}' /></div><div class='col-sm-11 col-xs-12'><input type='tel' name='quiz_phone' placeholder='Ваш телефон' autocomplete='off' value='' class='input-phone' required='required'/></div><div class='col-sm-11 col-xs-12'><input type='text' name='quiz_id' value='".$myquiz['quiz_id']."' style='display: none;'/><input type='text' name='redirect' value='".$other_setting['redirect']."' style='display: none;'/><input type='submit' class='fcallback' value='Отправить' /></div><p style='margin-bottom: 50px;'>*Нажимая отправить вы соглашаетесь с условиями конкурса и <a  href='".$other_setting['policy']."' target='blank' style='color:black;'>политикой обработки данных</a></p><div class='bck hover-shadow'><< Назад</div></div></form><div class='ok-message'>";
	            $main_text .= "</div></div>";

	    $main_text .= "<script type='text/javascript'>";
	   		$main_text .= "document.querySelector('#quizblock0').style.display='block';";
	     	$main_text .= "function next(next, now){";
	        	$main_text .= "let next_id = '#quizblock'+next;";
	        	$main_text .= "let now_id = '#quizblock'+now;";
	        	$main_text .= "document.querySelector(now_id).style.display = 'none';";
	        	$main_text .= "document.querySelector(next_id).style.display = 'block';";
	        	$main_text .= "document.querySelector('#q-row > div.callback').classList.add('fade-in-block');";
	        	$main_text .= "document.querySelector('#q-row > div.callback > div.cb-title').classList.remove('red-block');";
	        $main_text .= "}";
	        $main_text .= "function change_buttons(next, now){";
		        $main_text .= "document.querySelector('#q-row > div.callback').classList.remove('fade-in-block');";
		        $main_text .= "document.querySelector('#q-row > div.callback > div.cb-title').classList.remove('red-block');";
		        $main_text .= "let now_id = '#quizblock'+now;";
		        $main_text .= "let func_next = 'next(' + next + ', ' + now + ');';";
		        $main_text .= "try {document.querySelector(now_id+' > div.fwd').removeAttribute('onclick');}catch(err){console.log(err);}";
		        $main_text .= "try {document.querySelector(now_id+' > div.fwd').setAttribute('onclick', func_next);} catch (err) {console.log(err);}";
		        $main_text .= "let next_id = '#quizblock'+next;";
		        $main_text .= "let func_back = 'next(' + now + ', ' + next + ');';";
		        $main_text .= "try {document.querySelector(next_id+' > div.bck').removeAttribute('onclick');} catch (err) {console.log(err);}"; 
		        $main_text .= "try {document.querySelector(next_id+' > div.bck').setAttribute('onclick', func_back);} catch (err) {console.log(err);}";
	        $main_text .= "}";
	        $main_text .= "function red_back(){";
	        	$main_text .= "document.querySelector('#q-row > div.callback > div.cb-title').classList.add('red-block');";
	        $main_text .= "}";
	        $main_text .= "function get_start(){";
	        	$main_text .= "document.querySelector('#start').classList.add('fade-out-block');";
	        $main_text .= "}";
	    $main_text .= "</script>";

	    $main_text .= "<script type='text/javascript'>";
		$main_text .= "let my_url = '".str_replace("http://", "", str_replace("https://", "", $myquiz['domain']))."';
			let с_url = document.location.hostname;
			if (my_url === с_url){ 
				my_url = с_url; 
				console.log(с_url);
			} else {
				document.querySelector('#q-row').remove();
				$('#q-row').remove();
			}
		</script>"; 
	$main_text .= "</div>";

}

$filename = __DIR__.'/quiz/'.$quiz_id.'.html';

$fh = fopen($filename, 'w+');

fwrite($fh, $main_text);

fclose($fh);
header('Location: '.DIR_DOMAIN.'edit_quiz.php/?quiz_id='.$quiz_id);
exit();

?>