<?php
namespace Citation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;


use Citation\Form\CreateForm;
use Citation\Form\OpenForm;
use Citation\Form\CitationFilter;
use Citation\Service\CitationService;
use Citation\Entity\Collection;

use \RuntimeException;

//todo: remove invalid server response exception
class IndexController extends AbstractActionController
{
    /** @var CitationService */
    protected $citationService = null;

    /**
     * Adds an article to active collection
     */
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

        return $this->redirect()->toUrl($redirectUrl);
    }

    /**
     * Removes an article to active collection
     */
    public function removeAction()
    {
        $id = $this->params()->fromRoute('id', 0);

        $redirectUrl = $this->params()->fromQuery('redirect', '/');

        if($id === 0) {
            $this->redirect()->toRoute('home');
        }

        $service = $this->getCitationService();
        $service->remove($id);

        return $this->redirect()->toUrl($redirectUrl);
    }

    public function newAction()
    {
        $redirect = $this->params()->fromQuery('redirect', '/');

        $request = $this->getRequest();
        if($this->getCitationService()->isChanged()) {
            if(!$request->isPost()) {
                $message = "Do you want to create a new list? All unsaved changes will be lost.";
                $layout = $this->layout();
                $layout->setTemplate('layout/single.phtml');
                return $this->displayConfirm($message, $redirect);
            }
            $post = $request->getPost()->toArray();
            if(!isset($post['action']) || $post['action'] != 'Yes') {
                return $this->redirect()->toUrl($redirect);
            }
        }
        $this->getCitationService()->newCollection();
        return $this->redirect()->toUrl($redirect);
    }

    public function openAction()
    {
        $request = $this->getRequest();
        $redirect = $this->params()->fromQuery('redirect', '/');
        $layout = $this->layout();
        $layout->setTemplate('layout/single.phtml');

        if($request->isPost()) {
            $data = $request->getPost()->toArray();
            if(isset($data['id'])) {
                if(isset($data['action-open'])) {
                    $this->getCitationService()->openCollection($data['id']);
                }
                return $this->redirect()->toUrl($redirect);
            }
        }
        if($this->getCitationService()->isChanged()) {
            if(!$request->isPost()) {
                $message = "Do you want to open an existing list? All unsaved changes will be lost.";
                return $this->displayConfirm($message, $redirect);
            }
            $post = $request->getPost()->toArray();
            if(!isset($post['action']) || $post['action'] != 'Yes') {
                return $this->redirect()->toUrl($redirect);
            }
        }
        $collections = $this->getCitationService()->getCollections();
        $form = new OpenForm();
        $form->get('id')->setValueOptions($collections);
        return array(
            'form' => $form,
        );
    }

    public function getAction()
    {
        $page = $this->params()->fromRoute('page');
        $id   = $this->params()->fromRoute('id');
        $service = $this->getCitationService();
        $collection = $service->getCollection($id);

        if(!$collection instanceof Collection) {
            throw new \RuntimeException('Invalid data returned from the server');
        }

        $viewModel = new ViewModel();
        $viewModel->collection = $collection;
        $count = count($collection);

        if($count > 0) {
            if($count === 1) {
                $viewModel->setTemplate('citation/index/single.phtml');
            } else {
                $viewModel->setTemplate('citation/index/multiple.phtml');

                $adapter = new ArrayAdapter($collection->getArticles());
                $paginator = new Paginator($adapter);
                $paginator->setCurrentPageNumber($page);
                $paginator->setItemCountPerPage(10);
                $viewModel->paginator = $paginator;
            }
        } else {
            $viewModel->setTemplate('citation/index/zero.phtml');
        }
        return $viewModel;
    }

    public function saveAction()
    {
        $redirectUrl = $this->params()->fromQuery('redirect', '/');
        $collection = $this->getCitationService()->getActiveCollection();
        if($collection->getName() == '') {
            return $this->redirect()->toUrl('/citation/save-as?redirect=' . $redirectUrl);
        }
        $response = $this->getCitationService()->saveCollection();
        if(is_array($response)) {
            return array(
                'response' => $response
            );
        }
        return $this->redirect()->toUrl($redirectUrl);
    }

    public function saveAsAction()
    {
        $redirect = $this->params()->fromQuery('redirect');
        $request = $this->getRequest();
        $layout = $this->layout();
        $layout->setTemplate('layout/single.phtml');

        $form = new CreateForm();

        if($request->isPost()) {
            $data = $request->getPost()->toArray();
            if(isset($data['action-cancel'])) {
                return $this->redirect()->toUrl($redirect);
            }
            $filter = new CitationFilter();
            $form->setInputFilter($filter);
            $form->setData($data);

            if($form->isValid()) {
                $data = $form->getData();
                $response = $this->getCitationService()->saveCollectionAs($data['name']);
                if($response === true) {
                    return $this->redirect()->toUrl($redirect);
                }
                $form->setMessages($response);
            }
        }

        return array(
            'form' => $form
        );
    }

    public function deleteAction()
    {
        $redirect = $this->params()->fromQuery('redirect', '/');

        $layout = $this->layout();
        $layout->setTemplate('layout/single.phtml');

        $collection = $this->getCitationService()->getActiveCollection();
        if($collection->getName() === "" && count($collection) === 0) {
            return $this->redirect()->toUrl($redirect);
        }

        $request = $this->getRequest();
        if(!$request->isPost()) {
            $collection = $this->getCitationService()->getActiveCollection();
            $message = "Do you want to delete the list \"" . $collection->getName() . "\"?";
            return $this->displayConfirm($message, $redirect);
        }

        $data = $request->getPost()->toArray();
        if(isset($data['action']) && $data['action'] == 'No') {
            return $this->redirect()->toUrl($redirect);
        }

        $response = $this->getCitationService()->deleteCollection();
        return $this->redirect()->toUrl('/');
    }

    protected function displayConfirm($message = '', $redirect = '/')
    {
        $viewModel = new ViewModel();

        $viewModel->setTemplate('citation/index/confirm.phtml');
        $viewModel->setVariables(array(
            'message'  => $message,
            'redirect' => $redirect
        ));
        return $viewModel;
    }


    /**
     * @return CitationService
     */
    protected function getCitationService()
    {
        if($this->citationService === null) {
            $this->citationService = $this->getServiceLocator()->get('CitationService');
        }
        return $this->citationService;
    }
}