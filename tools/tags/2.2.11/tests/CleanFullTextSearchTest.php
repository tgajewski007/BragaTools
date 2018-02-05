<?php
/**
 * Created on 6 paź 2017 20:52:29
 * @author Tomasz Gajewski
 * package frontoffice
 * error prefix
 */
class CleanFullTextSearchTest extends PHPUnit_Framework_TestCase
{
	// -------------------------------------------------------------------------
	function testMalpa()
	{
		$txt = "@wp.pl";
		$expected = " wp pl";
		$this->assertEquals($expected, cleanFullTextSearch($txt));
	}
	// -------------------------------------------------------------------------
}