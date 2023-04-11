    <div class='attention-popup' id='groups_popup' style='display: none;'>
       <div class='login-popup-content' style="background-image: url(<?echo DIR_IMAGES;?>%D0%A1%D0%BA%D1%80%D0%B8%D0%BD%D1%88%D0%BE%D1%82%2006-04-2022%20200129.jpg) !important;">
       		<ul class='zone'>
       			<?
       				for ($c=0; $c<6; $c++){
       					echo "<li class ='group_str' id='str-".$c."' draggable='true'>&#9617; &#160".$standart[$c]."</li>";
       				}
       			?>
       		</ul>
        <button onclick="Groups_hide()">Отмена</button>&#160 &#160<button onclick="changegroups()">Применить</button>
      </div>
    </div> 