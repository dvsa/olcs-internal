<?php

/**
 * Fees action trait
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace Olcs\Controller\Traits;

use Common\RefData;
use Dvsa\Olcs\Transfer\Command\Fee\ApproveWaive as ApproveWaiveCmd;
use Dvsa\Olcs\Transfer\Command\Fee\CreateFee as CreateFeeCmd;
use Dvsa\Olcs\Transfer\Command\Fee\RecommendWaive as RecommendWaiveCmd;
use Dvsa\Olcs\Transfer\Command\Fee\RejectWaive as RejectWaiveCmd;
use Dvsa\Olcs\Transfer\Command\Fee\RefundFee as RefundFeeCmd;
use Dvsa\Olcs\Transfer\Command\Transaction\CompleteTransaction as CompletePaymentCmd;
use Dvsa\Olcs\Transfer\Command\Transaction\PayOutstandingFees as PayOutstandingFeesCmd;
use Dvsa\Olcs\Transfer\Query\Fee\Fee as FeeQry;
use Dvsa\Olcs\Transfer\Query\Fee\FeeList as FeeListQry;
use Dvsa\Olcs\Transfer\Query\Fee\FeeType as FeeTypeQry;
use Dvsa\Olcs\Transfer\Query\Fee\FeeTypeList as FeeTypeListQry;
use Dvsa\Olcs\Transfer\Query\Transaction\Transaction as PaymentByIdQry;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Fees action trait
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
trait FeesActionTrait
{
    /**
     * Must be declared by implementing classes in an attempt to
     * try and sanitise template nuances between apps & licences
     */
    abstract protected function renderLayout($view);

    /**
     * Defines the controller specific fees route
     */
    protected abstract function getFeesRoute();

    /**
     * Defines the controller specific fees route params
     */
    protected abstract function getFeesRouteParams();

    /**
     * Defines the controller specific fees table params
     */
    protected abstract function getFeesTableParams();

    /**
     * Shows fees table
     */
    public function feesAction()
    {
        $response = $this->checkActionRedirect();
        if ($response) {
            return $response;
        }

        return $this->commonFeesAction();
    }

    /**
     * Pay Fees Action
     */
    public function payFeesAction()
    {
        return $this->commonPayFeesAction();
    }

    /**
     * Pay Fees Action
     */
    public function addFeeAction()
    {
        $form = $this->getForm('create-fee');
        $form = $this->alterCreateFeeForm($form);

        if ($this->getRequest()->isPost()) {
            if ($this->isButtonPressed('cancel')) {
                return $this->redirectToList();
            }
            $this->formPost($form, 'createFee', [$form]);
        }

        if ($this->getResponse()->getContent() !== '') {
            return $this->getResponse();
        }

        $this->getServiceLocator()->get('Helper\Form')
            ->setDefaultDate($form->get('fee-details')->get('createdDate'));

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('pages/form');

        $this->loadScripts(['forms/create-fee']);

        return $this->renderView($view, 'fees.create.title');
    }

    /**
     * Common logic when rendering the list of fees
     */
    private function commonFeesAction()
    {
        $this->loadScripts(['forms/filter', 'table-actions']);

        $status = $this->params()->fromQuery('status');
        $table = $this->getFeesTable($status);

        $view = new ViewModel(['table' => $table]);
        $view->setTemplate('pages/table');
        return $this->renderLayout($view);
    }

    public function getLeftView()
    {
        $status = $this->params()->fromQuery('status');
        $filters = [
            'status' => $status
        ];

        $view = new ViewModel(['filterForm' => $this->getFeeFilterForm($filters)]);
        $view->setTemplate('sections/fees/partials/left');

        return $view;
    }

    protected function checkActionRedirect()
    {
        if ($this->getRequest()->isPost()) {

            $data = (array)$this->getRequest()->getPost();

            $action = isset($data['action']) ? strtolower($data['action']) : '';
            switch ($action) {
                case 'new':
                    $params = [
                        'action' => 'add-fee',
                    ];
                    break;
                case 'pay':
                default:
                    if (!isset($data['id']) || empty($data['id'])) {
                        $this->addErrorMessage('fees.pay.error.please-select');
                        return $this->redirectToList();
                    }
                    $params = [
                        'action' => 'pay-fees',
                        'fee' => implode(',', $data['id']),
                    ];
                    break;
            }

            return $this->redirect()->toRoute(
                $this->getFeesRoute() . '/fee_action',
                $params,
                null,
                true
            );
        }
    }

    /**
     * Get fee filter form
     *
     * @param array $filters
     * @return \Zend\Form\Form
     */
    protected function getFeeFilterForm($filters = [])
    {
        $form = $this->getForm('fee-filter');
        $form->remove('csrf');
        $form->setData($filters);

        return $form;
    }

    /**
     * Get fees table
     *
     * @param string $status
     * @return \Common\Service\Table\TableBuilder;
     */
    protected function getFeesTable($status)
    {
        $params = array_merge(
            $this->getFeesTableParams(),
            [
                'page'    => $this->params()->fromQuery('page', 1),
                'sort'    => $this->params()->fromQuery('sort', 'id'),
                'order'   => $this->params()->fromQuery('order', 'ASC'),
                'limit'   => $this->params()->fromQuery('limit', 10)
            ]
        );

        if ($status) {
            $params['status'] = $status;
        }

        $results = $this->getFees($params);

        $tableParams = array_merge($params, ['query' => $this->getRequest()->getQuery()]);
        $table = $this->getTable('fees', $results, $tableParams);

        return $this->alterFeeTable($table, $results);
    }

    protected function getFees($params)
    {
        $query = FeeListQry::create($params);
        $response = $this->handleQuery($query);
        return $response->getResult();
    }

    protected function getFee($id)
    {
        $query = FeeQry::create(['id' => $id]);
        $response = $this->handleQuery($query);
        return $response->getResult();
    }

    /**
     * Display fee info and edit waive note
     */
    public function editFeeAction()
    {
        if ($this->isButtonPressed('refund')) {
            $route = $this->getFeesRoute() . '/fee_action';
            return $this->redirect()->toRoute($route, ['action' => 'refund-fee'], [], true);
        }

        $id = $this->params()->fromRoute('fee', null);

        $fee = $this->getFee($id);

        $form = $this->alterFeeForm($this->getForm('fee'), $fee);
        $form = $this->setDataFeeForm($fee, $form);
        $this->processForm($form);

        if ($this->getResponse()->getContent() !== '') {
            return $this->getResponse();
        }

        $table = $this->getTable('fee-transactions', $fee['displayTransactions'], []);

        $viewParams = [
            'form' => $form,
            'table' => $table,
            'invoiceNo' => $fee['id'],
            'description' => $fee['description'],
            'amount' => $fee['amount'],
            'created' => $fee['invoicedDate'],
            'outstanding' => $fee['outstanding'],
            'status' => isset($fee['feeStatus']['description']) ? $fee['feeStatus']['description'] : '',
            'fee' => $fee
        ];

        $this->placeholder()->setPlaceholder('contentTitle', 'internal.fee-details.title');

        $this->loadScripts(['forms/fee-details']);

        $view = new ViewModel($viewParams);
        $view->setTemplate('sections/fees/pages/fee-details');

        $layout = $this->renderLayout($view, 'No # ' . $fee['id']);

        $this->maybeClearLeft($layout);

        return $layout;
    }

    /**
     * Alter which feeTransactions are displayed in the table,
     * called as array_filter callback
     *
     * @param array $feeTransaction
     * @return boolean
     */
    public function ftDisplayFilter($feeTransaction)
    {
        // OLCS-10687 exclude outstanding waive transactions
        if (
            $feeTransaction['transaction']['status']['id'] === RefData::TRANSACTION_STATUS_OUTSTANDING
            &&
            $feeTransaction['transaction']['type']['id'] === RefData::TRANSACTION_TYPE_WAIVE
        ) {
            return false;
        }

        return true;
    }

    protected function maybeClearLeft($layout)
    {
        $layout->clearLeft();
    }

    /**
     * Redirect back to fee details page
     */
    protected function redirectToFeeDetails($ajax = false)
    {
        $method = $ajax ? 'toRouteAjax' : 'toRoute';

        $route = $this->getFeesRoute() . '/fee_action';
        return $this->redirect()->$method($route, ['action' => 'edit-fee'], [], true);
    }

    /**
     * Display transaction info
     */
    public function transactionAction()
    {
        $id = $this->params()->fromRoute('transaction', null);

        $query = PaymentByIdQry::create(['id' => $id]);
        $response = $this->handleQuery($query);

        if (!$response->isOk()) {
            if ($response->isNotFound()) {
                return $this->notFoundAction();
            }
            $this->addErrorMessage('unknown-error');
            return $this->redirectToFeeDetails();
        }

        $transaction = $response->getResult();

        $fees = $transaction['fees'];

        $table = $this->getTable('transaction-fees', $fees);

        $backLink = $this->getServiceLocator()->get('Helper\Url')
            ->fromRoute($this->getFeesRoute() . '/fee_action', ['action' => 'edit-fee'], [], true);

        $receiptLink = ($transaction['type']['id'] == RefData::TRANSACTION_TYPE_PAYMENT) ?
            $this->getServiceLocator()->get('Helper\Url')
                ->fromRoute(
                    $this->getFeesRoute() . '/print-receipt',
                    ['reference' => $transaction['reference']],
                    [],
                    true
                ) :
            '';

        $viewParams = [
            'table' => $table,
            'transaction' => $transaction,
            'backLink' => $backLink,
            'receiptLink' => $receiptLink
        ];

        $this->placeholder()->setPlaceholder('contentTitle', 'internal.transaction-details.title');

        $view = new ViewModel($viewParams);
        $view->setTemplate('sections/fees/pages/transaction-details');

        $layout = $this->renderLayout($view, 'Transaction # ' . $transaction['id']);

        $this->maybeClearLeft($layout);

        return $layout;
    }

    /**
     * Common logic when handling payFeesAction
     */
    protected function commonPayFeesAction()
    {
        $feeIds = explode(',', $this->params('fee'));
        $feeData = $this->getFees(['ids' => $feeIds]);
        $fees = $feeData['results'];
        $title = 'Pay fee' . (count($fees) !== 1 ? 's' : '');

        foreach ($fees as $fee) {
            // bail early if any of the fees prove to be the wrong status
            if ($fee['feeStatus']['id'] !== RefData::FEE_STATUS_OUTSTANDING) {
                $this->addErrorMessage('You can only pay outstanding fees');
                return $this->redirectToList();
            }
        }

        $form = $this->getForm('FeePayment');
        $form = $this->alterPaymentForm($form, $feeData);

        $this->loadScripts(['forms/fee-payment']);

        if ($this->getRequest()->isPost()) {

            $data = (array)$this->getRequest()->getPost();

            // check if we need to recover serialized data from confirm step
            if ($this->isButtonPressed('confirm') && isset($data['custom'])) {
                $data = unserialize($data['custom']);
            }

            if ($this->isCardPayment($data)) {
                // remove field and validator if this is a card payment
                $this->getServiceLocator()
                    ->get('Helper\Form')
                    ->remove($form, 'details->received');
            }

            $form->setData($data);

            if ($form->isValid()) {

                if ($this->shouldConfirmPayment($feeData, $data)) {
                    $confirmMessage = $this->getConfirmPaymentMessage($feeData, $data);
                    $confirm = $this->confirm($confirmMessage, false, serialize($data));

                    if ($confirm instanceof ViewModel) {
                        return $this->renderView($confirm);
                    }
                }

                return $this->initiatePaymentRequest($feeIds, $form->getData()['details']);
            }
        }

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('pages/form');

        return $this->renderView($view, $title);
    }

    protected function refundFeeAction()
    {
        $feeId = $this->params('fee');
        $formHelper = $this->getServiceLocator()->get('Helper\Form');
        $form = $formHelper->createFormWithRequest('GenericConfirmation', $this->getRequest());

        if ($this->getRequest()->isPost()) {
            if ($this->isButtonPressed('cancel')) {
                return $this->redirectToFeeDetails();
            }
            $data = (array) $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $response = $this->handleCommand(
                    RefundFeeCmd::create(['id' => $feeId])
                );
                if ($response->isOk()) {
                    $this->addSuccessMessage('fees.refund.success');
                } else {
                    $this->addErrorMessage('unknown-error');
                }
                return $this->redirectToFeeDetails(true);
            }
        }

        $form->get('messages')->get('message')->setValue('fees.refund.confirm');
        $form->setSubmitLabel('Refund');
        $view = new ViewModel(array('form' => $form));
        $view->setTemplate('pages/form');

        return $this->renderView($view, 'fees.refund.title');
    }

    /**
     * Alter fee form
     *
     * @param \Zend\Form\Form $form
     * @param array $fee
     * @return \Zend\Form\Form
     */
    protected function alterFeeForm($form, $fee)
    {
        $status = $fee['feeStatus']['id'];

        if ($status !== RefData::FEE_STATUS_OUTSTANDING) {
            $form->get('form-actions')->remove('approve');
            $form->get('form-actions')->remove('reject');
            $form->get('form-actions')->remove('recommend');
            // don't remove whole fieldset as we need to keep 'back' button

            $form->get('fee-details')->remove('waiveRemainder'); // checkbox
            $form->get('fee-details')->remove('waiveReason'); // textbox

            return $form;
        }

        if ($fee['hasOutstandingWaiveTransaction']) {
            $form->get('fee-details')->remove('waiveRemainder');
            $form->get('form-actions')->remove('recommend');
        } else {
            $form->get('form-actions')->remove('approve');
            $form->get('form-actions')->remove('reject');
        }

        if ($fee['canRefund'] === false) {
            $form->get('form-actions')->remove('refund');
        }

        return $form;
    }

    /**
     * Alter fee payment form
     *
     * @param \Zend\Form\Form $form
     * @param array $feeData from FeeList query
     * @return \Zend\Form\Form
     */
    protected function alterPaymentForm($form, $feeData)
    {
        $minAmount = $feeData['extra']['minPayment'];
        $maxAmount = $feeData['extra']['totalOutstanding'];

        // default the receipt date to 'today'
        $today = $this->getServiceLocator()->get('Helper\Date')->getDateObject();
        $form->get('details')
            ->get('receiptDate')
            ->setValue($today);

        // add the fee amount and validator to the form
        $form->get('details')
            ->get('maxAmount')
            ->setValue('£' . number_format($maxAmount, 2));

        // conditional validation needs numeric values to compare
        $form->get('details')
            ->get('maxAmountForValidator')
            ->setValue($maxAmount);
        $form->get('details')
            ->get('minAmountForValidator')
            ->setValue($minAmount);

        return $form;
    }

    /**
     * Alter create fee form
     *
     * @param \Zend\Form\Form $form
     * @return \Zend\Form\Form
     */
    protected function alterCreateFeeForm($form)
    {
        $formHelper = $this->getServiceLocator()->get('Helper\Form');

        // disable amount validation by default
        $form->get('fee-details')->get('amount')->setAttribute('readonly', true);
        $formHelper->disableEmptyValidationOnElement($form, 'fee-details->amount');

        // remove IRFO fields by default
        $formHelper->remove($form, 'fee-details->irfoGvPermit');
        $formHelper->remove($form, 'fee-details->irfoPsvAuth');

        // populate fee type select
        $options = $this->fetchFeeTypeValueOptions();
        $form->get('fee-details')->get('feeType')->setValueOptions($options);

        return $form;
    }

    /**
     * Get value options array for create fee type form
     *
     * @return array
     */
    protected function fetchFeeTypeValueOptions($effectiveDate = null)
    {
        $data = $this->fetchFeeTypeListData($effectiveDate);
        if (isset($data['extra']['valueOptions']['feeType'])) {
            return $data['extra']['valueOptions']['feeType'];
        }

        return [];
    }

    /**
     * @return array
     */
    protected function fetchFeeTypeListData($effectiveDate = null)
    {
        $dtoData = $this->getFeeTypeDtoData();
        if ($effectiveDate) {
            $dtoData['effectiveDate'] = $effectiveDate;
        }
        $response = $this->handleQuery(FeeTypeListQry::create($dtoData));
        if ($response->isOk()) {
            return $response->getResult();
        }
    }

    protected function getFeeTypeDtoData()
    {
        return [];
    }

    protected function getCreateFeeDtoData($formData)
    {
        return [];
    }

    /**
     * @param Table $table
     * @param array $results
     * @return Table
     */
    protected function alterFeeTable($table, $results)
    {
        // disable 'pay' button if appropriate
        if ($results['extra']['allowFeePayments'] == false) {
            $table->disableAction('pay');
        }

        return $table;
    }


    /**
     * Process form
     *
     * @param \Zend\Form\Form $form
     */
    protected function processForm($form)
    {
        if ($this->isButtonPressed('recommend')) {
            $this->formPost($form, 'recommendWaive');
        } elseif ($this->isButtonPressed('reject')) {
            $this->validateForm = false;
            $this->formPost($form, 'rejectWaive');
        } elseif ($this->isButtonPressed('approve')) {
            $this->formPost($form, 'approveWaive');
        } elseif ($this->isButtonPressed('cancel')) {
            $this->redirectToList();
        }
    }

    /**
     * Recommend waive
     *
     * @param array $data
     */
    protected function recommendWaive($data)
    {
        $dto = RecommendWaiveCmd::create(
            [
                'id' => $data['fee-details']['id'],
                'version' => $data['fee-details']['version'],
                'waiveReason' => $data['fee-details']['waiveReason'],
            ]
        );

        $this->updateFeeAndRedirectToList($dto, 'Waive recommended');
    }

    /**
     * Reject waive
     *
     * @param array $data
     */
    protected function rejectWaive($data)
    {
        $dto = RejectWaiveCmd::create(
            [
                'id' => $data['fee-details']['id'],
                'version' => $data['fee-details']['version'],
            ]
        );

        return $this->updateFeeAndRedirectToList($dto, 'Waive rejected');
    }

    /**
     * Approve waive
     *
     * @param array $data
     */
    protected function approveWaive($data)
    {
        $dto = ApproveWaiveCmd::create(
            [
                'id' => $data['fee-details']['id'],
                'version' => $data['fee-details']['version'],
                'waiveReason' => $data['fee-details']['waiveReason'],
            ]
        );

        return $this->updateFeeAndRedirectToList($dto, 'Waive approved');
    }

    /**
     * Update fee and redirect to list
     *
     * @param CommandInterface $command
     * @param string $message
     */
    protected function updateFeeAndRedirectToList($command, $message = '')
    {
        $response = $this->handleCommand($command);

        if ($response->isOk() && $message) {
            $this->addSuccessMessage($message);
        }

        if (!$response->isOk()) {
            $this->addErrorMessage('unknown-error');
        }

        $this->redirectToList();
    }

    /**
     * Set data
     *
     * @param array $fee
     * @param \Zend\Form\Form $form
     * @return \Zend\Form\Form
     */
    protected function setDataFeeForm($fee, $form)
    {
        if ($form) {
            $form->get('fee-details')->get('id')->setValue($fee['id']);
            $form->get('fee-details')->get('version')->setValue($fee['version']);
            if (isset($fee['waiveReason'])) {
                $form->get('fee-details')->get('waiveReason')->setValue($fee['waiveReason']);
            }
        }
        return $form;
    }

    /**
     * Redirect back to list of fees
     */
    protected function redirectToList()
    {
        $route = $this->getFeesRoute();
        $params = $this->getFeesRouteParams();
        return $this->redirect()->toRouteAjax($route, $params);
    }

    /**
     * Kick off the CPMS payment process for a given amount
     * relating to a given array of fees
     *
     * @param array  $feeIds
     * @param array  $details
     */
    private function initiatePaymentRequest($feeIds, $details)
    {
        $paymentMethod = $details['paymentType'];

        switch ($paymentMethod) {
            case RefData::FEE_PAYMENT_METHOD_CARD_OFFLINE:

                $cpmsRedirectUrl = $this->url()->fromRoute(
                    $this->getFeesRoute() . '/fee_action',
                    ['action' => 'payment-result'],
                    ['force_canonical' => true],
                    true
                );

                $dtoData = compact('cpmsRedirectUrl', 'feeIds', 'paymentMethod');
                $dto = PayOutstandingFeesCmd::create($dtoData);
                $response = $this->handleCommand($dto);

                if (!$response->isOk()) {
                    $this->addErrorMessage('unknown-error');
                    return $this->redirectToList();
                }

                // Look up the new payment in order to get the redirect data
                $transactionId = $response->getResult()['id']['transaction'];
                $response = $this->handleQuery(PaymentByIdQry::create(['id' => $transactionId]));
                $transaction = $response->getResult();
                $view = new ViewModel(
                    [
                        'gateway' => $transaction['gatewayUrl'],
                        'data' => [
                            'receipt_reference' => $transaction['reference']
                        ]
                    ]
                );
                // render the gateway redirect and return early
                $view->setTemplate('cpms/payment');
                return $this->renderView($view);

            case RefData::FEE_PAYMENT_METHOD_CASH:
                $dtoData = [
                    'feeIds' => $feeIds,
                    'paymentMethod' => $paymentMethod,
                    'received' => $details['received'],
                    'receiptDate' => $details['receiptDate'],
                    'payer' => $details['payer'],
                    'slipNo' => $details['slipNo'],
                ];
                break;

            case RefData::FEE_PAYMENT_METHOD_CHEQUE:
                $dtoData = [
                    'feeIds' => $feeIds,
                    'paymentMethod' => $paymentMethod,
                    'received' => $details['received'],
                    'receiptDate' => $details['receiptDate'],
                    'payer' => $details['payer'],
                    'slipNo' => $details['slipNo'],
                    'chequeNo' => $details['chequeNo'],
                    'chequeDate' => $details['chequeDate'],
                ];
                break;

            case RefData::FEE_PAYMENT_METHOD_POSTAL_ORDER:
                $dtoData = [
                    'feeIds' => $feeIds,
                    'paymentMethod' => $paymentMethod,
                    'received' => $details['received'],
                    'receiptDate' => $details['receiptDate'],
                    'payer' => $details['payer'],
                    'slipNo' => $details['slipNo'],
                    'poNo' => $details['poNo'],
                ];
                break;

            default:
                throw new \UnexpectedValueException("Payment type '$paymentMethod' is not valid");
        }

        $dto = PayOutstandingFeesCmd::create($dtoData);
        $response = $this->handleCommand($dto);

        if ($response->isOk()) {
            $this->addSuccessMessage('The payment was made successfully');
        } else {
            $this->addErrorMessage('The fee(s) have NOT been paid. Please try again');
        }

        return $this->redirectToList();
    }

    /**
     * Handle response from third-party payment gateway
     */
    public function paymentResultAction()
    {
        $queryStringData = (array)$this->getRequest()->getQuery();

        $dtoData = [
            'reference' => $queryStringData['receipt_reference'],
            'cpmsData' => $queryStringData,
            'paymentMethod' => RefData::FEE_PAYMENT_METHOD_CARD_OFFLINE,
        ];

        $response = $this->handleCommand(CompletePaymentCmd::create($dtoData));

        if (!$response->isOk()) {
            $this->addErrorMessage('The fee payment failed');
            return $this->redirectToList();
        }

        // check payment status and redirect accordingly
        $transactionId = $response->getResult()['id']['transaction'];
        $response = $this->handleQuery(PaymentByIdQry::create(['id' => $transactionId]));
        $transaction = $response->getResult();

        switch ($transaction['status']['id']) {
            case RefData::TRANSACTION_STATUS_COMPLETE:
                $this->addSuccessMessage('The fee(s) have been paid successfully');
                break;
            case RefData::TRANSACTION_STATUS_CANCELLED:
                $this->addWarningMessage('The fee payment was cancelled');
                break;
            case RefData::TRANSACTION_STATUS_FAILED:
                $this->addErrorMessage('The fee payment failed');
                break;
            default:
                $this->addErrorMessage('An unexpected error occured');
                break;
        }

        return $this->redirectToList();
    }

    /**
     * Create fee
     *
     * @param array $data
     */
    protected function createFee($data, $form)
    {
        $dtoData = $this->getCreateFeeDtoData($data);

        $dto = CreateFeeCmd::create($dtoData);

        $response = $this->handleCommand($dto);

        if ($response->isOk()) {
            $this->addSuccessMessage('fees.create.success');
            $this->redirectToList();
        } else {
            $errors = $response->getResult();
            \Olcs\Data\Mapper\CreateFee::mapFromErrors($form, $errors);
            if (!empty($errors)) {
                $this->addErrorMessage('fees.create.error');
            }
        }
    }

    /**
     * Determine if we're making a card payment
     *
     * @param array $data payment data
     * @return boolean
     */
    public function isCardPayment($data)
    {
        return (
            isset($data['details']['paymentType'])
            && $data['details']['paymentType'] == RefData::FEE_PAYMENT_METHOD_CARD_OFFLINE
        );
    }

    /**
     * @param array $feeData from FeeList query
     * @param array $postData
     * @return boolean
     */
    protected function shouldConfirmPayment(array $feeData, array $postData)
    {
        if ($this->isCardPayment($postData)) {
            return false;
        }

        // force the amounts to be in the same format, we can't compare floats for equality
        $received = number_format((float)$postData['details']['received'], 2);
        $total = number_format((float)$feeData['extra']['totalOutstanding'], 2);
        return ($received !== $total);
    }

    /**
     * @param array $feeData from FeeList query response
     * @param array $postData
     * @return string message (translation key)
     */
    protected function getConfirmPaymentMessage(array $feeData, $postData)
    {
        $received = $postData['details']['received'];
        $total = $feeData['extra']['totalOutstanding'];

        if ($received > $total) {
            // overpayment
            if ($received > ($total * 2)) {
                // A slightly altered warning message is displayed if the payment amount
                // is more than double the amount outstanding in order to avoid mis-keying:
                return 'internal.fee-payment.over-payment-double';
            }
            return 'internal.fee-payment.over-payment-standard';
        }

        // underpayment (different message for one or multiple fees)
        $suffix = $feeData['count'] > 1 ? 'multiple' : 'single';
        return 'internal.fee-payment.part-payment-' . $suffix;
    }

    public function feeTypeAction()
    {
        $id = $this->params('id');
        $response = $this->handleQuery(FeeTypeQry::create(['id' => $id]));

        $feeType = $response->getResult();

        return new JsonModel(['value' => $feeType['displayValue']]);
    }

    public function feeTypeListAction()
    {
        $valueOptions = $this->fetchFeeTypeValueOptions($this->params('date'));

        // map to format that the JS expects :-/
        $feeTypes = array_map(
            function ($id, $description) {
                return array(
                    'value' => $id,
                    'label'  => $description,
                );
            },
            array_keys($valueOptions),
            $valueOptions
        );

        array_unshift($feeTypes, ["value" => "", "label" => ""]);

        return new JsonModel($feeTypes);
    }
}
