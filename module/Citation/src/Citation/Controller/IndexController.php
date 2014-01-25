<?php
namespace Citation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Api\Client\ApiClient;
use Common\Entity\OrderedList;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\View\Model\ViewModel;
use Citation\Service\CitationService;

class IndexController extends AbstractActionController
{
    protected $activeList = null;
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