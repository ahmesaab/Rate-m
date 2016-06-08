<?php
require_once 'vendor/autoload.php';
Class Utils
{

	public static function FacebookObject($token='')
	{
		$fb = new Facebook\Facebook([
		  'app_id' => '455942167864223',
		  'app_secret' => '136b8a1ff6fee455190a2f9d87298eb3',
		  'default_graph_version' => 'v2.5',
		]);
		if($token!='')
		{
			$fb->setDefaultAccessToken($token);
		}
		return $fb;
	}

	public static function validateVote($voteScore)
	{
		try
		{
			if($voteScore<=5 && $voteScore>0)
			{
				return true;
			}
			else
				return false;
		}
		catch(Exception $e)
		{
			return false;
		}
	}

	public static function getfacebookLoginUrl()
	{
		$fb = self::FacebookObject();
		$helper = $fb->getRedirectLoginHelper();
		$permissions = ['email', 'user_likes','user_friends']; // optional
		$loginUrl = $helper->getLoginUrl('http://'.$_SERVER['SERVER_NAME'].'/login-callback', $permissions);
		return($loginUrl);
	}

	public static function getfacebookLogoutUrl()
	{
		return('/logout');
	}

	public static function canRate($loggedID,$userID,$loggedToken)
    {
    	if($loggedToken=='')
    	{
    		return false;
    	}
		$fb = self::FacebookObject($loggedToken);
        $request =  $fb->request('GET','/'.$loggedID.'/friends/'.$userID);
		$response = $fb->getClient()->sendRequest($request);
		$graphEdge = $response->getGraphEdge();
		foreach ($graphEdge as $graphNode)
		{
		  return true;
		}
		return false;
    }

    public static function handelFacebookCallback()
    {
    	$fb = self::FacebookObject();
    	$helper = $fb->getRedirectLoginHelper();
		try
		{
  			$accessToken = $helper->getAccessToken();
		}
		catch(Facebook\Exceptions\FacebookResponseException $e)
		{
		  // When Graph returns an error
		  echo 'Graph returned an error: ' . $e->getMessage();
		  return false;
		}
		catch(Facebook\Exceptions\FacebookSDKException $e)
		{
		  // When validation fails or other local issues
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  return false;
		}

		if (isset($accessToken))
		{
			// Logged in!
			$fb->setDefaultAccessToken($accessToken);
			try
			{

			  //Make API call with the accessToken
			  $response = $fb->get('/me');
			  $userNode = $response->getGraphUser();

			  //Save userID & access token to session
			  $_SESSION["facebook_user_id"] = $userNode->getId();
			  $_SESSION["facebook_user_name"] = $userNode->getName();
			  $_SESSION['facebook_access_token'] = (string) $accessToken;
			  return true;

			}
			catch(Facebook\Exceptions\FacebookResponseException $e)
			{
			  // When Graph returns an error
			  echo 'Graph returned an error: ' . $e->getMessage();
			  return false;
			}
			catch(Facebook\Exceptions\FacebookSDKException $e)
			{
			  // When validation fails or other local issues
			  echo 'Facebook SDK returned an error: ' . $e->getMessage();
			  return false;
			}
    	}
    	else
    	{
    		return false;
    	}
    }
}

?>