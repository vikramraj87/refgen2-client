<?php
namespace Application\Controller;

use Article\Entity\Article;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Controller\AbstractActionController;
use Api\Client\ApiClient;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
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



        $result = null;

        if(preg_match('/^\d+$/', $term)) {
            $result = ApiClient::get($term);
        } else {
            $result = ApiClient::getByTerm($term, $page);
        }

        if(!is_array($result)  || empty($result)) {
            // TODO:
            // something went wrong
            // redirect the user to error page
            // based on the log. find out the type
            // of errors
        }

        $this->layout()->setVariable('query', $term);

        $count = $result['count'];

        $viewModel = new ViewModel();

        if($count === 0) {
            // zero results
            // log the term
        } else if($count === 1) {
            $article = $result['results'][0];
            $hydrator = new ClassMethodsHydrator;
            $article = $hydrator->hydrate($article, new Article());
            $viewModel->setTemplate('application/search/single-result');
            $viewModel->article = $article;
        } else {

        }
        return $viewModel;
        /*




        $hydrator = new ClassMethodsHydrator;

        if(count($result) == 1) {
            $article = $hydrator->hydrate($result[0], new Article());
            $viewModel->setTemplate('application/search/single-result');
            $viewModel->article = $article;
        } else {

        }

        /*
        if(count($articles) === 1) {
            $viewModel->setTemplate('application/search/single-result');
            $tmp = $articles->getData();
            $article = $tmp[0];
            var_dump($article);
            $viewModel->setVariable('article', $article);
            return $viewModel;
        }

        $viewModel->setVariable('articles', $articles);

        */
        //return $viewModel;
    }
}