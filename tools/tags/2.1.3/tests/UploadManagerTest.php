<?php
use braga\tools\tools\UploadFileManager;

/**
 * Created on 25.06.2017 14:51:20
 * @author Tomasz Gajewski
 * package frontoffice
 * error prefix
 */
class UploadManagerTest extends PHPUnit_Framework_TestCase
{
	// -------------------------------------------------------------------------
	function testFileFlated()
	{
		$f = new UploadFileManager();
		$f->setOrginalFilename("jeden dwa trzy.pdf");
		$this->assertEquals("jeden-dwa-trzy.pdf", $f->getOrginalFilenameFlated());
		$f->setOrginalFilename("jeden_dwa trzy.pdf");
		$this->assertEquals("jedendwa-trzy.pdf", $f->getOrginalFilenameFlated());
		$f->setOrginalFilename("jeden_dwa trzy.pdfasd");
		$this->assertEquals("jedendwa-trzy.pdfasd", $f->getOrginalFilenameFlated());
	}
	// -------------------------------------------------------------------------
}