<?php
require_once 'vendor/autoload.php';
require_once 'php/models/core/user.php';
Class dao
{

	static function getAttributes()
	{
		$array=[];
		$attribute_store = new GDS\Store('Attribute');
		$attributes = $attribute_store->fetchAll();
		foreach($attributes as $att)
		{
			array_push($array, $att->name);
		}
		return $array;
	}

	static function getUserAttributeRatingScore($userID,$attributeName)
	{
		$rating_store = new GDS\Store('Rating');
		$rating = $rating_store->fetchOne("SELECT * FROM Rating WHERE userID='$userID' and attributeName = '$attributeName' ");
		if($rating)
		{
			return $rating->score;
		}
		else
		{
			return "None";
		}	
	}

	static function getUserAttributeRating($userID,$attributeName)
	{
		$rating_store = new GDS\Store('Rating');
		$rating = $rating_store->fetchOne("SELECT * FROM Rating WHERE userID='$userID' and attributeName = '$attributeName' ");
		return $rating;
	}

	static function getUserObject($Id)
	{
		$user_store = new GDS\Store('User');
		$userMap = $user_store->fetchOne("SELECT * FROM User WHERE facebook_id='$Id'");
		if($userMap)
		{
			return new User($userMap->facebook_id,$userMap->name,$userMap->facebook_token);
		}
		else
		{
			return null;
		}
	}

	static function addVote($voterID,$votedOnID,$attributeName,$voteScore)
	{
		// returns old vote if vote was updated and null if new vote.
		$vote = self::getVote($voterID,$votedOnID,$attributeName);
		$vote_store = new GDS\Store('Vote');
		if($vote!=null)
		{
			//Vote already exist, update it
			$oldVote = $vote->voteScore;
			$vote->voteScore = $voteScore;
			$vote_store->upsert($vote);
			return $oldVote;
		}
		else
		{
			// Build a new vote entity
			$vote = new GDS\Entity();
			$vote->voterID = $voterID;
			$vote->votedOnID = $votedOnID;
			$vote->attributeName = $attributeName;
			$vote->voteScore = $voteScore;
			$vote_store->upsert($vote);
			return null;
		}
	}

	static function getVote($voterID,$votedOnID,$attributeName)
	{
		$vote_store = new GDS\Store('Vote');
		$voteMap = $vote_store->fetchOne("SELECT * FROM Vote WHERE voterID='$voterID' and votedOnID = '$votedOnID' and attributeName = '$attributeName'");
		return $voteMap;
	}

	static function updateUserAttributeRatting($userID,$voteScore,$attributeName,$oldVoteScore)
	{
		//Update user Ratting after adding the vote to the current rating and return this new rating
		//STEPS:
		/*
			1. Get oldRatting and totalCount of this userID for this attributeName from DB
			2. sum = totalCount * oldRatting
			3. newRatting = ((totalCount * oldRatting) + voteScore) / (totalCount +1)
			4. Update Ratting with the new Ratting
		*/
		$rating = self::getUserAttributeRating($userID,$attributeName);
		if(!isset($rating))
		{
			$rating=self::addRating($userID,$attributeName);
		}
		if(is_null($oldVoteScore))
		{
			$rating->score = (($rating->totalCount * $rating->score) + $voteScore) / ($rating->totalCount +1);
			$rating->totalCount = $rating->totalCount + 1;
		}
		else
		{
			$rating->score = $rating->score + (($voteScore-$oldVoteScore)/$rating->totalCount);
		}
		$rating_store = new GDS\Store('Rating');
		$rating_store->upsert($rating);
		return $rating->score;
	}

	static function addRating($userID,$attributeName)
	{
		// Build a new entity
		$rating = new GDS\Entity();
		$rating->userID = $userID;
		$rating->attributeName = $attributeName;
		$rating->totalCount = 0;
		$rating->score = 0;
		// Write it to Datastore
		$rating_store = new GDS\Store('Rating');
		$rating_store->upsert($rating);
		return $rating;
	}

	static function getUsersArray()
	{
		$array=[];
		$user_store = new GDS\Store('User');
		$users = $user_store->fetchAll();
		foreach($users as $user)
		{
			$array[$user->facebook_id] = $user->name;
		}
		return $array;
	}

	static function firstTimeUser($userID)
	{
		$user_store = new GDS\Store('User');
		$this_user = $user_store->fetchOne("SELECT * FROM User WHERE facebook_id = '$userID'");
		if($this_user)
			return false;
		else
			return true;
	}

	static function addUserToDataStore($userID,$userName,$token)
	{
		// Build a new entity
		$new_user = new GDS\Entity();
		$new_user->name = $userName;
		$new_user->facebook_id = $userID;
		$new_user->facebook_token = $token;

		// Write it to Datastore
		$user_store = new GDS\Store('User');
		$user_store->upsert($new_user);
	}

	static function addAttributeToDataStore($name)
	{
		// Build a new entity
		$new_attribute = new GDS\Entity();
		$new_attribute->name = $name;

		// Write it to Datastore
		$attribute_store = new GDS\Store('Attribute');
		$attribute_store->upsert($new_attribute);
	}
}
?>