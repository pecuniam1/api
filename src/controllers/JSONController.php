<?php

namespace src\controllers;

class JSONController extends BaseController
{
	public static function getJSON()
	{
		//this is where you query the db
		$json = new \stdClass();
		$json->name = "Snuggles";
		$json->age = 5;
		$json->breed = "Beagle";

		return json_encode($json);
	}

	public static function setJSON()
	{
		
	}
}
