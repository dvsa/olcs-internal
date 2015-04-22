<?php

/**
 * Email Service
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace Olcs\Service\Email;

use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Olcs\View\Model\Email\InspectionRequest as InspectionRequestEmailViewModel;

/**
 * Email Service
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class InspectionRequestEmailService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    const FROM_ADDRESS = "OLCS <donotreply@otc.gsi.gov.uk>";
    const SUBJECT_LINE = "[ Maintenance Inspection ] REQUEST=%s,STATUS=";

    /**
     * Build and inspection request email and send it via the email service
     *
     * @param int $inspectionRequestId
     */
    public function sendInspectionRequestEmail($inspectionRequestId)
    {
        // retrieve Inspection Request, User, People and Workshop data
        $inspectionRequest = $this->getServiceLocator()->get('Entity\InspectionRequest')
            ->getInspectionRequest($inspectionRequestId);

        $user = $this->getServiceLocator()->get('Entity\User')
            ->getCurrentUser();

        $peopleData = $this->getServiceLocator()->get('Entity\Person')
            ->getAllForOrganisation($inspectionRequest['licence']['organisation']['id']);

        $workshops = $this->getServiceLocator()->get('Entity\Workshop')
            ->getForLicence($inspectionRequest['licence']['id']);

        // Use view rendering to build email body
        $translator = $this->getServiceLocator()->get('Helper\Translation');
        $view = new InspectionRequestEmailViewModel();
        $view->populate($inspectionRequest, $user, $peopleData, $workshops, $translator);
        $emailBody = $this->getServiceLocator()->get('ViewRenderer')->render($view);

        // build subject line
        $subject = sprintf(self::SUBJECT_LINE, $inspectionRequest['id']);

        // look up destination email address from relevant enforcement area
        $toEmailAddress = $inspectionRequest['licence']['enforcementArea']['emailAddress'];

        // send via email service
        return $this->getServiceLocator()->get('email')
            ->sendEmail(self::FROM_ADDRESS, $toEmailAddress, $subject, $emailBody);
    }
}
