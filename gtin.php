<?php

// GTIN Validator

/* 

- Multiply every other digit by 3, for even length GTIN numbers, start by multiplying the first digit by 3, leaving out the last digit
- Get the sum of the new values, leaving out the last digit
- Subtract the sum from the nearest (equal or higher) multiple of 10, ie 60 - 57(sum) would give a check number of 3

GTIN(13): 6291041500213 sum = 57 and check = 3
GTIN(12): 036000241457 sum = 53 and check = 7
*/

class GTIN
{
	private $validLegnths = [8, 12, 13, 14];

	public static function validate(string $gtin) : bool
	{
		$self = new self();
		$gtin = array_map('intval', str_split($gtin)); // Split string, and convert to integers
		$count = count($gtin); // Get length of GTIN number
		if(!in_array($count, $self->validLegnths)) return FALSE; // If legnth doesn't match value in array, return false
		$odd = ($count % 2 === 0) ? FALSE : TRUE;
		return self::checkDigit($gtin, $odd);
	}

	public static function checkDigit($gtin, $odd = FALSE)
	{
		$lastDigit = array_pop($gtin); // Stores removed element (the check number)
		$count = count($gtin);
		$multiThree = TRUE; // Indicates if value needs to be multiplied by 3
		if($odd) $multiThree = FALSE;
		for($i = 0; $i < $count; $i++)
		{
			$result[] = ($multiThree) ? $gtin[$i] * 3 : $gtin[$i];
			$multiThree = ($multiThree) ? FALSE : TRUE; // If just multiplied by 3, don't multiply the next. Or vice versa
		}
		$sum = array_sum($result);
		$diff = 100; // Random number out of the check number value range
		foreach ($result as $value)
		{
			$value = $value * 10;
			$tempDiff = $value - $sum; // Get the difference between current value and sum
			// If smaller than current difference, set diffrence to temp value
			if($tempDiff > 0 && $tempDiff < $diff) $diff = $tempDiff;
		}
		return ($diff === $lastDigit) ? TRUE : FALSE;
	}
}

$result = GTIN::validate('036000241457');
var_dump($result);


