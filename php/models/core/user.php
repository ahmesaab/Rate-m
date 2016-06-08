<?php
require_once 'php/data/dao.php';
require_once 'php/utils/utils.php';
require_once 'vendor/autoload.php';

class User
{
    public $name;
    public $id;
    public $token;

    public function __construct ($FacebookID, $UserName, $Token)
    {
	    $this->name = $UserName;
	    $this->id = $FacebookID;
	    $this->token = $Token;
  	}

    public function getFriends()
    {
    	$friends=[];
        $fb = Utils::FacebookObject($this->token);
        try
        {
        	$request =  $fb->request('GET','/me/friends');
			$response = $fb->getClient()->sendRequest($request);
			$graphEdge = $response->getGraphEdge();
			foreach($graphEdge as $friend)
			{
				$friends[$friend->getField("id")] = $friend->getField("name");
			}
			return $friends;
        }
        catch(Exception $e)
        {
        	$friends;
        }
    }

    public function getRating($attributeName)
    {
    	return dao::getUserAttributeRatingScore($this->id,$attributeName);
    }
}
?>