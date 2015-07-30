<?php

namespace Olcs\Controller\Traits;

/**
 * Class ListDataTrait
 * @package Olcs\Controller
 */
trait ListDataTrait
{
    /**
     * This should be a const but that's a no-go in a trait
     *
     * @var int
     */
    private $max_list_data_limit = 100;

    abstract public function makeRestCall($service, $method, $data, array $bundle = null);

    /**
     * Retrieve some data from the backend and convert it for use in
     * a select. Optionally provide some search data to filter the
     * returned data too.
     */
    protected function getListDataFromBackend(
        $entity,
        $data = array(),
        $titleKey = 'name',
        $primaryKey = 'id',
        $showAll = 'All'
    ) {
        $data['limit'] = $this->max_list_data_limit;
        $data['sort'] = $titleKey;  // AC says always sort alphabetically
        $data['order'] = 'ASC';     // Sort was leaking in from filters, force to ASC

        $response = $this->makeRestCall($entity, 'GET', $data);

        if ($showAll !== false) {
            $final = array('' => $showAll);
        } else {
            $final = array();
        }

        if (isset($response['Results']) && is_array($response['Results'])) {
            foreach ($response['Results'] as $result) {
                $key = $result[$primaryKey];
                $value = $result[$titleKey];

                $final[$key] = $value;
            }
        }
        return $final;
    }
}
