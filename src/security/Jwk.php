<?php
namespace braga\tools\security;
use CoderCat\JWKToPEM\JWKConverter;
use braga\tools\exception\NoRecordFoundException;
use braga\tools\tools\JsonSerializer;
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
		$tmp = JsonSerializer::arrayFromJson($this->jwkString, JwkCertsResponse::class);
		/**
		 * @var JwkCertsResponse $tmp
		 */
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
			return $jwkConverter->toPEM($this->jwk[$kid]);
		}
		else
		{
			throw new NoRecordFoundException("BR:91601 Key not found", 91601);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
}
