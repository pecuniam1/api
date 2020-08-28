<?php
namespace src\controllers;

class BaseController //extends Database
{
	public static function createView($view)
	{
		$info = static::getDetails();
		require_once(APP_ROOT . '\views\\' . $view . '.php');
	}
}
