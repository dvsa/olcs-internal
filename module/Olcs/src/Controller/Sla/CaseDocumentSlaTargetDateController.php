<?php

namespace Olcs\Controller\Sla;

use Olcs\Controller\Sla\AbstractSlaTargetDateController;
use Laminas\View\Model\ViewModel;

class CaseDocumentSlaTargetDateController extends AbstractSlaTargetDateController
{
    protected $entityType = 'document';

    protected $redirectConfig = [
        'addsla' => [
            'route' => 'case_licence_docs_attachments',
            'action' => 'documents'
        ],
        'editsla' => [
            'route' => 'case_licence_docs_attachments',
            'action' => 'documents'
        ]
    ];
}
