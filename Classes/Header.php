<?php
namespace Classes;
class Header
{
	/**
	 * Cleans the output buffer and removes any existing header.
	 * @return void
	 */
	public static function cleanHeader() : void
	{
		ob_clean();
		header_remove();
	}
	/**
	 * Adds a JSON Header.
	 * @return void
	 */
	public static function addJSONHeader() : void
	{
		self::cleanHeader();
		header("Content-Type: application/json; charset=UTF-8");
	}
	/**
	 * Adds an HTML header.
	 * @return void
	 */
	public static function addHTMLHeader() : void
	{
		self::cleanHeader();
		header("Content-Type: text/html; charset=UTF-8");
	}
}
