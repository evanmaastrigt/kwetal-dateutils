<?php

namespace Kwetal\DateUtils\DateTime;

use PHPUnit_Framework_TestCase;

class DateTimeTest extends PHPUnit_Framework_TestCase
{
    public $object;

    public function setUp()
    {
        $this->object = new DateTime();
    }

    public function testDateTimeImplementDateTimeInterface()
    {
        $this->assertInstanceOf('\DateTimeInterface', $this->object);
    }

    public function testDateTimeHasFluentInterface()
    {
        $class = $this->object->setLabels([]);
        $this->assertSame($class, $this->object);

        $class = $this->object->addLabel('A label');
        $this->assertSame($class, $this->object);
    }

    public function testDateTimeHasNoLabelsAtStartOfLive()
    {
        $this->assertCount(0, $this->object->getLabels());
    }

    public function testDateTimeAddsLabels()
    {
        $this->object->addLabel('A label');
        $this->assertCount(1, $this->object->getLabels());

        $this->object->addLabel('Another label');
        $this->assertCount(2, $this->object->getLabels());

        $this->assertContains('A label', $this->object->getLabels());
        $this->assertContains('Another label', $this->object->getLabels());
    }

    public function testDateTimeSetsLabelsByArray()
    {
        $array = ['A label', 'Another Label'];
        $this->object->setLabels($array);

        $this->assertEquals($array, $this->object->getLabels());
    }
} 
