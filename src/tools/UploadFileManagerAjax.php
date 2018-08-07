<?php
namespace braga\tools\tools;
use braga\tools\excepion\UploadMangerException;

/**
 * Created on 25.12.2016 14:26:14
 * error prefix
 * @author Tomasz Gajewski
 * @package
 *
 */
class UploadFileManagerAjax extends UploadFileManager
{
	// -------------------------------------------------------------------------
	private $inputFromPost;
	// -------------------------------------------------------------------------
	/**
	 * @param string $postName
	 * @throws \Exception
	 * @return \braga\tools\tools\UploadFileManager
	 */
	public static function getFileContent($postName)
	{
		$retval = new self();
		$retval->setInputFromPost(PostChecker::get($postName));
		if(mb_strlen($retval->getInputFromPost()) < 8)
		{
			throw new UploadMangerException("BT:10004 File not transmited", 10004);
		}
		$retval->decodeAndSetContent();
		$retval->decodeAndSetMime();
		$retval->setSize(strlen($retval->getContent()));
		$retval->setOrginalFilename(PostChecker::get($postName . "_file_name"));
		$retval->clearMem();
		return $retval;
	}
	// -------------------------------------------------------------------------
	private function decodeAndSetContent()
	{
		$startPos = strpos($this->getInputFromPost(), ';base64,');
		if(false === $startPos)
		{
			throw new \Exception("BT::10005 Transmision error, see log");
		}
		$startPos += +strlen(';base64,');
		$contentBase64 = substr($this->getInputFromPost(), $startPos);
		$content = base64_decode($contentBase64);
		if(false === $content)
		{
			throw new \Exception("BT::10006 Transmision error: error decode content from base64");
		}
		$this->setContent($content);
	}
	// -------------------------------------------------------------------------
	private function decodeAndSetMime()
	{
		$startPos = strpos($this->getInputFromPost(), 'data:');
		$endPos = strpos($this->getInputFromPost(), ';base64,');
		if($startPos === false || $endPos === false)
		{
			return;
		}
		$startPos += strlen('data:');
		$mime = substr($this->getInputFromPost(), $startPos, $endPos - $startPos);
		$this->setMimeType($mime);
	}
	// -------------------------------------------------------------------------
	private function getInputFromPost()
	{
		return $this->inputFromPost;
	}
	// -------------------------------------------------------------------------
	private function setInputFromPost($inputFromPost)
	{
		$this->inputFromPost = $inputFromPost;
	}
	// -------------------------------------------------------------------------
	private function clearMem()
	{
		unset($this->inputFromPost);
	}
	// -------------------------------------------------------------------------
}