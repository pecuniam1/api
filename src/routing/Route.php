<?php

namespace src\routing;

/**
 * The route class provides routing.
 */
class Route
{

	private static $validRoutes = array();

	public static function set($route, $function)
	{
		self::$validRoutes[] = $route;
		if ($_GET['url'] == $route) {
			$function->__invoke();
		}
	}
}
