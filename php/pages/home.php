<?php
require_once 'php/utils/init-page.php';

$params = array(
	'projectName' => "Rat'em v0.0.4",
	'navigation' => $navigation);

$page = 'home.twig';

echo $twig->render($page,$params);
?>