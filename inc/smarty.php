<?php
    require_once('libs/Smarty-3.1.11/libs/Smarty.class.php');
    $smarty = new Smarty();
	$smarty->template_dir = 'templates/';
	$smarty->compile_dir = 'templates_c/';
	$smarty->left_delimiter = "{";
	$smarty->right_delimiter = "}";
?>