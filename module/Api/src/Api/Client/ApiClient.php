<?php
namespace Api\Client;

use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Json\Decoder as JsonDecoder;
use Zend\Json\Json;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class ApiClient
{
    protected static $client = null;

    protected static $host = 'http://api.kivirefgen.com';
    protected static $getList = '/pubmed/term/%s/%d';
    protected static $get = '/pubmed/id/%s';


    protected static function getClientInstance()
    {
        if(self::$client === null) {
            self::$client = new Client();
            self::$client->setEncType(Client::ENC_URLENCODED);
        }
        return self::$client;
    }

    public static function get($id)
    {
        $uri = self::$host . sprintf(self::$get, $id);
        return self::doRequest($uri);
    }

    public static function getByTerm($term, $page = 1)
    {
        $page = (int) $page;
        $uri = self::$host . sprintf(self::$getList, $term, $page);
        return self::doRequest($uri);
    }

    protected static function doRequest($url, array $data = array(), $method = Request::METHOD_GET)
    {
        $client = self::getClientInstance();
        $client->setUri($url);
        $client->setMethod($method);

        if($data !== null) {
            $client->setParameterPost($data);
        }

        $response = $client->send();

        if($response->isSuccess()) {
            return JsonDecoder::decode($response->getBody(), Json::TYPE_ARRAY);
        } else {
            $logger = new Logger();
            $logger->addWriter(new Stream('data/logs/apiclient.log'));
            $logger->debug($response->getBody());
            return false;
        }
    }
}