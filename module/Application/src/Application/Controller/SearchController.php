<?php
namespace Application\Controller;

use Article\Entity\Article;
use Zend\Mvc\Controller\AbstractActionController;
use Api\Client\ApiClient;
use Zend\View\Model\ViewModel;
use Api\Exception\ApiException;
use Application\Entity\ArticlesAdapter;
use Zend\Paginator\Paginator;
use Api\Service\ApiService;

class SearchController extends AbstractActionController
{
    /** @var null|ApiService */
    protected $apiService = null;

    public function indexAction()
    {
        /** @var string $term search term as part of url or query string */
        $term = $this->params()->fromRoute('term', '');
        $term = $term ?: $this->params()->fromQuery('term', '');

        // filter search term
        $term = trim(strip_tags($term));
        $term = urldecode($term);

        // if empty redirect to home page
        if($term === '') {
            $this->redirect()->toRoute('home');
        }

        $page = $this->params()->fromRoute('page', '');
        $page = $page ?: $this->params()->fromQuery('page');

        $result = null;

        if(preg_match('/^\d+$/', $term)) {
            $result = $this->getApiService()->getArticle($term);
        } else {
            $result = $this->getApiService()->search($term, $page);
        }

        $this->layout()->setVariable('query', $term);

        $count = $result['count'];

        $viewModel = new ViewModel();

        if($count === 0) {
            /** @var \Zend\Log\Logger $logger */
            $logger = $this->getServiceLocator()->get('zero_results_log');

            $logger->debug(sprintf('Searched with %s', $term));

            $viewModel->setTemplate('application/search/no-results');
            return $viewModel;
        }

        /** @var \Zend\Stdlib\Hydrator\ClassMethods $hydrator */
        $hydrator = $this->getServiceLocator()->get('article_hydrator');

        /** @var \Zend\Di\Di $di */
        $di = $this->getServiceLocator()->get('app_di');

        if($count === 1) {
            /** @var array|Article $article */
            $article = $result['results'][0];
            $article = $hydrator->hydrate($article, $di->get('Article\Entity\Article'));
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