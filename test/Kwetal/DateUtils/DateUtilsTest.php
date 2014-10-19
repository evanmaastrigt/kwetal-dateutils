<?php

namespace Kwetal\DateUtils;

use PHPUnit_Framework_TestCase;


class DateUtilsTest extends PHPUnit_Framework_TestCase
{
    public function dateProvider()
    {
        return [
            [2014, 1, 'Mon', 1, '2014-01-06'],
            [2014, 2, 'Mon', 1, '2014-02-03'],
            [2012, 2, 'Wed', 5, '2012-02-29'],
            [2014, 3, 'Tue', 2, '2014-03-11'],
            [2014, 4, 'Tue', 2, '2014-04-08'],
            [2014, 5, 'Wed', 3, '2014-05-21'],
            [2014, 6, 'Wed', 3, '2014-06-18'],
            [2014, 7, 'Thu', 4, '2014-07-24'],
            [2014, 8, 'Thu', 4, '2014-08-28'],
            [2014, 9, 'Fri', 4, '2014-09-26'],
            [2014, 10, 'Fri', 5, '2014-10-31'],
            [2014, 11, 'Sat', 1, '2014-11-01'],
            [2014, 12, 'Sun', 1, '2014-12-07'],
        ];
    }

    public function dateWithStartDateProvider()
    {
        return [
            [2014, 1, 'Mon', 1, new \DateTime('2014-01-07'), '2014-01-13'],
            [2014, 2, 'Mon', 1, new \DateTime('2014-02-07'), '2014-02-10'],
            [2012, 2, 'Wed', 1, new \DateTime('2012-02-23'), '2012-02-29'],
            [2014, 3, 'Tue', 2, new \DateTime('2014-03-12'), '2014-03-25'],
            [2014, 4, 'Tue', 2, new \DateTime('2014-04-09'), '2014-04-22'],
            [2014, 5, 'Wed', 3, new \DateTime('2014-05-08'), '2014-05-28'],
            [2014, 6, 'Wed', 3, new \DateTime('2014-06-01'), '2014-06-18'],
            [2014, 7, 'Thu', 1, new \DateTime('2014-07-24'), '2014-07-24'],
            [2014, 8, 'Thu', 2, new \DateTime('2014-08-17'), '2014-08-28'],
            [2014, 9, 'Fri', 4, new \DateTime('2014-09-04'), '2014-09-26'],
            [2014, 10, 'Fri', 2, new \DateTime('2014-10-16'), '2014-10-24'],
            [2014, 11, 'Sat', 1, new \DateTime('2014-11-16'), '2014-11-22'],
            [2014, 12, 'Sun', 1, new \DateTime('2014-12-25'), '2014-12-28'],
        ];
    }

    public function monthProvider()
    {
        return [
            [2012, 1, '2012-01-31'],
            [2012, 2, '2012-02-29'],
            [2013, 2, '2013-02-28'],
            [2014, 2, '2014-02-28'],
            [2015, 2, '2015-02-28'],
            [2016, 2, '2016-02-29'],
            [2012, 3, '2012-03-31'],
            [2012, 4, '2012-04-30'],
            [2012, 5, '2012-05-31'],
            [2012, 6, '2012-06-30'],
            [2012, 7, '2012-07-31'],
            [2012, 8, '2012-08-31'],
            [2012, 9, '2012-09-30'],
            [2012, 10, '2012-10-31'],
            [2012, 11, '2012-11-30'],
            [2012, 12, '2012-12-31'],
        ];
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function  testGetNthWeekdayInMontInYearThrowsExceptionOnInvalidDayString()
    {
        DateUtils::getNthWeekdayInMonth(2014, 10, 'WTF', 1);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function  testGetNthWeekdayInMontInYearThrowsExceptionOnInvalidDate()
    {
        DateUtils::getNthWeekdayInMonth('I', 'am', 'Sun', 1);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function  testGetNthWeekdayInMontInYearThrowsExceptionOnDateMismatch()
    {
        DateUtils::getNthWeekdayInMonth(2014, 1, 'Sun', 1, new \DateTime('2010-05-01'));
    }

    /**
     * @test
     */
    public function  testGetNthWeekdayInMontInYearReturnsDateTime()
    {
        $object = DateUtils::getNthWeekdayInMonth(2014, 1, 'Mon', 1);

        $this->assertInstanceOf('DateTime', $object);
    }


    /**
     * @test
     */
    public function  testGetNthWeekdayInMontInYearReturnsNullWithInvalidNumParameter()
    {
        $object = DateUtils::getNthWeekdayInMonth(2014, 1, 'Mon', 8);

        $this->assertNull($object);
    }

    /**
     * @test
     * @dataProvider dateWithStartDateProvider
     */
    public function  testGetNthWeekdayInMontInYearWithStartDateReturnsCorrectDateTime($a, $b, $c, $d, $e, $expected)
    {
        $object = DateUtils::getNthWeekdayInMonth($a, $b, $c, $d, $e);

        $this->assertEquals($expected, $object->format('Y-m-d'));
    }

    /**
     * @test
     * @dataProvider dateProvider
     */
    public function  testGetNthWeekdayInMontInYearReturnsCorrectDateTime($a, $b, $c, $d, $expected)
    {
        $object = DateUtils::getNthWeekdayInMonth($a, $b, $c, $d);

        $this->assertEquals($expected, $object->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function testGetLastDayOfMonthReturnsDateTime()
    {
        $object = DateUtils::getLastDayOfMonth(2013, 2);

        $this->assertInstanceOf('DateTime', $object);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function testGetLastDayOfMonthTrowsExceptionOnInvalidInput()
    {
        DateUtils::getLastDayOfMonth('unit', 'test');
    }

    /**
     * @ptest
     * @dataProvider monthProvider
     */
    public function testGetLastDayOfMonthReturnsCorrectDate($a, $b, $expected)
    {
        $object = DateUtils::getLastDayOfMonth($a, $b);

        $this->assertEquals($expected, $object->format('Y-m-d'));
    }
}
