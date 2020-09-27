<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ConstantsTest extends TestCase
{
	public function testGetWebsiteName() : void
	{
		$this->assertEquals('JoeKellyOnline', Constants::getWebsiteName());
	}
}