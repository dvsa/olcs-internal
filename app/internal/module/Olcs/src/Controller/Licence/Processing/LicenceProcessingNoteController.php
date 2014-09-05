<?php

/**
 * Note controller
 * Licence note search and display
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
namespace Olcs\Controller\Licence\Processing;

use Common\Controller\CrudInterface;
use Olcs\Controller\Traits\DeleteActionTrait;
use Olcs\Controller\Traits\LicenceNoteTrait;

/**
 * Note controller
 * Licence note search and display
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class LicenceProcessingNoteController extends AbstractLicenceProcessingController implements CrudInterface
{
    use DeleteActionTrait;
    use LicenceNoteTrait;

    protected $section = 'notes';

    public function __construct()
    {
        $this->setTemplatePrefix('licence/processing');
    }

    /**
     * Brings back a list of notes based on the search
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $licenceId = $this->getFromRoute('licence');

        //unable to use checkForCrudAction() as add and edit/delete require different routes
        $action = $this->getFromPost('action');
        $id = $this->getFromPost('id');

        $view = $this->getNotesList($licenceId, 'note_t_lic', $action, $id);

        return $this->renderView($view);
    }
}
