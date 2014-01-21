<?php
namespace Application\Controller;

use Article\Entity\Articles;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Controller\AbstractActionController;
use Api\Client\ApiClient;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\View\Model\ViewModel;

class SearchController extends AbstractActionController
{
    public function indexAction()
    {
        /** @var string $term search term as part of url or query string */
        $term = $this->params()->fromRoute('term', '');
        $term = $term ?: $this->params()->fromQuery('term', '');

        // filter search term
        $term = trim(strip_tags($term));

        // if empty redirect to home page
        if($term === '') {
            $this->redirect()->toRoute('home');
        }

        $page = $this->params()->fromRoute('page', '');
        $page = $page ?: $this->params()->fromQuery('page', 1);


        /** @var null|array|Articles $result */
        $result = null;

        if(preg_match('/^\d+$/', $term)) {
            $result = ApiClient::get($term);
        } else {
            $result = ApiClient::getByTerm($term, $page);
        }

        if(!is_array($result)) {
            // TODO:
            // something went wrong
            // redirect the user to error page
            // based on the log. find out the type
            // of errors
        }

        $this->layout()->setVariable('query', $term);

        $viewModel = new ViewModel();

        if(empty($result)) {
            // TODO:
            // log the term
            $logger = new Logger();
            $logger->addWriter(new Stream('data/logs/zeroresults.log'));

            $logger->debug(sprintf('Searched with term: %s', $term));

            $viewModel->setTemplate('application/search/no-results');
            return $viewModel;
        }

        $articles = new Articles();
        $articles->setData($result);

        if(count($articles) === 1) {
            $viewModel->setTemplate('application/search/single-result');
            $article = $articles->getData()[0];
            $viewModel->setVariable('article', $article);
            return $viewModel;
        }

        $viewModel->setVariable('articles', $articles);
        return $viewModel;
    }
}