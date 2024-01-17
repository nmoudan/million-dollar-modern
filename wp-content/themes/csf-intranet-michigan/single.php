<?php
if(is_main_site()) {
	include (get_stylesheet_directory() . '/single-master.php');
}else{
	include (get_stylesheet_directory() . '/single-child.php');
}
?>
