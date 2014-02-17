<?php
namespace ApplicationTest\View\Helper;

use Application\View\Helper\SelfUrl;
use ApplicationTest\Bootstrap;
use PHPUnit_Framework_TestCase;
use Zend\Http\Request;
use Zend\Mvc\Router\Http\TreeRouteStack as Router;
use Zend\Mvc\Router\RouteMatch;
use Zend\View\Helper\Url;

class SelfUrlTest extends PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    /** @var Router $router */
    protected $router;

    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $config = $this->serviceManager->get('config');
        $routerConfig = $config['router'];

        $this->router = Router::factory($routerConfig);
    }



    public function testTypicalUsage()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $request->setUri('/search?x=0&y=0&term=ca+lung');

        $helper = new SelfUrl($this->router, $request);
        $this->assertEquals('/search/ca+lung', $helper());

        $request->setUri('/search/ca+lung');

        $helper = new SelfUrl($this->router, $request);
        $this->assertEquals('/search/ca+lung', $helper());
    }

    public function testAtypicalUsage()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $request->setUri('/searches?x=0&y=0&term=ca+lung');

        $helper = new SelfUrl($this->router, $request);
        //$this->assertEquals('/searches?x=0&y=0&term=ca+lung', $helper());

    }

    public function testUrlWithRedirect()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $request->setUri('/citation/remove/24451063?redirect=/citation/get/6');

        $helper = new SelfUrl($this->router, $request);
        $this->assertEquals('/citation/remove/24451063', $helper());
    }

    public function testUrlWithHost()
    {
        $request = new Request();
        $request->setUri('http://refgen.loc/search/ca+lung');
        $request->setMethod(Request::METHOD_GET);

        $helper = new SelfUrl($this->router, $request);
        $this->assertEquals('/search/ca+lung', $helper());
    }
}
