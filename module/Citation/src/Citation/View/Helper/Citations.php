<?php
namespace Citation\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Citation\Service\CitationService;
use Article\Entity\Article;


class Citations extends AbstractHelper
{
    protected $service;

    public function __construct(CitationService $service)
    {
        $this->service = $service;
    }

    public function __invoke()
    {
        return $this;
    }

    public function render()
    {
        $collection = $this->service->getActiveCollection();

        if(count($collection)) {
            return $this->getView()->partial('citation/partial/_collection.phtml', array(
                'collection' => $collection
            ));
        } else {
            return $this->getView()->partial('citation/partial/_empty.phtml', array(
                'collection' => $collection
            ));
        }
    }

    public function isAdded(Article $article)
    {
        return $this->service->isAdded($article);
    }
} 