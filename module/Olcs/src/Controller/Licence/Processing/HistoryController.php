<?php

namespace Olcs\Controller\Licence\Processing;

use Olcs\Controller\Interfaces\LicenceControllerInterface;
use Olcs\Controller\AbstractHistoryController;

class HistoryController extends AbstractHistoryController implements LicenceControllerInterface
{
    protected $navigationId = 'licence_processing_event-history';
    protected $listVars = ['licence'];
    protected $itemParams = ['licence', 'id' => 'id'];
}
