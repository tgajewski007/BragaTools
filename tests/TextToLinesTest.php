<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class TextToLinesTest extends TestCase
{
	public function testSplitsLf(): void
	{
		$text = "a\nb\nc";
		$this->assertSame([ 'a', 'b', 'c' ], textToLines($text));
	}
	public function testSplitsCrlf(): void
	{
		$text = "a\r\nb\r\nc";
		$this->assertSame([ 'a', 'b', 'c' ], textToLines($text));
	}
	public function testSplitsCr(): void
	{
		$text = "a\rb\rc";
		$this->assertSame([ 'a', 'b', 'c' ], textToLines($text));
	}
	public function testTrimsAndRemovesEmptyByDefault(): void
	{
		$text = "  a \n\n  b  \n   \n c ";
		$this->assertSame([ 'a', 'b', 'c' ], textToLines($text));
	}
	public function testKeepsEmptyLinesWhenConfigured(): void
	{
		$text = "a\n\nb\n";
		// trimLines=true, removeEmpty=false => puste linie zostają, a ostatnia linia po \n też jest pusta
		$this->assertSame([ 'a', '', 'b', '' ], textToLines($text, true, false));
	}
	public function testNoTrimWhenConfigured(): void
	{
		$text = " a \n b ";
		$this->assertSame([ ' a ', ' b ' ], textToLines($text, false, true));
	}
	public function testRemovesBom(): void
	{
		$text = "\u{FEFF}a\nb";
		$this->assertSame([ 'a', 'b' ], textToLines($text));
	}
	public function testEmptyStringReturnsEmptyArrayByDefault(): void
	{
		$this->assertSame([], textToLines(''));
	}
	public function testEmptyStringReturnsSingleEmptyLineWhenKeepingEmpty(): void
	{
		$this->assertSame([ '' ], textToLines('', true, false));
	}
}
