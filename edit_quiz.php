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

/*----------------------------- вопросы - ответы------------------------------*/

$questions = get_questions($myquiz['setting']);
$other_setting = $myquiz['other_setting'];

/*-----------------------------END вопросы - ответы----------------------------*/

if ($_POST){
	if ($is_my){
		
		if ($_FILES['background']['name'] && $_FILES['background']['size'] < 3*1024*1024){
			move_uploaded_file($_FILES['background']['tmp_name'], 'images/'.$_FILES['background']['name']);
			$background = DIR_DOMAIN.'images/'.$_FILES['background']['name'];
		} else {
			$background = $myquiz['background'];
		}

		if ($_FILES['logo']['name'] && $_FILES['logo']['size'] < 3*1024*1024){
			move_uploaded_file($_FILES['logo']['tmp_name'], 'images/'.$_FILES['logo']['name']);
			$logo = DIR_DOMAIN.'images/'.$_FILES['logo']['name'];
		} else {
			$logo = $myquiz['logo'];
		}

		mysqli_query($conn, "UPDATE `quiz` SET `name`='".$_POST['name']."', `login`='".$auth_login."', `domain`='".$_POST['domain']."', `setting`='".json_encode($_POST['questions'], JSON_UNESCAPED_UNICODE)."',  `other_setting`='".json_encode($_POST['other_setting'], JSON_UNESCAPED_UNICODE)."', `background` = '".$background."', `logo` = '".$logo."' WHERE quiz_id='".$quiz_id."'");
	}

	header('Location: '.DIR_DOMAIN.'create_script.php/?quiz_id='.$quiz_id);
	exit();
}

$main_script = ('<iframe style="width:100%; min-height:870px; border:none;" src="'.DIR_DOMAIN.'quiz/'.$quiz_id.'.html"></iframe>');

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

		
		<div class="center" style="margin-bottom: 100px;">
			<a class="example" href="<?echo DIR_DOMAIN.'see_quiz.php/?quiz_id='.$quiz_id;?>">ЗАПУСТИТЬ КВИЗ >></a>
			<? if ($is_my){?>

			<?if ($auth_role == '1') {
			    include_once(DIR_SETTING.'tabs1.php'); 
			} else { ?>
				<h4>Скрипт для вставки на сайт (работает только с удаленного сервера):</h4>
				<textarea style="width:80%; margin: 10px; max-height: 30px;"><? echo $main_script; ?></textarea>

				<h3>Настройки:</h3>

				<form method="post" enctype="multipart/form-data" id="form-quiz" class="form-horizontal">
					<table style="width: -webkit-fill-available;">
						<tbody>
							<tr>
								<td class="table-title">
									<label>Название</label>
								</td>
								<td>
									<input  class='time fill' type="text" name="name" value="<?echo $myquiz['name'];?>">
								</td>
							</tr>
							<tr>
								<td class="table-title">
									<label>Страница размещения</label>
								</td>
								<td>
									<input  class='time fill' type="text" name="domain" value="<?echo $myquiz['domain'];?>">
								</td>
							</tr>
							<tr>
								<td class="table-title">
									<label>Редирект после прохождения</label>
								</td>
								<td>
									<input class="time fill" type="text" name="other_setting[redirect]" value="<?php echo $other_setting['redirect']; ?>" />
								</td>
							</tr>
							<tr>
								<td class="table-title">
									<label>Текст на обложке</label>
								</td>
								<td>
									<textarea  class="time fill" name="other_setting[description]"><?php echo $other_setting['description']; ?></textarea>
								</td>
							</tr>
							<tr>
								<td class="table-title">
									<label>Цвет кнопок (далее и назад)</label>
								</td>
								<td>
									<input class="time fill" type="color" name="other_setting[color]" value="<?php echo $other_setting['color']; ?>" style="height: 40px; width: 100px;"/>
								</td>
							</tr>
							<tr>
								<td class="table-title">
									<label>Цвет текста на кнопках</label>
								</td>
								<td>
									<select name="other_setting[text_color]" class="form-control">
										<option value="#212223" <?if ($other_setting['text_color'] == '#212223'){echo 'selected="selected"';}?>>Темный</option>
										<option value="#fff" <?if ($other_setting['text_color'] == '#fff'){echo 'selected="selected"';}?>>Светлый</option>
									</select>
									<span class="examle" style="background: <?echo $other_setting['color'];?>; color: <?echo $other_setting['text_color'];?>;">Пример кнопки >></span>
								</td>
							</tr>
							<tr>
								<td class="table-title">
									<label>Изображение для обложки (менее 3 Мб)</label>
								</td>
								<td>
									<input style="color: transparent;" class="time fill" type="file" name="background" accept="image/jpeg,image/png"/>
									<?if ($myquiz['background']){?>
									<img height="200px" src="<?echo $myquiz['background'];?>">
									<?}?>
								</td>
							</tr>
							<tr>
								<td class="table-title">
									<label>Логотип (менее 3 Мб)</label>
								</td>
								<td>
									<input style="color: transparent;" class="time fill" type="file" name="logo" accept="image/jpeg,image/png"/>
									<?if ($myquiz['logo']){?>
									<img height="50px" src="<?echo $myquiz['logo'];?>">
									<?}?>
								</td>
							</tr>
							<tr>
								<td class="table-title">
									<label>Ссылка на политику конфиденциальности</label>
								</td>
								<td>
									<input class="time fill" type="text" name="other_setting[policy]" value="<?php echo $other_setting['policy']; ?>" />
								</td>
							</tr>
						</tbody>
					</table>
					
					<input  class='time' type="submit" name="updatebot" value="Сохранить"><br><br>

				<table id="module" class="table-bordered">

					<thead>
					  <tr>
					    <td class="text-right" style="width: 5%;">Номер</td>
					    <td class="text-left" style="width: 85%;">Вопрос</td>
					    <td class="text-left"></td>
					  </tr>
					</thead>

					<tbody id="module-tbody">

						<?php $module_row = 0; ?>
						<?php foreach ($questions as $question) { ?>
							<tr id="module-row<?php echo $module_row; ?>">

								<td class="text-right">
								    <input type="text" name="questions[<?php echo $module_row; ?>][sort_order]" value="<?php echo $question['sort_order']; ?>" hidden="hidden" />
								    <?php echo $question['sort_order']; ?>
								</td>

								<td class="text-left">
								    <input class="time fill" type="text" name="questions[<?php echo $module_row; ?>][name]" value="<?php echo $question['name']; ?>" placeholder="Введите вопрос" />
								</td>

								<td class="text-left">
									<button type="button" onclick="$('#module-row<?php echo $module_row; ?>').remove(); $('#add-row<?php echo $module_row; ?>').remove();">
										удалить
									</button>
								</td>
							</tr>

							<tr id="add-row<?php echo $module_row; ?>">
								<td class="text-left"></td>
								<td colspan="2" class="text-left">
							    
							    <table id="answer<?php echo $module_row; ?>" class="table table-striped table-bordered table-hover" style="background: white;">

							        <thead>
								        <tr>
									        <td class="text-left" style="width: 60%;">Ответ</td>
									        <td class="text-left" style="width: 25;">Тип ответа</td>
									        <td class="text-right">Переход к вопросу №</td>
									        <td class="text-left"></td>
								        </tr>
							        </thead>

							        <tbody>
							        <?php $answer_row = 0; ?>
							        <?php foreach ($question['answers'] as $answer) { ?>
								        <tr id="answer_row<?php echo $module_row.'-'.$answer_row; ?>">

								            <td class="text-left">
								              <input type="text" name="questions[<?php echo $module_row; ?>][answers][<?php echo $answer_row; ?>][name]" value="<?php echo $answer['name']; ?>" class="time fill" />
								            </td>

								            <td class="text-left">
								            	<select name="questions[<?php echo $module_row; ?>][answers][<?php echo $answer_row; ?>][type]" class="form-control">
									                <option value="0" <?if ($answer['type'] == 0){?> selected="selected" <?}?> >Поставить галочку</option>
									                <option value="1" <?if ($answer['type'] == 1){?> selected="selected" <?}?> >Текстовое поле</option>
									                <option value="2" <?if ($answer['type'] == 2){?> selected="selected" <?}?> >Несколько вариантов</option>
								            	</select>
								            </td>

								            <td class="text-right">
								            	<input type="text" name="questions[<?php echo $module_row; ?>][answers][<?php echo $answer_row; ?>][next]" value="<?php echo $answer['next']; ?>" class="time fill" />
								            </td>

								            <td class="text-left">
								            	<button type="button" onclick="$('#answer_row<?php echo $module_row; ?>-<?php echo $answer_row; ?>').remove();">
								            		удалить
								            	</button>
								            </td>

								        </tr>
							        	<?php $answer_row++; ?>
							        <?php } ?>
							        </tbody>
							        <tfoot>
								        <tr>
								            <td colspan="3" class="text-left"></td>
								            <td class="text-left">
									          	<button type="button" onclick="addAnswer(<?php echo $module_row; ?>, <?php echo $answer_row; ?>);">
									          		Добавить вариант
									          	</button>
								            </td>
								        </tr>
							        </tfoot>
							    </table>
								</td>
							</tr>
							<tr  style="height: 10px;"></tr>
				    	<?php $module_row++; ?>
				    	<?php } ?>
					</tbody>
					<tfoot>
						  <tr>
						    <td colspan="2" class="text-left"></td>
						    <td class="text-left"><button type="button" onclick="addQuest();" >Добавить вопрос</button></td>
						  </tr>
					</tfoot>
				</table>
            	<input type="text" name="module_id" value="<?php echo $module_id; ?>" hidden />
            	<input style="float:right;" class='time' type="submit" name="updatequiz" value="Сохранить"><br><br>
        		</form>	
			<? } ?>
			<?} else {?>
			<h3>У вас НЕТ квизов для редактирования</h3>
		<?}?>
		</div>
		
		<? include_once(DIR_INCLUDES.'footer.php'); ?>

		<script type="text/javascript">

		    var module_row = <?php echo $module_row; ?>;

		    function addQuest() {
		      html  = '<tr id="module-row' + module_row + '">';
		      html += '  <td class="text-right"><input type="text" name="questions[' + module_row + '][sort_order]" value="' + module_row + '" required hidden/>' + module_row + '</td>';
		      html += '  <td class="text-left"><input type="text" name="questions[' + module_row + '][name]" value="" placeholder="Введите вопрос"  class="time fill"/></td>';
		      html += '  <td class="text-left"><button type="button" onclick="$(\'#module-row' + module_row + '\').remove(); $(\'#add-row' + module_row + '\').remove();">удалить вопрос</button></td>';
		      html += '</tr>';
		      $('#module-tbody').append(html);
		      module_row++;

		    }
		</script>


		<script type="text/javascript">

		    function addAnswer(question, answer_row) {

		      html  = '<tr id="answer_row' + question + '-' + answer_row + '">';
		      html += '  <td class="text-left"><input type="text" name="questions[' + question + '][answers][' + answer_row + '][name]" value=""  class="time fill"/></td>';
		      html += '  <td class="text-left"><select name="questions[' + question + '][answers][' + answer_row + '][type]" class="form-control"><option value="0" selected="selected">Поставить галочку</option><option value="1" >Текстовое поле</option><option value="2" selected="selected">Несколько вариантов</option></select></td>';
		      html += '  <td class="text-right"><input type="text" name="questions[' + question + '][answers][' + answer_row + '][next]" value="' + (question+1) + '"  class="time fill"/></td>';

		      html += '  <td class="text-right"><button type="button" onclick="$(\'#answer_row' + question + '-' + answer_row + '\').remove();">Удалить ответ</button></td>';
		      html += '</tr>';

		      let id_answ = "answer" + question;

		      $('#'+id_answ+' tbody').append(html);

		      // меняю кнопку

		      let new_fun = 'addAnswer('+question+', '+(answer_row+1)+');';

		      document.querySelector("#answer"+question+" > tfoot > tr > td.text-left > button").setAttribute("onclick", new_fun);
		      
		    }

		</script>
		<script type="text/javascript">
			function change(question, answer_row){
				let new_fun = 'addAnswer('+question+', '+(answer_row-1)+');';

		        document.querySelector("#answer"+question+" > tfoot > tr > td.text-left > button").setAttribute("onclick", new_fun);
			}
		</script>

	</body>
</html>