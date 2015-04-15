<?php

/**
 * Licence Processing Publications Controller
 */
namespace Olcs\Controller\Licence\Processing;

use Common\Exception\ResourceNotFoundException;
use Common\Exception\BadRequestException;
use Common\Exception\DataServiceException;
use Common\Controller as CommonController;
use Zend\Http\Response;

/**
 * Licence Processing Publications Controller
 * @note This does not extend from CrudAbstract, so doesn't use same processSave mechanism as other Crud forms. Not
 * sure why. What this means is that I have had to declare the isSaved flag which when set,
 * is caught and a redirect object is returned (modals used) - ShaunL
 *
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class LicenceProcessingPublicationsController extends AbstractLicenceProcessingController implements
    CommonController\CrudInterface
{
    protected $section = 'publications';

    /**
     * Flag to intercept the save and determine whether to return redirect object
     * @var bool
     */
    private $isSaved = false;

    /**
     * Index action
     *
     * @return Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->checkForCrudAction(null, [], 'id');

        $view = $this->getViewWithLicence();

        $requestQuery = $this->getRequest()->getQuery();
        $requestArray = $requestQuery->toArray();

        $defaultParams = [
            'page' => 1,
            'limit' => 10,
            'sort' => 'createdOn',
            'order' => 'DESC',
            'licence' => $this->params()->fromRoute('licence')
        ];

        $filters = array_merge(
            $defaultParams,
            $requestArray
        );

        $data = $this->getService()->fetchList($filters);

        if (!isset($data['url'])) {
            $data['url'] = $this->getPluginManager()->get('url');
        }

        $table = $this->getServiceLocator()->get('Table')->buildTable(
            'publication',
            $data,
            array_merge(
                $filters,
                array('query' => $requestQuery)
            ),
            false
        );

        $view->setVariables(['table' => $table]);
        $view->setTemplate('partials/table');
        $this->loadScripts(['table-actions']);

        return $this->renderView($view);
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        $publication = $this->getService();

        try {
            $publication->delete($id);
            $this->addErrorMessage('Record deleted successfully');
        } catch (DataServiceException $e) {
            $this->addErrorMessage($e->getMessage());
        } catch (BadRequestException $e) {
            $this->addErrorMessage($e->getMessage());
        } catch (ResourceNotFoundException $e) {
            $this->addErrorMessage($e->getMessage());
        }

        return $this->redirectToIndex();
    }

    /**
     * Edit action
     *
     * @return Zend\View\Model\ViewModel
     */
    public function editAction()
    {
        $id = $this->getFromRoute('id');
        $service = $this->getService();
        $publication = $service->fetchOne($id);

        $readOnly = [
            'typeArea' => $publication['publication']['pubType'] . ' / ' .
                $publication['publication']['trafficArea']['name'],
            'publicationNo' => $publication['publication']['publicationNo'],
            'status' => $publication['publication']['pubStatus']['description'],
            'section' => $publication['publicationSection']['description'],
            'trafficArea' => $publication['publication']['trafficArea']['name'],
            'publicationDate' => date('d/m/Y', strtotime($publication['publication']['pubDate']))
        ];

        $textFields = [
            'text1' => $publication['text1'],
            'text2' => $publication['text2'],
            'text3' => $publication['text3']
        ];

        if ($publication['publication']['pubStatus']['id'] == 'pub_s_new') {
            $base = [
                'id' => $publication['id'],
                'version' => $publication['version']
            ];

            $data = [
                'fields' => array_merge($textFields, $base)
            ];

            $form = 'Publication';
        } else {
            $data = [
                'readOnlyText' => $textFields
            ];

            $form = 'PublicationNotNew';
        }

        $data['readOnly'] = $readOnly;

        $form = $this->generateFormWithData($form, 'processSave', $data);

        if ($this->isSaved) {
            return $this->redirectToIndex();
        }

        //having read only fields means that they aren't populated in the event of a post so we need to do it here
        if ($this->getRequest()->isPost()) {
            $data = array_merge(
                $data,
                (array)$this->params()->fromPost(),
                $this->fieldValues
            );

            $form->setData($data);
        }

        $view = $this->getViewWithLicence();

        $this->getServiceLocator()->get('viewHelperManager')
            ->get('placeholder')
            ->getContainer('form')
            ->set($form);

        $view->setTemplate('pages/crud-form');

        return $this->renderView($view);
    }

    /**
     * Specific save processing functionality
     *
     * @param array $data
     * @return int
     */
    public function processSave($data)
    {
        $saveData = [
            'text1' => $data['fields']['text1'],
            'text2' => $data['fields']['text2'],
            'text3' => $data['fields']['text3'],
            'id' => $data['fields']['id'],
            'version' => $data['fields']['version']
        ];

        $publication = $this->getService();
        $publication->update($data['fields']['id'], $saveData);

        $this->isSaved = true;
    }

    /**
     * Redirect to ajax route
     * @return mixed|\Zend\Http\Response
     */
    public function redirectToIndex()
    {
        return $this->redirectToRouteAjax(null, ['action' => 'index', 'id' => null], ['code' => '303'], true);
    }

    public function addAction()
    {
        return false;
    }

    /**
     * @return string
     */
    protected function getService()
    {
        return $this->getServiceLocator()->get('DataServiceManager')->get('Common\Service\Data\PublicationLink');
    }
}
