<?php
require_once 'php/utils/init-page.php';
require_once 'php/data/dao.php';

if(!isset($_GET['id']))
{
	echo "Who? No id param found in url, lil faggot !";
	die();
}

$userObject = dao::getUserObject($_GET['id']);
$profileName = $userObject->name;
$friends= array();
$ratings = array();

foreach (dao::getAttributes() as $attribute)
{
	$stars = array();
	$ratingScore = round($userObject->getRating($attribute)*2)/2;
	for( $i = 10; $i>0; $i-- )
	{
		$value = $i/2;
		$checkedString = '';
		if($ratingScore==$value){ $checkedString = 'checked';}
		if(is_int($value)) { $class= 'full';}
		else { $class = 'half'; }
		array_push($stars, new star('star'.$value.$attribute,$checkedString,$value,$class));
	}
	array_push($ratings,new rating($attribute,$stars,$ratingScore));
}

foreach($userObject->getFriends() as $friendID => $friendName)
{
	array_push($friends, new link('http://'.$_SERVER['SERVER_NAME'].'/profile?id='.$friendID,$friendName));
}

echo $twig->render('profile.twig', array(
	'ratings' => $ratings,
	'friends' => $friends,
	'profileName' => $profileName,
	'projectName' => "Rat'em v0.0.3",
	'navigation' => $navigation));

?>

