<?php
namespace braga\tools\security;
use braga\tools\exception\NoRecordFoundException;
use braga\tools\tools\JsonSerializer;
use CoderCat\JWKToPEM\JWKConverter;
/**
 * @author tgaje
 * error prefix BR:916
 */
class Jwk
{
	protected string $jwkString;
	/**
	 * @var JwkJsonData[]
	 */
	protected array $jwk;
	// -----------------------------------------------------------------------------------------------------------------
	function __construct(string $jwkString)
	{
		$this->jwkString = $jwkString;
		/**
		 * @var JwkCertsResponse $tmp
		 */
		$tmp = JsonSerializer::fromJson($this->jwkString, JwkCertsResponse::class);
		$this->jwk = array();
		foreach($tmp->keys as $jwk)
		{
			$this->jwk[$jwk->kid] = $jwk;
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function get(string $kid)
	{
		if(isset($this->jwk[$kid]))
		{
			$jwkConverter = new JWKConverter();
			$tmp = $this->objToArray($this->jwk[$kid]);
			return $jwkConverter->toPEM($tmp);
		}
		else
		{
			throw new NoRecordFoundException("BR:91601 Key not found", 91601);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	private function objToArray($obj)
	{
		$json = json_encode($obj, JSON_PRETTY_PRINT);
		$retval = json_decode($json, true);
		return $retval;
	}
	// -----------------------------------------------------------------------------------------------------------------
}
