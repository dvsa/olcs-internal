<?php

/**
 * Note controller
 * Application note search and display
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace Olcs\Controller\Application\Processing;

use Common\Controller\CrudInterface;
use Olcs\Controller\Traits\DeleteActionTrait;
use Olcs\Controller\Traits\LicenceNoteTrait;
use Zend\View\Model\ViewModel;

/**
 * Note controller
 * Application note search and display
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class ApplicationProcessingNoteController extends AbstractApplicationProcessingController implements CrudInterface
{
    use DeleteActionTrait;
    use LicenceNoteTrait;

    protected $headerViewTemplate = 'application/header';

    protected $section = 'notes';
    protected $service = 'Note';

    public function __construct()
    {
        $this->setTemplatePrefix('application/processing');
        $this->setRoutePrefix('application/processing');
        $this->setRedirectIndexRoute('/notes');
    }

    /**
     * Brings back a list of notes based on the search
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $applicationId = $this->getFromRoute('application');
        $licenceId = $this->getServiceLocator()->get('Entity\Application')->getLicenceIdForApplication($applicationId);

        //unable to use checkForCrudAction() as add and edit/delete require different routes
        $action = $this->getFromPost('action');
        $id = $this->getFromPost('id');

        $notesResult = $this->getNotesList($licenceId, $licenceId, 'note_t_app', $action, $id);
        // 'getNotesList' will return a ViewModel or a HttpResponse(!)

        //if a ViewModel has been returned
        if ($notesResult instanceof ViewModel) {
            $view = $this->getView(['table' => 'notes table here']); // insert $notesResult here
            $view->setTemplate('application/processing/layout');
            return $this->renderView($view);
        }

        //if a redirect has been returned
        return $notesResult;
    }
}
