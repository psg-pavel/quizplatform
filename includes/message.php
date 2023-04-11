<div id="message_popup" style="border:1px solid gray; margin: 5px; padding: 10px; border-radius: 10px; width: 550px; position: fixed; top: 200px; left: 60%; display: block;">
	<h3>Сделать рассылку:</h3>
	<form method="POST">
		<label>Введите текст:</label>

		<!-- смайлики -->
		<div style="cursor: pointer; margin-bottom: -20px; position: relative; margin-left: 294px;" onclick="openSmile('smile-popup')">&#128515;</div>
		<div id="smile-popup" style="position: absolute; margin-left: 295px; margin-top: -50px; width: 200px; height: 100px; z-index: 20;   border-radius: 15px;  background: white;  display: none;">
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%83', 'txt-popup')">&#128512;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%83', 'txt-popup')">&#128515;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%84', 'txt-popup')">&#128516;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%81', 'txt-popup')">&#128513;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%86', 'txt-popup')">&#128518;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%85', 'txt-popup')">&#128517;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%82', 'txt-popup')">&#128514;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%83', 'txt-popup')">&#128578;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%91%87', 'txt-popup>')">&#128071;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%89', 'txt-popup')">&#128521;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%8A', 'txt-popup')">&#128522;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%8D', 'txt-popup')">&#128525;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%8B', 'txt-popup')">&#128523;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%9C', 'txt-popup')">&#128540;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%B5', 'txt-popup')">&#129322;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%92%B5', 'txt-popup')">&#128181;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%91%8B', 'txt-popup')">&#128075;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%E2%9C%8B', 'txt-popup')">&#128400;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%91%8D', 'txt-popup')">&#128077;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%99%8F', 'txt-popup')">&#128591;</div>
			<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%91%8C', 'txt-popup')">&#129309;</div>
		</div>
		<!-- END смайлики -->

		<textarea  id='txt-popup' type="text" name="text_mess" value="" style="width:50%;height:60px;" onclick="closesmile('smile-popup')"></textarea>
		<input id='time' type="submit" name="" value="Отправить">
	</form>
</div>