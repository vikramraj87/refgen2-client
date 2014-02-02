<?php
namespace Citation\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Citation\Service\CitationService;


class Citations extends AbstractHelper
{
    protected $service;

    public function __construct(CitationService $service)
    {
        $this->service = $service;
    }

    public function __invoke()
    {
        $list = $this->service->getAll();

        if(count($list)) {
            return $this->getView()->partial('citation/partial/_collection.phtml', array(
                'list' => $list,
                'activeList' => $this->service->getActiveListId(),
                'changed' => $this->service->isChanged()
            ));
        } else {
            return $this->getView()->partial('citation/partial/_empty.phtml');
        }
    }
} 