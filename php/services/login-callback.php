<?php
session_start();
require_once 'php/utils/utils.php';
require_once 'php/data/dao.php';
if(utils::handelFacebookCallback())
{
	if(dao::firstTimeUser($_SESSION["facebook_user_id"]))
	{
		dao::addUserToDataStore($_SESSION["facebook_user_id"],$_SESSION["facebook_user_name"],$_SESSION['facebook_access_token']);
	}
	$callbackUrl=$_SESSION["callback_url"];
	unset($_SESSION["callback_url"]);
	header('Location: '.$callbackUrl); 
}
else
{
	echo 'An Error Occured while authentication.';
}

?>