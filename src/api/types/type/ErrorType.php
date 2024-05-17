<?php
namespace braga\tools\api\types\type;
use braga\tools\exception\WithDocumentException;
use Throwable;
/**
 * Created on 22 lip 2018 16:46:50
 * error prefix
 * @author Tomasz Gajewski
 * @package
 *
 */
class ErrorType
{
	// -----------------------------------------------------------------------------------------------------------------
	public ?string $number = null;
	public ?string $description = null;
	public ?int $idBerkas = null;
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * @param Throwable $e
	 * @return ErrorType
	 */
	public static function convertFromThrowrable(Throwable $e): ErrorType
	{
		$retval = new self();
		$retval->number = $e->getCode();
		$retval->description = $e->getMessage();
		if($e instanceof WithDocumentException)
		{
			$retval->idBerkas = $e->idBerkas;
		}
		if(empty($retval->number))
		{
			preg_match('/\d+/', $e->getMessage(), $matches);
			$retval->number = $matches[0] ?? "-1";
		}
		return $retval;
	}
	// -----------------------------------------------------------------------------------------------------------------
}