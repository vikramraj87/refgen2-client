<?php
namespace Application\Controller;

use Article\Entity\Article;
use Zend\Mvc\Controller\AbstractActionController;
use Api\Client\ApiClient;
use Zend\View\Model\ViewModel;
use Api\Exception\ApiException;

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

        $result = null;

        if(preg_match('/^\d+$/', $term)) {
            $result = ApiClient::getArticle($term);
        } else {
            $result = ApiClient::getArticles($term, $page);
        }

        if(!is_array($result) || empty($result)) {
            throw new ApiException('Api server error: no resultset returned');
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
                $article = $hydrator->hydrate($articleData, $di->get('Article\Entity\Article'));
                $articles[] = $article;
            }
            $viewModel->articles = $articles;
        }
        return $viewModel;
    }
}