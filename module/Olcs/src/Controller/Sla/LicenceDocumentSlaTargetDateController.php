<?php

namespace Olcs\Controller\Sla;

use Olcs\Controller\Sla\AbstractSlaTargetDateController;
use Laminas\View\Model\ViewModel;

class LicenceDocumentSlaTargetDateController extends AbstractSlaTargetDateController
{
    protected $entityType = 'document';

    protected $redirectConfig = [
        'addsla' => [
            'route' => 'licence/documents',
            'action' => 'documents'
        ],
        'editsla' => [
            'route' => 'licence/documents',
            'action' => 'documents'
        ]
    ];
}
