<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends Main {

	public function action_index()
	{
		$this->content = new Element("p", "Hello World!");
	}

}