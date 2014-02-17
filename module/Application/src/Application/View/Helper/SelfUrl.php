<?php
namespace Application\View\Helper;

use Zend\Http\Request;
use Zend\Mvc\Router\Http\TreeRouteStack as Router;
use Zend\View\Helper\AbstractHelper;
use Zend\Mvc\Router\Http\RouteMatch;

class SelfUrl extends AbstractHelper
{
    /** @var \Zend\Mvc\Router\Http\TreeRouteStack  */
    protected $router;

    /** @var \Zend\Http\Request  */
    protected $request;

    public function __construct(Router $router, Request $request)
    {
        $this->router  = $router;
        $this->request = $request;
    }

    public function __invoke($params = array())
    {
        $uri = $this->request->getUri()->getPath();
        $match = $this->router->match($this->request);
        if($match != null) {
            $queryString = $this->request->getUri()->getQuery();
            if(!empty($queryString)) {
                $qPairs = array();
                $q = array();

                $qPairs = explode('&', $queryString);
                foreach($qPairs as $pair) {
                    list($k, $v) = explode('=', $pair, 2);
                    $q[$k] = $v;
                }
                if(!empty($q)) {
                    if(!empty($params)) {
                        foreach($params as $k => $v) {
                            $q[$k] = $v;
                        }
                    }
                    $route = $this->router->getRoute($match->getMatchedRouteName());
                    $uri = $route->assemble($q);
                }
            }
        }
        return $uri;
    }
} 