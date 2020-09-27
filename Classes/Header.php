<?php
namespace Classes;
class Header
{
	/**
	 * Clean header removes an header in the buffer.
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
		header("Content-type: application/json; charset=utf-8");
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
