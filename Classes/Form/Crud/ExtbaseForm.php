<?php

declare(strict_types=1);

namespace Ameos\AmeosForm\Form\Crud;

use Ameos\AmeosForm\Form\Crud;

class ExtbaseForm extends Crud
{
    /**
     * @var \TYPO3\CMS\Extbase\DomainObject\AbstractEntity $model model
     */
    protected $model;

    /**
     * @constuctor
     *
     * @param   string $identifier form identifier
     * @param   \TYPO3\CMS\Extbase\DomainObject\AbstractEntity $model model
     */
    public function __construct($identifier, \TYPO3\CMS\Extbase\DomainObject\AbstractEntity $model)
    {
        parent::__construct($identifier);
        $this->mode  = 'crud/extbase';
        $this->model = $model;
    }

    /**
     * Return model
     *
     * @return  \TYPO3\CMS\Extbase\DomainObject\AbstractEntity model
     */
    public function getModel()
    {
        if ($this->getMode() == 'crud/extbase') {
            return $this->model;
        }
        return null;
    }
}
