<?php
namespace Ameos\AmeosForm\Constraints;

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

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class Captcha extends \Ameos\AmeosForm\Constraints\ConstraintAbstract 
{
	
	/**
	 * return true if the element is valide
	 *
	 * @param	string $value value to test
	 * @return	bool true if the element is valide
	 */
	public function isValid($value) 
	{
		require_once ExtensionManagementUtility::extPath('ameos_form') . 'Classes/Contrib/SecureImage/securimage.php';
		$securimage = new \Securimage();
		return $securimage->check($value);
	}
}
