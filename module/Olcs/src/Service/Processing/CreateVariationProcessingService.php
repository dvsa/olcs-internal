<?php

/**
 * Create Variation Processing Service
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Service\Processing;

use Common\Service\Data\FeeTypeDataService;
use Dvsa\Olcs\Transfer\Command\Licence\CreateVariation;
use Laminas\Form\Form;
use Laminas\Http\Request;
use Laminas\ServiceManager\ServiceLocatorAwareTrait;
use Laminas\ServiceManager\ServiceLocatorAwareInterface;

/**
 * Create Variation Processing Service
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class CreateVariationProcessingService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function getForm(Request $request)
    {
        $form = $this->getServiceLocator()->get('Helper\Form')
            ->createFormWithRequest('CreateVariation', $request);

        if ($request->isPost()) {
            $form->setData((array)$request->getPost());
        } else {
            $dateHelper = $this->getServiceLocator()->get('Helper\Date');

            $form->setData(['data' => ['receivedDate' => $dateHelper->getDate()]]);
        }

        return $form;
    }

    public function createVariation($licenceId, $data)
    {
        $data['id'] = $licenceId;

        $command = CreateVariation::create($data);

        $annotationBuilder = $this->getServiceLocator()->get('TransferAnnotationBuilder');
        $commandService = $this->getServiceLocator()->get('CommandService');

        $command = $annotationBuilder->createCommand($command);
        $response = $commandService->send($command);

        if ($response->isOk()) {
            return $response->getResult()['id']['application'];
        }
    }

    public function getDataFromForm(Form $form)
    {
        return $form->getData()['data'];
    }
}
