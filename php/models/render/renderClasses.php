<?php
Class link 
{
	public $link;
	public $name;
	public function __construct ($HREF, $CAPTION)
    {
	    $this->link = $HREF;
	    $this->name = $CAPTION;
  	}
}

Class rating
{
	public $attribute;
	public $stars;
	public $score;
	public function __construct ($attribute, $stars,$score)
    {
	    $this->attribute = $attribute;
	    $this->stars = $stars;
	    $this->score = $score;
  	}
}

Class star
{
	public $id;
	public $checkedString;
	public $value;
	public $class;
	public function __construct ($id, $checkedString,$value,$class)
    {
	    $this->id = $id;
	    $this->checkedString = $checkedString;
	    $this->value = $value;
	    $this->class = $class;
  	}
}
?>