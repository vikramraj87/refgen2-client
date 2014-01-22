<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Truncate extends AbstractHelper
{
    public function __invoke($str, $limit = 300, $break = " ", $trailing = "...")
    {
        $truncated = $str;
        if(strlen($truncated) > $limit) {
            $truncated = substr($truncated, 0, $limit - 1);
            $bp = strrpos($truncated, $break);
            $truncated = substr($truncated, 0, $bp) . $trailing;
        }
        return $truncated;
    }
} 