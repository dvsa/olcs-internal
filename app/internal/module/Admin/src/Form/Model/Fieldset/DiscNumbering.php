<?php

namespace Admin\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @Form\Type("Zend\Form\Fieldset")
 * @Form\Options({
 *     "label": "admin_disc-printing.discNumbering"
 * })
 */
class DiscNumbering
{
    /**
     * @Form\Name("startNumber")
     * @Form\Options({
     *     "label": "admin_disc-printing.startNumber"
     * })
     * @Form\Type("\Common\Form\Elements\InputFilters\GoodsDiscStartNumber")
     */
    public $startNumber = null;

    /**
     * @Form\Name("endNumber")
     * @Form\Attributes({"disabled": true})
     * @Form\Options({
     *     "label": "admin_disc-printing.endNumber"
     * })
     * @Form\Type("Text")
     */
    public $endNumber = null;

    /**
     * @Form\Name("totalPages")
     * @Form\Attributes({"disabled": true})
     * @Form\Options({
     *     "label": "admin_disc-printing.totalPages"
     * })
     * @Form\Type("Text")
     */
    public $totalPages = null;

    /**
     * @Form\Name("originalEndNumber")
     * @Form\Type("Hidden")
     */
    public $originalEndNumber = null;

    /**
     * @Form\Name("endNumberIncreased")
     * @Form\Type("Hidden")
     */
    public $endNumberIncreased = null;
}
