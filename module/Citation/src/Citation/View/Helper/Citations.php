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
        $output = '<div id="citations-preview" class="widget">';

        $output .= '<h2>Citations</h2>';

        $list = $this->service->getAll();

        if(count($list)) {
            $output .= '<ol id="citations">';
            foreach($list as $article) {
                $redirectUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

                $remUrl = $this->getView()->url('citation\remove', array('id' => $article->getId()));
                $remUrl .= '?redirect=' . $redirectUrl;

                $output .= '<li>' . $article->getCitation();
                $output .= '<a href=' . $remUrl . ' data-id="' . $article->getId();
                $output .= '">x</a></li>';
            }
            $output .= '</ol>';
        } else {
            $output .= '<p>Add references to build a numbered list. ';
            $output .= 'Click the "+" button of the article to add the corresponding ';
            $output .= 'reference to your collection.</p>';
        }
        $output .= '</div>';
        return $output;
    }
} 