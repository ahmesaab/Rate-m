<?php
require_once 'vendor/autoload.php';
require_once 'php/models/render/renderClasses.php';
require_once 'php/utils/utils.php';

//Get Session Information needed to populate the Page
session_start();
if(isset($_SESSION["facebook_user_id"]))
{
	$userID = $_SESSION["facebook_user_id"];
	$userName = $_SESSION["facebook_user_name"];
	$userToken = $_SESSION["facebook_access_token"];
	$loggingLink= utils::getfacebookLogoutUrl();
	$loggingLabel = 'Logout';
	$loggedProfileLink = '/profile?id='.$userID;
}
else
{
	$userID = "10230";
	$userName = "Visitor";
	$userToken ='';
	$loggingLink = utils::getfacebookLoginUrl();
	$loggingLabel = 'Login';
	$loggedProfileLink = $loggingLink;
}

$_SESSION["callback_url"] = $_SERVER["REQUEST_URI"];

$navigation = array(
	new link('/','Home'),
	new link($loggedProfileLink,'You'),
	new link($loggingLink,$loggingLabel));

$loader = new Twig_Loader_Filesystem('../../views');
$twig = new Twig_Environment($loader);

?>