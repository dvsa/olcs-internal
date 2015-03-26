<?php

namespace Olcs\Controller\Traits;

/**
 * Class DocumentSearchTrait
 * @package Olcs\Controller
 */
trait DocumentSearchTrait
{

    /**
     * Inspect the request to see if we have any filters set, and
     * if necessary, filter them down to a valid subset
     *
     * @return array
     */
    protected function mapDocumentFilters($extra = array())
    {
        $defaults = array(
            'sort'   => 'issuedDate',
            'order'  => 'DESC',
            'page'   => 1,
            'limit'  => 10
        );

        $filters = array_merge(
            $defaults,
            $extra,
            $this->getRequest()->getQuery()->toArray()
        );

        if ( isset($filters['isDigital']) ) {
            if ($filters['isDigital'] === 'digital') {
                $filters['isDigital'] = true;
            } elseif ($filters['isDigital'] === 'nondigital') {
                $filters['isDigital'] = false;
            } else {
                unset($filters['isDigital']);
            }
        }

        // nuke any empty values
        return array_filter(
            $filters,
            function ($v) {
                return $v === false || !empty($v);
            }
        );
    }

    protected function getDocumentForm($filters = array())
    {
        $form = $this->getForm('DocumentsHome');

        // @see https://jira.i-env.net/browse/OLCS-6061
        $filters['isDoc'] = true;

        // the way this method is being called this sometimes comes
        // through as DESC; that's *never* right
        $filters['order'] = 'ASC';

        // grab all the relevant backend data needed to populate the
        // various dropdowns on the filter form
        $selects = array(
            'category' => $this->getListDataFromBackend('Category', ['isDocCategory' => true], 'description'),
            'documentSubCategory' => $this->getListDataFromBackend('SubCategory', $filters, 'subCategoryName'),
            'fileExtension' => $this->getListDataFromBackend(
                'RefData',
                ['refDataCategoryId' => 'document_type'],
                'description', 'id'
            )
        );

        // insert relevant data into the corresponding form inputs
        foreach ($selects as $name => $options) {
            $form->get($name)
                ->setValueOptions($options);
        }

        // setting $this->enableCsrf = false won't sort this; we never POST
        $form->remove('csrf');

        $form->setData($filters);

        return $form;
    }

    protected function getDocumentsTable($filters = array())
    {
        $documents = $this->makeRestCall(
            'DocumentSearchView',
            'GET',
            $filters
        );

        $filters['query'] = $this->getRequest()->getQuery();

        $table = $this->getTable('documents', $documents, $filters);

        return $table;
    }
}
