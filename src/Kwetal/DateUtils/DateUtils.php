<?php

namespace Kwetal\DateUtils;

/**
 * Class DateUtils
 * @package Kwetal\DateUtils
 */
class DateUtils
{
    const EASTER_JULIAN = 1;
    const EASTER_GREGORIAN = 3;

    protected static $validWeekdays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    /**
     * Returns the nth weekday in a given month.
     *
     * @param int $year
     * @param int $month
     * @param string $weekday
     * @param int $num
     * @param \DateTime $start
     *
     * @throws \InvalidArgumentException
     *
     * @return \DateTime
     */
    public static function getNthWeekdayInMonth($year, $month, $weekday, $num = 1, \DateTime $start = null)
    {
        if (!in_array($weekday, self::$validWeekdays)) {
            throw new \InvalidArgumentException("Invalid value for weekday (must be one of 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun').");
        }

        try {
            $day = new \DateTime(sprintf('%s-%s-01', $year, $month));
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        if ($start) {
            if ($start->format('Y') !== (string)$year || $start->format('n') !== (string)$month) {
                throw new \InvalidArgumentException('Invalid $start parameter (year and month must match with other parameters).');
            }
            $day = $start;
        }

        $counter = 0;
        while (true) {
            if ($day->format('n') !== (string)$month) {
                return null;
            }

            if ($day->format('D') === $weekday) {
                ++$counter;
            }

            if ($counter == $num) {
                break;
            }

            $day = $day->add(new \DateInterval('P1D'));
        }

        return $day;
    }

    /**
     * Returns the next weekday from the given Date, can be a date in a later month/
     *
     * @param \DateTime $origDay
     * @param string $weekday
     * @return \DateTime
     */
    public static function getNextWeekday(\DateTime $origDay, $weekday)
    {
        if (!in_array($weekday, self::$validWeekdays)) {
            throw new \InvalidArgumentException("Invalid value for weekday (must be one of 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun').");
        }

        $day = clone $origDay;

        while (true) {
            if ($day->format('D') == $weekday) {
                return $day;
            }

            $day->add(new \DateInterval('P1D'));
        }
    }

    /**
     * Returns the last weekday of the month
     *
     * @param int $year
     * @param int $month
     * @param string $weekday
     *
     * @throws InvalidArgumentException
     *
     * @return \DateTime
     */
    public static function getLastWeekdayOfMonth($year, $month, $weekday)
    {
        if (!in_array($weekday, self::$validWeekdays)) {
            throw new \InvalidArgumentException("Invalid value for weekday (must be one of 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun').");
        }

        try {
            $day = new \DateTime(sprintf('%s-%s-%s', $year, $month, self::getLastDayOfMonth($year, $month)->format('d')));
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        while (true) {
            if ($day->format('D') == $weekday) {
                break;
            }

            $day->sub(new \DateInterval('P1D'));
        }

        return $day;
    }

    /**
     * Returns the last day of the month
     *
     * @param int $year
     * @param int $month
     *
     * @throws \InvalidArgumentException
     *
     * @return \DateTime
     */
    public static function getLastDayOfMonth($year, $month)
    {
        try {
            $date = new \DateTime(sprintf('%s-%s-01', $year, $month));
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        return $date->add(new \DateInterval('P1M'))
            ->sub(new \DateInterval('P1D'));

        return $date;
    }

    /**
     * Returns the Easter sunday in the given year
     *
     * @param int $year
     * @return \DateTime
     */
    public static function getEasterSunday($year, $method = self::EASTER_GREGORIAN)
    {
        if (!is_int($year)) {
            throw new \InvalidArgumentException('parameter $year must be an integer');
        }

        if ($method == self::EASTER_JULIAN) {
            return self::getEasterSundayJulian($year);
        }

        $num = easter_days($year);

        $date = new \DateTime(sprintf('%s-03-21', $year));

        $date->add(new \DateInterval(sprintf('P%sD', $num)));

        return $date;
    }

    /**
     * Returns the Easter sunday in the given year, using the Julian Calendar
     *
     * @param int $year
     * @return \DateTime
     */
    private static function getEasterSundayJulian($year)
    {
        $paschalFullMoon = self::getJulianPaschalFullMoon(self::getGoldenNumber($year));

        $day = new \DateTime(sprintf('%s-%s', $year,$paschalFullMoon));

        if ($day->format('D') === 'Sun') {
            $day->add(new \DateInterval('P1D'));
        }

        return self::getNextWeekday($day, 'Sun');
    }

    /**
     * Calculate the so called golden number for a year.
     *
     * @param int $year
     * @return int
     */
    private static function getGoldenNumber($year)
    {
        return ($year % 19) + 1;
    }

    /**
     * Return the day and month for the first full moon after 21th March; The Paschal Full Moon
     *
     * @param int $goldenYear
     * @return string
     */
    private static function getJulianPaschalFullMoon($goldenYear)
    {
        $lookUp = [
            1 => '04-05',
            2 => '03-25',
            3 => '04-13',
            4 => '04-02',
            5 => '03-22',
            6 => '04-10',
            7 => '03-30',
            8 => '04-18',
            9 => '04-07',
            10 => '03-27',
            11 => '04-15',
            12 => '04-04',
            13 => '03-24',
            14 => '04-12',
            15 => '04-01',
            16 => '03-21',
            17 => '04-09',
            18 => '03-28',
            19 => '04-17',
        ];

        return $lookUp[$goldenYear];
    }
}
