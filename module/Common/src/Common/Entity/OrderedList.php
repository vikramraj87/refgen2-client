<?php
namespace Common\Entity;
use \ArrayAccess;
use \Iterator;
use \Countable;

class OrderedList  implements ArrayAccess, Iterator, Countable
{
    /** @var array associative array to be ordered */
    protected $data = array();

    /** @var array order of the keys */
    protected $order = array();

    /** @var int */
    protected $index = 0;

    /**
     * @param array $data
     */
    public function setData(array $data = array())
    {
        $this->data = array();
        $this->order = array();
        if(!empty($data)) {
            foreach($data as $k => $v) {
                $this->data[(string) $k] = $v;
            }
            $this->order = array_keys($this->data);
        }
    }

    /**
     * Returns the data in the order stored in the array order
     *
     * @return array
     */
    public function getData()
    {
        $data = array();
        foreach($this->order as $order => $id) {
            $data[$id] = $this->data[$id];
        }
        return $this->data;
    }


    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        $k = $this->order[$this->index];
        return $this->data[$k];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        ++$this->index;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->order[$this->index];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        if(!isset($this->order[$this->index])) {
            return false;
        }
        $k = $this->order[$this->index];
        return isset($this->data[$k]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        $k = $offset;
        if(is_int($offset)) {
            if(!isset($this->order[$offset])) {
                return false;
            }
            $k = $this->order[$offset];
        }
        return isset($this->data[$k]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        $k = $offset;
        if(is_int($offset)) {
            if(!isset($this->order[$offset])) {
                return null;
            }
            $k = $this->order[$offset];
        }
        if(!isset($this->data[$k])) {
            return null;
        }
        return $this->data[$k];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $offset = (string) $offset;
        if(!in_array($offset, $this->data)) {
            $this->order[] = $offset;
        }
        $this->data[$offset] = $value;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        if(!isset($this->data[$offset])) {
            return;
        }

        unset($this->data[$offset]);
        if(in_array($offset, $this->order)) {
            $index = array_keys($this->order, $offset)[0];
            array_splice($this->order, $index, 1);
        }

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
        return count($this->data);
    }

    /**
     * Changes the order of the element with the key value
     *
     * @param string $key
     * @param int $newIndex
     * @return bool status of the change
     * @throws \InvalidArgumentException
     */
    public function changeKeyOrder($key, $newIndex)
    {
        $key = (string) $key;
        if(!is_string($key)) {
            throw new \InvalidArgumentException('key value must be a string in associative array');
        }
        if(!in_array($key, $this->order)) {
            return false;
        }
        $oldIndex = array_keys($this->order, $key)[0];

        return $this->changeIndex($oldIndex, $newIndex);
    }

    /**
     * Changes the order of the element with the index given as oldIndex
     *
     * @param int $oldIndex
     * @param int $newIndex
     * @return bool
     * @throws \OutOfBoundsException
     */
    public function changeIndex($oldIndex, $newIndex)
    {
        $oldIndex = (int) $oldIndex;
        $newIndex = (int) $newIndex;

        $maxIndex = count($this->order) - 1;

        if($oldIndex > $maxIndex || $oldIndex < 0) {
            throw new \OutOfBoundsException("oldIndex value is out of bounds");
        }

        if($newIndex < 0) {
            throw new \OutOfBoundsException("newIndex value is out of bounds");
        }

        $removedElement = array_splice($this->order, $oldIndex, 1);

        $maxIndex = count($this->order) - 1;
        if($newIndex > $maxIndex) {
            $this->order[] = $removedElement[0];
        } else {
            array_splice($this->order, $newIndex, 0, $removedElement);
        }
        return true;
    }

} 