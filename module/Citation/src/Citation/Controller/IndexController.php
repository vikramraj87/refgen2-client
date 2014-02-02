<?php
namespace Citation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Citation\Form\CreateForm;
use Citation\Form\CitationFilter;
use Citation\Service\CitationService;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $citationService = null;

    public function addAction()
    {
        /** @var int|string $id */
        $id = $this->params()->fromRoute('id', 0);

        /** @var string $redirectUrl */
        $redirectUrl = $this->params()->fromQuery('redirect', '/');

        if($id === 0) {
            $this->redirect()->toRoute('home');
        }

        $service = $this->getCitationService();
        $service->add($id);

        $this->redirect()->toUrl($redirectUrl);
    }

    public function removeAction()
    {
        $id = $this->params()->fromRoute('id', 0);

        $redirectUrl = $this->params()->fromQuery('redirect', '/');

        if($id === 0) {
            $this->redirect()->toRoute('home');
        }

        $service = $this->getCitationService();
        $service->remove($id);

        $this->redirect()->toUrl($redirectUrl);
    }

    public function getAction()
    {
        $id = $this->params()->fromRoute('id');

        if($id === 0) {
            $this->redirect()->toRoute('home');
        }

        $service = $this->getCitationService();
        $collection = $service->getCollection($id);
        return array(
            'collection' => $collection
        );
    }

    public function createAction()
    {
        $form = new CreateForm();
        return array(
            'form' => $form
        );
    }

    public function saveAction()
    {
        $request = $this->getRequest();
        if(!$request->isPost()) {
            return $this->redirect()->toRoute('citation/default', array('action' => 'create'));
        }
        $data = $request->getPost()->toArray();

        $form   = new CreateForm();
        $filter = new CitationFilter();
        $form->setInputFilter($filter);

        $form->setData($data);

        if(!$form->isValid()) {
            $viewModel = new ViewModel();
            $viewModel->setTemplate('citation/index/create.phtml');
            $viewModel->form = $form;
            $viewModel->msg = $form->getMessages();
            return $viewModel;
        }

    }

    public function updateAction()
    {

    }

    /**
     * @return \Citation\Service\CitationService
     */
    protected function getCitationService()
    {
        if($this->citationService === null) {
            $this->citationService = $this->getServiceLocator()->get('citation_service');
        }
        return $this->citationService;
    }
} 