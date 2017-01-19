<?php

namespace Olcs\Service\Data;

/**
 * Class DocumentSubCategory
 *
 * @package Olcs\Service\Data
 */
class DocumentSubCategory extends SubCategory
{
    /**
     * constructor.
     */
    public function __construct()
    {
        $this->setCategoryType(self::TYPE_IS_DOC);
    }
}
