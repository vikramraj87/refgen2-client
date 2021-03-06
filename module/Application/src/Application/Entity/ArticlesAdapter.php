<?php
namespace Application\Entity;

use Zend\Paginator\Adapter\AdapterInterface;

class ArticlesAdapter implements AdapterInterface
{
    protected $articles = array();
    protected $count    = 0;

    public function __construct(array $articles = array())
    {
        $this->articles = $articles;
        $this->count    = count($articles);
    }

    /**
     * Returns an collection of items for a page.
     *
     * @param  int $offset Page offset
     * @param  int $itemCountPerPage Number of items per page
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        return $this->articles;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return $this->count;
    }

    public function setCount($count)
    {
        $count = (int) $count;
        $this->count = $count;
    }

} 