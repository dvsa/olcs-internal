<?php
namespace Olcs\Data\Object\Search\Filter;

/**
 * Publication type filter class.
 *
 * @package Olcs\Data\Object\Search\Filter
 * @author Shaun Lizzio <shaun@lizzio.co.uk>
 */
class PublicationType extends FilterAbstract
{
    /**
     * The human readable title of this filter. This may also be used in the front-end (not sure yet).
     *
     * @var string
     */
    protected $title = 'Doc type';

    /**
     * The actual name of the field to ask for filter information for.
     *
     * @var string
     */
    protected $key = 'pubType';
}
