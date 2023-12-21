<?php

declare(strict_types=1);

namespace Ameos\AmeosForm\Elements;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use Ameos\AmeosForm\Utility\Events;

class Password extends ElementAbstract
{
    /**
     * @var bool $searchable searchable
     */
    protected $searchable = false;

    /**
     * @constuctor
     *
     * @param    string    $absolutename absolutename
     * @param    string    $name name
     * @param    array    $configuration configuration
     * @param    \Ameos\AmeosForm\Form $form form
     */
    public function __construct($absolutename, $name, $configuration, $form)
    {
        $configuration['encrypt'] = isset($configuration['encrypt']) ? (bool)$configuration['encrypt'] : true;
        $configuration['fill_value'] = isset($configuration['fill_value']) ? (bool)$configuration['fill_value'] : false;
        $configuration['fill_value_iferror'] = isset($configuration['fill_value_iferror']) ? (bool)$configuration['fill_value_iferror'] : true;

        parent::__construct($absolutename, $name, $configuration, $form);
    }

    /**
     * form to html
     *
     * @return    string the html
     */
    public function toHtml()
    {
        $attributes = $this->getAttributes();
        if (
            $this->configuration['fill_value']
            || ($this->form->isSubmitted() && !$this->form->isValid() && $this->configuration['fill_value_iferror'])
        ) {
            $attributes .= ' value="' . $this->getValue() . '"';
        }
        return '<input type="password" id="' . $this->getHtmlId() . '" name="' . $this->absolutename . '"' . $attributes . ' />';
    }

    /**
     * set the value
     *
     * @param    string    $value value
     * @return     ElementAbstract this
     */
    public function setValue($value)
    {
        if ($this->configuration['encrypt'] && $value) {
            Events::getInstance($this->form->getIdentifier())->registerEvent('form_is_valid', [$this, 'encryptPassword'], [
                'password' => $value,
            ]);
        }

        $this->valueSetted = true;
        $this->value = $value;

        if ($this->form !== false) {
            if ($this->form->getMode() == 'crud/extbase' && $value != '') {
                $method = 'set' . \Ameos\AmeosForm\Utility\StringUtility::camelCase($this->name);
                if (method_exists($this->form->getModel(), $method)) {
                    $this->form->getModel()->$method($value);
                }
            }

            if ($this->form->getMode() == 'crud/classic' && $value != '') {
                if ($this->form->hasData($this->name)) {
                    $this->form->setData($this->name, $value);
                }
            }
        }

        return $this;
    }

    /**
     * encrypt password
     * @param string $password password
     */
    public function encryptPassword($password)
    {
        $hashInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)
            ->getDefaultHashInstance('FE');
        $this->setValue($hashInstance->getHashedPassword($password));
    }
}
