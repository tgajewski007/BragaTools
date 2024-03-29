<?php
namespace braga\tools\tools;

/**
 * Created on 19-10-2011 20:27:32
 * @author Tomasz Gajewski
 * @package common
 */
class Guid
{
	// -------------------------------------------------------------------------
	public static function get()
	{
		return strtoupper(sprintf('%04x%04x%04x%04x%04x%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff),
			// 16 bits for "time_mid"
			mt_rand(0, 0xffff),
			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand(0, 0x0fff) | 0x4000,
			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for
			// variant DCE1.1
			mt_rand(0, 0x3fff) | 0x8000,
			// 48 bits for "node"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)));
	}
	// -------------------------------------------------------------------------
	public static function clear($guid)
	{
		$guid = str_replace("{", "", $guid);
		$guid = str_replace("}", "", $guid);
		$guid = str_replace("-", "", $guid);
		$guid = mb_substr($guid, 0, 32);
		return $guid;
	}
	// -------------------------------------------------------------------------
	public static function format($guid)
	{
		$hyphen = "-";
		$uuid = "{";
		$uuid .= substr($guid, 0, 8);
		$uuid .= $hyphen;
		$uuid .= substr($guid, 8, 4);
		$uuid .= $hyphen;
		$uuid .= substr($guid, 12, 4);
		$uuid .= $hyphen;
		$uuid .= substr($guid, 16, 4);
		$uuid .= $hyphen;
		$uuid .= substr($guid, 20, 12);
		$uuid .= "}";
		return $uuid;
	}
	// -------------------------------------------------------------------------
}
?>