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

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function  testGetNthWeekdayInMontInYearThrowsExceptionOnInvalidDayString()
    {
        DateUtils::getNthWeekdayInMonthAndYear(2014, 10, 'WTF', 1);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function  testGetNthWeekdayInMontInYearThrowsExceptionOnInvalidDate()
    {
        DateUtils::getNthWeekdayInMonthAndYear('I', 'am', 'Sun', 1);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function  testGetNthWeekdayInMontInYearThrowsExceptionOnDateMismatch()
    {
        DateUtils::getNthWeekdayInMonthAndYear(2014, 1, 'Sun', 1, new \DateTime('2010-05-01'));
    }

    /**
     * @test
     */
    public function  testGetNthWeekdayInMontInYearReturnsDateTime()
    {
        $object = DateUtils::getNthWeekdayInMonthAndYear(2014, 1, 'Mon', 1);

        $this->assertInstanceOf('DateTime', $object);
    }

    /**
     * @test
     */
    public function  testGetNthWeekdayInMontInYearReturnsNullWithInvalidNumParameter()
    {
        $object = DateUtils::getNthWeekdayInMonthAndYear(2014, 1, 'Mon', 8);

        $this->assertNull($object);
    }

    /**
     * @test
     * @dataProvider dateWithStartDateProvider
     */
    public function  testGetNthWeekdayInMontInYearWithStartDateReturnsCorrectDateTime($a, $b, $c, $d, $e, $expected)
    {
        $object = DateUtils::getNthWeekdayInMonthAndYear($a, $b, $c, $d, $e);

        $this->assertEquals($expected, $object->format('Y-m-d'));
    }

    /**
     * @test
     * @dataProvider dateProvider
     */
    public function  testGetNthWeekdayInMontInYearReturnsCorrectDateTime($a, $b, $c, $d, $expected)
    {
        $object = DateUtils::getNthWeekdayInMonthAndYear($a, $b, $c, $d);

        $this->assertEquals($expected, $object->format('Y-m-d'));
    }
}
