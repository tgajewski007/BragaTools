<?php
namespace braga\tools\tools;
/**
 * Created on 21 paź 2013 20:31:56
 * @author Tomasz Gajewski
 */
class FileInfo
{
	// -------------------------------------------------------------------------
	protected $fileName;
	protected $folder;
	protected $pathFileName;
	protected $fileSize;
	protected $link;
	// -------------------------------------------------------------------------
	public static function getStandardFileName($fileName)
	{
		$retval = plCharset($fileName);
		if(strlen($retval) < 5)
		{
			$retval = getRandomStringLetterOnly(8) . $retval;
		}
		return $retval;
	}
	// -------------------------------------------------------------------------
	public static function scan($folder, $urlPrefix)
	{
		$dir = scandir($folder);
		$retval = array();
		foreach($dir as $fileName)
		{
			if(is_file($folder . $fileName))
			{
				$retval[] = new static($folder, $fileName, $urlPrefix);
			}
		}
		return $retval;
	}
	// -------------------------------------------------------------------------
	public function __construct($folder, $fileName, $urlPrefix)
	{
		$this->setFileName(basename($fileName));
		$this->setFolder($folder);
		$this->setPathFileName($this->getFolder() . $this->getFileName());
		$this->setFileSize(filesize($this->getPathFileName()));
		$this->setLink($urlPrefix . "/" . $this->getFileName());
	}
	// -------------------------------------------------------------------------
	public function getFileSizeFormated()
	{
		return formatBytes($this->getFileSize());
	}
	// -------------------------------------------------------------------------
	public function setFileName($fileName)
	{
		$this->fileName = $fileName;
	}
	// -------------------------------------------------------------------------
	public function setFolder($folder)
	{
		$this->folder = $folder;
	}
	// -------------------------------------------------------------------------
	public function setPathFileName($pathFileName)
	{
		$this->pathFileName = $pathFileName;
	}
	// -------------------------------------------------------------------------
	public function setFileSize($fileSize)
	{
		$this->fileSize = $fileSize;
	}
	// -------------------------------------------------------------------------
	public function setLink($link)
	{
		$this->link = $link;
	}
	// -------------------------------------------------------------------------
	public function getFileName()
	{
		return $this->fileName;
	}
	// -------------------------------------------------------------------------
	public function getFolder()
	{
		return $this->folder;
	}
	// -------------------------------------------------------------------------
	public function getPathFileName()
	{
		return $this->pathFileName;
	}
	// -------------------------------------------------------------------------
	public function getFileSize()
	{
		return $this->fileSize;
	}
	// -------------------------------------------------------------------------
	public function getLink()
	{
		return $this->link;
	}
	// -------------------------------------------------------------------------
}
?>