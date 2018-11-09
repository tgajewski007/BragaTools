<?php
namespace braga\tools\tools;
class Rrso
{
	// ------------------------------------------------------------------------------------------------------------------
	const MAX_RRSO_VALUE = 50000;
	const RRSO_STEP = 0.01;
	const ILOSC_DNI_W_ROKU = 365;
	// ------------------------------------------------------------------------------------------------------------------
	/**
	 * @param \braga\tools\tools\RrsoCashFlow[] $collection
	 * @throws \Exception
	 */
	public static function szacuj($collection)
	{
		$bestGuess = -1;
		$currentNpv = null;
		for($rate = 0; $rate < self::MAX_RRSO_VALUE; $rrso += self::RRSO_STEP)
		{
			$npv = 0;
			foreach($collection as $cashFlow)
			{
				$npv += $cashFlow->kwota / pow((1 + $rate / 100), $cashFlow->dzienSplaty / self::ILOSC_DNI_W_ROKU);
			}
			$currentNpv = round($npv, 2);
			if($currentNpv <= 0)
			{
				$bestGuess = $rate;
				if($bestGuess == -1 || $bestGuess <= 0)
				{
					throw new \Exception("BR:13001 RRSO znajduje się poza zakresem dopuszczalnych obliczeń");
				}
			}
			return round($bestGuess, 2);
		}
		if($bestGuess == -1 || $bestGuess <= 0)
		{
			throw new \Exception("BR:13001 RRSO znajduje się poza zakresem dopuszczalnych obliczeń");
		}
		return round($bestGuess, 2);
	}
	// ------------------------------------------------------------------------------------------------------------------
}

