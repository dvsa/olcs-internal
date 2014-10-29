<?php

namespace Olcs\Controller\Cases\PublicInquiry;

/**
 * Class SlaController
 * @package Olcs\Controller\Cases\PublicInquiry
 */
class SlaController extends PublicInquiryController
{
    /**
     * Holds the form name
     *
     * @var string
     */
    protected $formName = 'PublicInquirySla';

    /**
     * @var array
     */
    protected $inlineScripts = ['showhideinput', 'pi-sla'];

    public function processLoad($data)
    {
        $data = parent::processLoad($data);

        $data = $this->formatDataForSlaService($data);

        $this->getServiceLocator()->get('Common\Service\Data\Sla')->setContext('pi', $data);

        return $data;
    }

    public function onInvalidPost($form)
    {
        $this->processLoad($this->loadCurrent());
    }
}
