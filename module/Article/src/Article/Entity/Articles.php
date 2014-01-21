<?php
namespace Article\Entity;


use Countable;
use IteratorAggregate;
use ArrayIterator;
use ArrayAccess;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\HydratorInterface;

class Articles implements Countable, IteratorAggregate, ArrayAccess {
    /** @var Article[] */
    protected $data = array();

    /** @var HydratorInterface */
    protected $hydrator;

    public function setData($data = null)
    {
        if($data !== null) {
            if(is_array($data) && !empty($data)) {
                foreach($data as $article) {
                    if(is_array($article)) {
                        $article = $this->getHydrator()->hydrate($article, new Article());
                    }
                    if(!$article instanceof Article) {
                        throw new \InvalidArgumentException('The data provided cannot be converted into array of articles');
                    }
                    $this->data[$article->getId()] = $article;
                }
            }
        }

    }

    /**
     * @return Article[]
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * @return ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * @param string|int $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * @param string|int $offset
     * @return Article|null
     */
    public function offsetGet($offset)
    {
        if(isset($this->data[$offset])) {
            return $this->data[$offset];
        }
        return null;
    }

    /**
     * @param string|int $offset
     * @param Article $value
     * @throws \InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        if(is_array($value)) {
            $value = $this->getHydrator()->hydrate($value, new Article());
        }
        if(!$value instanceof Article) {
            throw new \InvalidArgumentException('value must be an instance of article or an array');
        }
        $this->data[$offset] = $value;
    }

    /**
     * @param string|int $offset
     */
    public function offsetUnset($offset)
    {
        if(isset($this->data[$offset])) {
            unset($this->data[$offset]);
        }
    }

    /**
     * @return HydratorInterface
     * @throws \RuntimeException
     */
    public function getHydrator()
    {
        if($this->hydrator == null) {
            $this->hydrator = new ClassMethods();
        }
        return $this->hydrator;
    }

    /**
     * @param HydratorInterface $hydrator
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }
}