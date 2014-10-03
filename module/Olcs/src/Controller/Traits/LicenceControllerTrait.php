<?php

/**
 * Licence Controller Trait
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller\Traits;

use Common\Controller\Service\LicenceSectionService;

/**
 * Licence Controller Trait
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
trait LicenceControllerTrait
{
    /**
     * Get view with licence
     *
     * @param array $variables
     * @return \Zend\View\Model\ViewModel
     */
    protected function getViewWithLicence($variables = array())
    {
        $licence = $this->getLicence();

        if ($licence['goodsOrPsv']['id'] == LicenceSectionService::LICENCE_CATEGORY_GOODS_VEHICLE) {
            $this->getServiceLocator()->get('Navigation')->findOneBy('id', 'licence_bus')->setVisible(0);
        }

        $variables['licence'] = $licence;

        $view = $this->getView($variables);

        $this->pageTitle = $view->licence['licNo'];
        $this->pageSubTitle = $view->licence['goodsOrPsv']['description'] . ', ' .
            $view->licence['licenceType']['description']
            . ', ' . $view->licence['status']['description'];

        return $view;
    }

    /**
     * Gets the licence by ID.
     *
     * @param integer $id
     * @return array
     */
    protected function getLicence($id = null)
    {
        if (is_null($id)) {
            $id = $this->getFromRoute('licence');
        }

        /** @var \Olcs\Service\Data\Licence $dataService */
        $dataService = $this->getServiceLocator()->get('Olcs\Service\Data\Licence');
        return $dataService->fetchLicenceData($id);
    }
}
