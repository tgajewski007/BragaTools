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
	protected $fileSizeFormated;
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
	public static function scan($folder)
	{
		$dir = scandir($folder);
		$retval = array();
		foreach($dir as $fileName)
		{
			if(is_file($folder . $fileName))
			{
				$retval[] = new static($folder, $fileName);
			}
		}
		return $retval;
	}
	// -------------------------------------------------------------------------
	public function __construct($folder, $fileName)
	{
		$this->setFileName(basename($fileName));
		$this->setFolder($folder);
		$this->setPathFileName($this->getFolder() . $this->getFileName());
		$this->setFileSize(filesize($this->getPathFileName()));
		$this->setFileSizeFormated($this->formatBytes($this->getFileSize()));
		$this->setLink(FRONT_BASE_URL . "download/" . $this->getFileName());
	}
	// -------------------------------------------------------------------------
	public function setFileSizeFormated($fileSizeFormated)
	{
		$this->fileSizeFormated = $fileSizeFormated;
	}
	// -------------------------------------------------------------------------
	public function getFileSizeFormated()
	{
		return $this->fileSizeFormated;
	}
	// -------------------------------------------------------------------------
	protected function formatBytes($bytes, $precision = 1)
	{
		$units = array(
						'B',
						'KiB',
						'MiB',
						'GiB',
						'TiB' );

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		$bytes /= pow(1024, $pow);
		return round($bytes, $precision) . ' ' . $units[$pow];
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