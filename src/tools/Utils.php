<?php

namespace braga\tools\tools;

/**
 * Created 7.07.2026 11:35
 * error prefix
 * @autor Tomasz Gajewski
 */
class Utils
{
	function isValidEDoreczeniaAddress(?string $address): bool
	{
		if($address === null)
		{
			return false;
		}

		$address = strtoupper(trim($address));

		$pattern = '/^AE:PL-([0-9]{5})-([0-9]{5})-([A-Z]{5})-([0-9]{2})$/';

		if(preg_match($pattern, $address, $matches) !== 1)
		{
			return false;
		}

		$firstNumberPart = (int)$matches[1];
		$secondNumberPart = (int)$matches[2];
		$lettersPart = $matches[3];
		$providedChecksum = $matches[4];

		$lettersSum = 0;

		for($i = 0; $i < strlen($lettersPart); $i++)
		{
			$lettersSum += ord($lettersPart[$i]);
		}

		$numbersSum = $firstNumberPart + $secondNumberPart;

		$difference = abs($lettersSum - $numbersSum);

		$calculatedChecksumNumber = 0;

		foreach(str_split((string)$difference) as $digit)
		{
			$calculatedChecksumNumber += (int)$digit;
		}

		$calculatedChecksum = str_pad(
			(string)$calculatedChecksumNumber,
			2,
			'0',
			STR_PAD_LEFT
		);

		return $providedChecksum === $calculatedChecksum;
	}
}