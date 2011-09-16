<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends Controller_Master {

	public function action_index()
	{
		$this->content = "Hello world!";
	}

}