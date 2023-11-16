<?php

namespace Olcs\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Common\Service\Table\Formatter\Address as AddressFormatter;

/**
 * Class Markers
 * @package Olcs\View\Helper
 */
class Address extends AbstractHelper
{
    private AddressFormatter $addressFormatter;

    public function __construct(AddressFormatter $addressFormatter)
    {
        $this->addressFormatter = $addressFormatter;
    }
    /**
     * @param $address
     * @return string
     */
    public function __invoke(array $address)
    {
        $options = [
            'addressFields' => [
                'addressLine1',
                'addressLine2',
                'addressLine3',
                'addressLine4',
                'town',
                'postcode',
                'countryCode'
            ]
        ];

        return $this->addressFormatter->format($address, $options);
    }
}
