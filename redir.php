<?php

include_once(__DIR__.'/library.php');
if ($_GET['back']){
	header('Location: ' . DIR_DOMAIN . $_GET['back'].'.php');
} else {
	header('Location: ' . DIR_DOMAIN);
}

?>