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

    /** @var OrderedList  */
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

    public function testArraySet()
    {
        $this->list['eleven'] = 'Eleventh';
        $this->assertEquals(11, count($this->list));
        $this->assertEquals('Eleventh', $this->list[10]);

        $this->list['two'] = 2;
        $this->assertEquals(11, count($this->list));
        $this->assertEquals(2, $this->list[1]);
    }

    public function testArrayUnset()
    {
        $initialCount = count($this->list);
        unset($this->list['eleven']);
        $afterCount = count($this->list);
        $this->assertEquals($initialCount, $afterCount);

        $initialCount = count($this->list);
        $initialValueBeforeIndex = $this->list[3];
        $initialValueAtIndex     = $this->list[4];
        $initalValueAfterIndex   = $this->list[7];

        unset($this->list['five']);

        $this->assertEquals($initialCount - 1, count($this->list));
        $this->assertEquals($initialValueBeforeIndex, $this->list[3]);
        $this->assertEquals($initalValueAfterIndex, $this->list[6]);
        $this->assertFalse(isset($this->list['five']));
        $this->assertTrue(isset($this->list[4]));
        $this->assertEquals('Sixth', $this->list[4]);
    }

    public function testChangeIndex()
    {
        $expectedData = array(
            'one'   => 'First',
            'two'   => 'Second',
            'eight' => 'Eighth',
            'three' => 'Three',
            'four'  => 'Fourth',
            'five'  => 'Fifth',
            'six'   => 'Sixth',
            'seven' => 'Seventh',
            'nine'  => 'Nineth',
            'ten'   => 'Tenth'
        );
        $initialCount = count($this->list);
        $this->list->changeIndex(7,2);
        $this->assertEquals($initialCount, count($this->list));
        $this->checkIteration($this->list, $expectedData);

        $expectedData = array(
            'ten'   => 'Tenth',
            'one'   => 'First',
            'two'   => 'Second',
            'eight' => 'Eighth',
            'three' => 'Three',
            'four'  => 'Fourth',
            'five'  => 'Fifth',
            'six'   => 'Sixth',
            'seven' => 'Seventh',
            'nine'  => 'Nineth'
        );
        $initialCount = count($this->list);
        $this->list->changeIndex(9,0);
        $this->assertEquals($initialCount, count($this->list));
        $this->checkIteration($this->list, $expectedData);

        $expectedData = array(
            'one'   => 'First',
            'two'   => 'Second',
            'eight' => 'Eighth',
            'three' => 'Three',
            'four'  => 'Fourth',
            'five'  => 'Fifth',
            'six'   => 'Sixth',
            'seven' => 'Seventh',
            'nine'  => 'Nineth',
            'ten'   => 'Tenth',
        );
        $initialCount = count($this->list);
        $this->list->changeIndex(0,9);
        $this->assertEquals($initialCount, count($this->list));
        $this->checkIteration($this->list, $expectedData);

        $expectedData = array(
            'two'   => 'Second',
            'eight' => 'Eighth',
            'three' => 'Three',
            'four'  => 'Fourth',
            'five'  => 'Fifth',
            'six'   => 'Sixth',
            'seven' => 'Seventh',
            'nine'  => 'Nineth',
            'ten'   => 'Tenth',
            'one'   => 'First'
        );
        $initialCount = count($this->list);
        $this->list->changeIndex(0,19);
        $this->assertEquals($initialCount, count($this->list));
        $this->checkIteration($this->list, $expectedData);
    }

    public function testChangeKeyOrder()
    {

        $expectedData = array(
            'one'   => 'First',
            'two'   => 'Second',
            'eight' => 'Eighth',
            'three' => 'Three',
            'four'  => 'Fourth',
            'five'  => 'Fifth',
            'six'   => 'Sixth',
            'seven' => 'Seventh',
            'nine'  => 'Nineth',
            'ten'   => 'Tenth'
        );
        $initialCount = count($this->list);
        $this->list->changeKeyOrder('eight',2);
        $this->assertEquals($initialCount, count($this->list));
        $this->checkIteration($this->list, $expectedData);

        $expectedData = array(
            'ten'   => 'Tenth',
            'one'   => 'First',
            'two'   => 'Second',
            'eight' => 'Eighth',
            'three' => 'Three',
            'four'  => 'Fourth',
            'five'  => 'Fifth',
            'six'   => 'Sixth',
            'seven' => 'Seventh',
            'nine'  => 'Nineth'
        );
        $initialCount = count($this->list);
        $this->list->changeKeyOrder('ten',0);
        $this->assertEquals($initialCount, count($this->list));
        $this->checkIteration($this->list, $expectedData);

        $expectedData = array(
            'one'   => 'First',
            'two'   => 'Second',
            'eight' => 'Eighth',
            'three' => 'Three',
            'four'  => 'Fourth',
            'five'  => 'Fifth',
            'six'   => 'Sixth',
            'seven' => 'Seventh',
            'nine'  => 'Nineth',
            'ten'   => 'Tenth',
        );
        $initialCount = count($this->list);
        $this->list->changeKeyOrder('ten',9);
        $this->assertEquals($initialCount, count($this->list));
        $this->checkIteration($this->list, $expectedData);

        $expectedData = array(
            'two'   => 'Second',
            'eight' => 'Eighth',
            'three' => 'Three',
            'four'  => 'Fourth',
            'five'  => 'Fifth',
            'six'   => 'Sixth',
            'seven' => 'Seventh',
            'nine'  => 'Nineth',
            'ten'   => 'Tenth',
            'one'   => 'First'
        );
        $initialCount = count($this->list);
        $this->list->changeKeyOrder('one',19);
        $this->assertEquals($initialCount, count($this->list));
        $this->checkIteration($this->list, $expectedData);

    }

    protected function checkIteration(OrderedList $list, $data = array())
    {
        if(empty($data)) {
            reset($this->data);
            $data = $this->data;
        }
        foreach($list as $key => $value) {
            $expectedValue = current($data);
            $expectedKey   = key($data);

            $this->assertEquals($expectedValue, $value);
            $this->assertEquals($expectedKey, $key);
            next($data);
        }
    }

} 