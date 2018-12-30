<?php
use braga\tools\tools\Rrso;
use braga\tools\tools\RrsoCashFlow;
use PHPUnit\Framework\TestCase;

require_once '../vendor/autoload.php';

/**
 * Rrso test case.
 */
class RrsoTest extends TestCase
{

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp()
	{
		parent::setUp();
	}

	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown()
	{
		parent::tearDown();
	}

	/**
	 * Constructs the test case.
	 */
	public function __construct()
	{
	}

	/**
	 * Tests Rrso::szacuj()
	 */
	public function testSzacuj()
	{
		$raty = [];
		$raty[] = new RrsoCashFlow(1000, 0);
		$raty[] = new RrsoCashFlow(1150, 30);

		$val = Rrso::szacuj($raty);

		$this->assertEquals($val, 447.12);
	}
}

