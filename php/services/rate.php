<?php
session_start();
require_once 'php/utils/utils.php';
require_once 'php/data/dao.php';

// get the parameters from URL
$rateAttribute = $_REQUEST["rateAttribute"];
$ratedUser = $_REQUEST["ratedUser"];
$rateScore = $_REQUEST["rateScore"];

if(utils::canRate($_SESSION["facebook_user_id"],$ratedUser,$_SESSION["facebook_access_token"]))
{
    if(utils::validateVote($rateScore))
    {
        $oldVoteScore = dao::addVote($_SESSION["facebook_user_id"],$ratedUser,$rateAttribute,$rateScore);
        $newAttributeScore = dao::updateUserAttributeRatting($ratedUser,$rateScore,$rateAttribute,$oldVoteScore);
        echo '200:'. $newAttributeScore;
    }
    else
    {
        echo '400:';
    }
}
else
{
    echo '403:';
}
?>