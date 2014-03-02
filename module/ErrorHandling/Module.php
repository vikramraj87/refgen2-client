<?php
namespace ErrorHandling;

use Zend\Mvc\MvcEvent,
    Zend\EventManager\EventManager,
    Zend\View\Model\ViewModel;


class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        /** @var EventManager $events */
        $events = $e->getTarget()->getEventManager();

        //$events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onError'));
    }

    public function onError(MvcEvent $e)
    {
        if(!$e->isError()) {
            return;
        }

        $viewModel = $e->getResult();

        $layout = new ViewModel();
        $layout->setTemplate('layout/single');
        $layout->addChild($viewModel);
        $layout->setTerminal(true);
        $e->setViewModel($layout);

        $response = $e->getResponse();
        $response->setStatusCode(200);

        $e->setResponse($response);
        $e->setResult($layout);
        return false;
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}