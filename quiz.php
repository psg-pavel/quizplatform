<?php
include_once(__DIR__.'/library.php');
include_once(DIR_INCLUDES.'functions.php');
include_once(DIR_AUTH.'config.php');
include('auth/check.php');

if ($_POST){
	if ($_POST['new_quiz'] && !empty($auth_login)){
		mysqli_query($conn, "INSERT INTO `quiz` (`quiz_id`, `login`, `name`, `domain`, `setting`) VALUES (NULL, '".$auth_login."', '".$_POST['quiz_name']."', '".$_POST['domain']."', NULL);");
	header('Location: ' . DIR_DOMAIN . 'redir.php/?back=quiz');
	}
}

/* получаем то, что есть */


if ($auth_login != null){
    $quiz = array();
    $select = mysqli_query($conn, "SELECT * FROM quiz qz WHERE qz.login='".$auth_login."' ORDER BY qz.quiz_id ASC");
    if ($select){
    	while ($result = mysqli_fetch_array($select)) { 
    		$quiz[] = array(
    			'quiz_id'	=> $result['quiz_id'],
    			'domain'	=> $result['domain'],
    			'name'		=> $result['name'],
    			'edit_href' => DIR_DOMAIN.'edit_quiz.php/?quiz_id='.$result['quiz_id'],
    			'stat_href' => DIR_DOMAIN.'stat_quiz.php/?quiz_id='.$result['quiz_id'],
    			'see_href'  => DIR_DOMAIN.'see_quiz.php/?quiz_id='.$result['quiz_id']
    		);
    	} 
    }
}
error_reporting(0);

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
		<? if ($auth_login) {?>
		<div class="center">
			<h3>Мои квизы:</h3>
			<?if ($auth_role == '1') {
			    include_once(DIR_SETTING.'tabs1.php'); 
			} else {
				foreach ($quiz as $q){?>
					<div style="background: white; padding: 5px; margin: 3px; min-height: 16px;">
						<div style="min-width: 150px; float: left;"><?echo $q['name'];?>&nbsp;</div>
						<div style="min-width: 150px; float: left;">
							<a href='<?echo $q['edit_href'];?>'>&nbsp;
							Редактировать</a>
						</div>
						<div style="min-width: 150px; float: left;">
							<a href='<?echo $q['see_href'];?>'>&nbsp;
							Посмотреть квиз</a>
						</div>
						<div style="min-width: 150px; float: left;">
							<a href='<?echo $q['stat_href'];?>'>&nbsp;
							Посетители квиза</a>
						</div>	
					</div>
			<?	}
			}   
			?>
			<div>
				<p style="cursor:pointer;" onclick="addBot()">+Добавить</p>
				<div id="add_bot"  hidden>
					<form method="POST">
						<input type="text" name="new_quiz" value="1" hidden>
						<input  class='time' type="text" name="quiz_name" placeholder="Название квиза"  value="" required>
						<input  class='time' type="text" name="domain" placeholder="Домен размещения"  value="" required>
						<input class='time' type="submit" name="Добавить">
					</form>
				</div>
			</div>
		</div>
		<?}?>
		<? include_once(DIR_INCLUDES.'footer.php'); ?>

	<script type="text/javascript">
		function addBot(){
			document.getElementById('add_bot').hidden = false;
		}

	</script>
	</body>
</html>