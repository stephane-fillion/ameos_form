<?php
namespace Ameos\AmeosForm\Utility;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

class Date {

	/**
	 * convert date to timestamp
	 * @param string $value
	 * @param string $format
	 * @return string
     */
	public static function dateToTimestamp($value, $format) {
		if($value == '') {
			return 0;
		}
		$format = str_replace('%', '', $format);
		$date = \Datetime::createFromFormat($format, $value);
		return $date->getTimestamp();
	}

	/**
	 * convert timestamp to date
	 * @param string $value
	 * @param string $format
	 * @return string
     */
	public static function timestampToDate($value, $format) {
		if(strpos($format, '%')) {
			return strftime($format, $value);
		}
		return date($format, $value);
	}
}
