<?php
/**
 * Created by PhpStorm.
 * User: vikramraj
 * Date: 21/01/14
 * Time: 5:36 PM
 */

namespace ApplicationTest\Entity;

use Application\Entity\OrderedList;
use PHPUnit_Framework_TestCase;

class OrderedListTest extends PHPUnit_Framework_TestCase
{
    protected $data;
    protected $list;

    public function setUp()
    {
        $this->data = array(
            'one'   => 'First',
            'two'   => 'Second',
            'three' => 'Three',
            'four'  => 'Fourth',
            'five'  => 'Fifth',
            'six'   => 'Sixth',
            'seven' => 'Seventh',
            'eight' => 'Eighth',
            'nine'  => 'Nineth',
            'ten'   => 'Tenth'
        );
        $this->list = new OrderedList();
        $this->list->setData($this->data);

    }

    public function testSettingDataAndCountableInterface ()
    {
        $this->assertEquals(count($this->data), count($this->list));
    }

    public function testingForEachIteration()
    {
        $this->checkIteration($this->list);
        $this->checkIteration($this->list);
    }

    public function testArrayAccess()
    {
        foreach($this->data as $key => $value) {
            $this->assertEquals($value, $this->list[$key]);
        }
        $i = 0;
        foreach($this->data as $key => $value) {
            $this->assertEquals($value, $this->list[$i]);
            $i++;
        }
    }


    protected function checkIteration(OrderedList $list)
    {
        reset($this->data);
        foreach($list as $key => $value) {
            $expectedValue = current($this->data);
            $expectedKey   = key($this->data);

            $this->assertEquals($expectedValue, $value);
            $this->assertEquals($expectedKey, $key);
            next($this->data);
        }
    }
} 