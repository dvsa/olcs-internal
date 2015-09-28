<?php

/**
 * Licence Controller Trait
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller\Traits;

use Common\RefData;

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
        if ($licence['goodsOrPsv']['id'] == RefData::LICENCE_CATEGORY_GOODS_VEHICLE) {
            $this->getServiceLocator()->get('Navigation')->findOneBy('id', 'licence_bus')->setVisible(0);
        }

        $variables['licence'] = $licence;

        $view = $this->getView($variables);

        $this->pageTitle = $licence['licNo'];
        $this->pageSubTitle = $licence['organisation']['name']
            . ' ' . $licence['status']['description'];

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
            $id = $this->params()->fromRoute('licence');
        }

        /* @var $response \Common\Service\Cqrs\Response */
        $response = $this->handleQuery(
            \Dvsa\Olcs\Transfer\Query\Licence\Licence::create(['id' => $id])
        );

        if ($response->isNotFound()) {
            return null;
        }
        if (!$response->isOk()) {
            throw new \RuntimeException('Failed to get Licence data');
        }

        return $response->getResult();
    }
}
