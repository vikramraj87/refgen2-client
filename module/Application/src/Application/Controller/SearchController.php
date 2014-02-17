<?php
namespace Application\Controller;

use Article\Entity\Article;
use Application\Entity\ArticlesAdapter;
use Api\Service\ApiService;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator;
use Zend\Stdlib\Hydrator\ClassMethods;

class SearchController extends AbstractActionController
{
    /** @var null|ApiService */
    protected $apiService = null;

    public function indexAction()
    {
        /** @var string $term search term as part of url or query string */
        $term = $this->params()->fromRoute('term', '') ?:
                $this->params()->fromQuery('term', '');

        // filter search term
        $term = trim(strip_tags($term));
        $term = urldecode($term);

        // if empty redirect to home page
        if($term === '') {
            $this->redirect()->toRoute('home');
        }

        // get the page number from route or query
        $page = $this->params()->fromRoute('page', '') ?:
                $this->params()->fromQuery('page');

        $result = null;

        if(preg_match('/^\d+$/', $term)) {
            $result = $this->getApiService()->getArticle($term);
        } else {
            $result = $this->getApiService()->search($term, $page);
        }

        $this->layout()->setVariable('query', $term);
        $viewModel = new ViewModel();

        $count = $result['count'];
        if($count === 0) {
            /** @var \Zend\Log\Logger $logger */
            $logger = $this->getServiceLocator()->get('ZeroResultsLogger');
            $logger->debug(sprintf('Searched with %s', $term));
            $viewModel->setTemplate('application/search/no-results');
            return $viewModel;
        }

        /** @var \Zend\Stdlib\Hydrator\ClassMethods $hydrator */
        $hydrator = new ClassMethods();

        /** @var \Zend\Di\Di $di */
        $di = $this->getServiceLocator()->get('app_di');

        if($count === 1) {
            /** @var Article $article */
            $article = $result['results'][0];
            $article = $hydrator->hydrate($article, $di->newInstance('Article\Entity\Article'));
            $viewModel->setTemplate('application/search/single-result');
            $viewModel->article = $article;

        } else {
            $articles = array();
            foreach($result['results'] as $articleData) {
                /** @var Article $article */
                $article = $hydrator->hydrate($articleData, $di->newInstance('Article\Entity\Article'));
                $articles[] = $article;
            }

            $paginationAdapter = new ArticlesAdapter($articles);
            $paginationAdapter->setCount($count);

            $paginator = new Paginator($paginationAdapter);
            $paginator->setCurrentPageNumber($page);

            $config = $this->getServiceLocator()->get('config');
            $resultsConfig = $config['results'];
            $paginator->setItemCountPerPage($resultsConfig['max_results']);
            $paginator->setPageRange($resultsConfig['page_range']);
            $viewModel->paginator = $paginator;
        }
        $viewModel->term = $term;
        return $viewModel;
    }

    public function getApiService()
    {
        if($this->apiService == null) {
            $this->apiService = $this->getServiceLocator()->get('ApiService');
        }
        return $this->apiService;
    }
}