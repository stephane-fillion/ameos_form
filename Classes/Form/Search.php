<?php
namespace Ameos\AmeosForm\Form;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Ameos\AmeosForm\Utility\Events;
use Ameos\AmeosForm\Utility\UserUtility;

abstract class Search extends \Ameos\AmeosForm\Form\AbstractForm 
{

	/**
	 * @var bool $storeSearchInSession
	 */
	protected $storeSearchInSession = true;
	
	/**
	 * @constuctor
	 *
	 * @param	string $identifier form identifier
	 */
	public function __construct($identifier) 
	{
		parent::__construct($identifier);
        if (TYPO3_MODE == 'FE') {
            if (UserUtility::isLogged()) {
                $GLOBALS['TSFE']->fe_user->setKey('user', 'form-' . $this->getIdentifier() . '-clauses', $this->clauses);
            } else {
                $GLOBALS['TSFE']->fe_user->setKey('ses', 'form-' . $this->getIdentifier() . '-clauses', $this->clauses);
            }
            $GLOBALS['TSFE']->storeSessionData();
        } elseif (TYPO3_MODE == 'BE') {
            session_start();
            $_SESSION['form-' . $this->getIdentifier() . '-clauses'] = $this->clauses;
        }

		if (!is_array($this->clauses)) {
			$this->clauses = [];
		}
	}

	/**
	 * set if the search criterias are stored in session
	 * @param	bool	$storeSearchInSession
	 * @return	\Ameos\AmeosForm\Form this
	 */
	public function storeSearchInSession($storeSearchInSession = true) 
	{
		$this->storeSearchInSession = $storeSearchInSession;
		return $this;
	}
	
	/**
	 * add element fo the form
	 * 
	 * @param	string	$type element type
	 * @param	string	$name element name
	 * @param	array	$configuration element configuration
	 * @return	\Ameos\AmeosForm\Form this
	 */
	public function add($name, $type = '', $configuration = [], $overrideFunction = false) 
	{
		parent::add($name, $type, $configuration);
		if ($overrideFunction !== false) {
			$this->elements[$name]->setOverrideClause($overrideFunction);	
		}
		return $this;
	}

	/**
	 * set value from session
	 */
	public function setValueFromSession() 
	{
		foreach ($this->clauses as $clause) {
			if (($element = $this->getElement($clause['elementname'])) !== false) {
				$element->setValue($clause['elementvalue']);
			}
		}
	}
}
