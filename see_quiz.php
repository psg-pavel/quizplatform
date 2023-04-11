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

/*----------------------------- вопросы - ответы настройки------------------------------*/

$questions = get_questions($myquiz['setting']);
$other_setting = $myquiz['other_setting'];
$other_setting['redirect'] = $other_setting['redirect'] ? $other_setting['redirect'] : $myquiz['domain'];

/*-----------------------------END вопросы - ответы------------------------------*/

?>


<html>
	<head> 
		<meta charset="utf-8" content="text/html" />
		<title> <?echo 'Квиз '.$myquiz['name'];?> </title>
		<link rel="stylesheet" type="text/css" href="/styles.css" />
		<style type="text/css">
			#q-row{
				width: 80%;
				max-width: 1200px;
				min-width: 500px;
				position: absolute;
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
			  background: <?echo $other_setting['color']; ?>;
			  color: <?echo $other_setting['text_color']; ?>;
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
			    background: <?echo $other_setting['color']; ?>;
			    color: <?echo $other_setting['text_color']; ?>;
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
		</style>
	</head>

	<body>
		<? include_once(DIR_INCLUDES.'header.php'); ?>
		<? include_once(DIR_INCLUDES.'menu.php'); ?>
		<? include_once(DIR_INCLUDES.'support.php'); ?>

		<div class="center" style="margin-bottom: 100px;">

			<a href="<?echo DIR_DOMAIN.'edit_quiz.php/?quiz_id='.$quiz_id;?>">РЕДАКТИРОВАТЬ</a>
			

			<? if ($is_my){?>

			<div id="q-row">

				<div id="start" style="background-size: cover !important; background-position: center; position: absolute; width: 97%; background: url(<?echo $myquiz['background'];?>); border-radius: 18px; margin: 15px 20px 30px 20px;  min-height: 480px; max-width: 1200px; min-width: 500px; z-index: 1; height: -webkit-fill-available;">
					<img style="position: absolute; bottom: 20px; left:20px; max-width:400px; max-height:200px;" src="<?echo $myquiz['logo'];?>">
	        		<div style="margin-left: 50%; font-size: 25px; padding: 10px; background: #ffffffb5; height: -webkit-fill-available; border-radius: 0 18px 18px 0;">
	        			<h3><?echo $other_setting['description'];?></h3>

	        			<div style="position: absolute;  bottom: 20px;  right: 20px;" class="fwd hover-shadow" onclick="get_start();"> Начать >> </div>

	        		</div>
	        	</div>
    
		        <div class="callback">

		        
		            <? if ($issetcheck != 0){ ?>
		                <div id="message" style="background: <? if ($issetcheck == 1){echo '#f3b8b0';}else{echo '#60ad62';}?>; border-radius: 18px; width:100%; padding-top: 18px 10px 5px 10px; margin-bottom: 30px;">
		                    <p style="text-align: center; color:white;">
		                        Спасибо за ответы!
		                    </p>
		                </div>
		            <? } else {?>

		              <div class="cb-title">
		                  Ответьте на несколько вопросов:
		              </div>

		            <? } ?>
		            
		            <form action="<?echo DIR_DOMAIN;?>send_quiz.php" class="row form-block" method="post" enctype="multipart/form-data" style="margin: auto;">
		                <?php if (isset($questions)) { ?>
		                  <?php $quest_num = 0; ?>
		                    <?php foreach ($questions as $question) {?>
		                        <div id="quizblock<?php echo $quest_num;?>" class="hidden-block">
		                            <h4 class="cb-title"><?php echo $question['name']; ?></h4>
		                            <div class="col-sm-11 col-xs-12" style="margin-bottom: 300px;">

		                            <?php $answer_num = 0; ?>
		                            <?php foreach ($question['answers'] as $answer) {?>
		                              <div class="answer-row mob_100" <?if ($answer['type'] == 1){ echo 'style="width:100%;"';} ?> onclick="change_buttons(<?php echo $answer['next'];?>, <?php echo $quest_num;?>);">
		                              <span>

		                              	<?if ($answer['type'] == 0){?>
		                                <input id="answer<?php echo $quest_num.'-'.$answer_num; ?>" type="radio" name="radio_<?echo $quest_num; ?>" value="<?echo $answer['name']; ?>" />
		                                <label for="answer<?php echo $quest_num.'-'.$answer_num; ?>" ><?echo $answer['name']; ?></label>
		                                <?}?>

		                                <?if ($answer['type'] == 2){?>
		                                <input id="checkbox_answer<?php echo $quest_num.'-'.$answer_num; ?>" type="checkbox" name="checkbox_<?echo $quest_num; ?>" value=""  style="width: 20%;"/>
		                                <label for="checkbox_answer<?php echo $quest_num.'-'.$answer_num; ?>" style="top:-9px;"><?echo $answer['name']; ?></label>
		                                <?}?>

		                              </span>
		                                
		                                <?if ($answer['type'] == 1){?>
		                                <label for="text_answer<?php echo $quest_num.'-'.$answer_num; ?>" ><?echo $answer['name']; ?></label>
		                                <input id="text_answer<?php echo $quest_num.'-'.$answer_num; ?>" type="text" name="text_<?echo $quest_num; ?>" value="" />
		                                <?}?>
		                                
		                              </div>
		                            <?php $answer_num++; ?>    
		                            <?}?>

		                            </div>

		                            <!-- кнопки -->
		                              <div class="fwd hover-shadow" onclick="red_back();"> Далее >> </div>
		                              <?if ($quest_num>0){?>
		                              <div class="bck hover-shadow" > << Назад </div>
		                              <?}?>
		                            <!-- END кнопки -->

		                        </div>
		                      <?php $quest_num++; ?>
		                    <?}?>
		                <?}?>
		              <div id="quizblock<?php echo $quest_num;?>"  class="hidden-block">
		                <div class="col-sm-11 col-xs-12">
		                    <input type="text" name="quiz_name" placeholder="Ф.И.О." autocomplete="off" value="" class="input-name" required="required" pattern=".{3,}" />
		                </div>

		                <div class="col-sm-11 col-xs-12">
		                    <input type="tel" name="quiz_phone" placeholder="Ваш телефон" autocomplete="off" value="" class="input-phone" required="required" />
		                </div>
		              
		                <div class="col-sm-11 col-xs-12">
		                    <input type="text" name="quiz_id" value="<?echo $myquiz['quiz_id'];?>" style="display: none;"/>
		                    <input type="text" name="redirect" value="<?echo $other_setting['redirect'];?>" style="display: none;"/>
		                    <input type="submit" class="fcallback" value="Отправить" />

		                </div>
		                <p style="margin-bottom: 50px;">*Нажимая отправить вы соглашаетесь с условиями конкурса и <a href="<?echo $other_setting['policy'];?>" target="blank" style="color:black;">политикой обработки данных</a></p>
		                <div class="bck hover-shadow" > << Назад </div>
		              </div>
		            </form>
		            <div class="ok-message"></div>
		        </div>

			    <script type="text/javascript">
			      document.querySelector("#quizblock0").style.display = 'block';
			      function next(next, now){

			        let next_id = '#quizblock'+next;
			        let now_id = '#quizblock'+now;
			        document.querySelector(now_id).style.display = 'none';
			        document.querySelector(next_id).style.display = 'block';
			        //фон:
			        document.querySelector("#q-row > div.callback").classList.add("fade-in-block");
			        document.querySelector("#q-row > div.callback > div.cb-title").classList.remove("red-block");

			      }
			      function change_buttons(next, now){

			         document.querySelector("#q-row > div.callback").classList.remove("fade-in-block");
			         document.querySelector("#q-row > div.callback > div.cb-title").classList.remove("red-block");

			        let now_id = '#quizblock'+now;
			        let func_next = 'next(' + next + ', ' + now + ');';
			        try {
			          document.querySelector(now_id+" > div.fwd").removeAttribute("onclick");
			        } catch (err) {
			          console.log(err);
			        }  
			        try {
			          document.querySelector(now_id+" > div.fwd").setAttribute('onclick', func_next);
			        } catch (err) {
			          console.log(err);
			        }
			        


			        let next_id = '#quizblock'+next;
			        let func_back = 'next(' + now + ', ' + next + ');';
			        try {
			          document.querySelector(next_id+" > div.bck").removeAttribute("onclick");
			        } catch (err) {
			          console.log(err);
			        } 
			        try {
			          document.querySelector(next_id+" > div.bck").setAttribute('onclick', func_back);
			        } catch (err) {
			          console.log(err);
			        }
			      }


			      function red_back(){
			        document.querySelector("#q-row > div.callback > div.cb-title").classList.add("red-block");
			      }

			      function get_start(){
			      	document.querySelector('#start').classList.add('fade-out-block');
			      }
			      
			    </script>
			</div>

			<?}?>
		</div>
		
		<? include_once(DIR_INCLUDES.'footer.php'); ?>

	</body>
</html>