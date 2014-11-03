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

    const MON = 'Mon';

    const TUE = 'Tue';

    const WED = 'Wed';

    const THU = 'Thu';

    const FRI = 'Fri';

    const SAT = 'Sat';

    const SUN = 'Sun';

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

        $interval = new \DateInterval('P1D');
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

            $day = $day->add($interval);
        }

        return $day;
    }

    /**
     * Returns the nth weekday before the given day
     *
     * @param \DateTime $originalDay
     * @param string $weekday
     * @param int $delta
     *
     * @throws \InvalidArgumentException
     *
     * @return \DateTime
     */
    public static function getNthWeekdayBefore(\DateTime $originalDay, $weekday, $delta = 1)
    {
        $day = clone $originalDay;

        if (!in_array($weekday, self::$validWeekdays)) {
            throw new \InvalidArgumentException("Invalid value for weekday (must be one of 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun').");
        }

        $interval = new \DateInterval('P1D');
        $counter = 0;
        while (true) {
            if ($day->format('D') === $weekday) {
                ++$counter;
            }

            if ($counter == $delta) {
                break;
            }

            $day = $day->sub($interval);
        }

        return $day;


    }

    /**
     * Returns the next weekday from the given Date, can be a date in a later month
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

        if ($method === self::EASTER_JULIAN) {
            return self::getEasterSundayJulian($year);
        }
        if ($method === self::EASTER_GREGORIAN) {
            return self::getEasterSundayGregorian($year);
        }

        throw new \InvalidArgumentException(
            sprintf(
                '$method must be either %s or %s',
                self::EASTER_GREGORIAN,
                self::EASTER_JULIAN
            )
        );
    }

    /**
     * Returns the Easter sunday in the given year, using the Julian Calendar
     *
     * @param int $year
     * @return \DateTime
     */
    private static function getEasterSundayJulian($year)
    {
        $paschalFullMoon = self::getJulianPaschalFullMoonForGoldenNumber(self::getGoldenNumber($year));

        $day = new \DateTime(sprintf('%s-%s', $year,$paschalFullMoon));

        return self::getEasterFromPaschalFullMoon($day);
    }

    /**
     * Returns the Easter sunday in the given year, using the Gregorian Calendar
     *
     * @param int $year
     * @return \DateTime
     */
    private static function getEasterSundayGregorian($year)
    {
        $goldenNumber = self::getGoldenNumber($year);
        $julianEpact = self::calculateJulianEpact($goldenNumber);
        $century = self::calculateCentury($year);
        $solarEquation = self::calculateSolarEquation($century);
        $lunarEquation = self::calculateLunarEquation($century);

        $gregorianEpact = $julianEpact - $solarEquation + $lunarEquation + 8;

        $gregorianEpact = self::normalizeGregorianEpact($gregorianEpact);

        $paschalFullMoon = self::getJulianPaschalFullMoonForEpact($gregorianEpact, $goldenNumber);

        $day = new \DateTime(sprintf('%s-%s', $year, $paschalFullMoon));

        return self::getEasterFromPaschalFullMoon($day);
    }

    /**
     * Calculate the Golden Number for a year.
     *
     * Considering that the relationship between the moon’s phases and the days of the year repeats itself
     * every 19 years, it is natural to associate a number between 1 and 19 with each year. This is the Golden Number.
     *
     * @link http://www.webexhibits.org/calendars/year-astronomy.html
     *
     * @param int $year
     * @return int
     */
    private static function getGoldenNumber($year)
    {
        return ($year % 19) + 1;
    }

    /**
     * Returns the Julian Epact for the Golden Number
     *
     * In the Julian calendar, the Epact is the age of the moon (i.e. the number of days that have passed since an
     * "official" new moon) on 22 March.
     *
     * @param int $goldenNumber
     * @return int
     */
    private static function calculateJulianEpact($goldenNumber)
    {
        $epact = (11* ($goldenNumber - 1)) % 30;

        if ($epact === 0) {
            $epact = 30;
        }

        return $epact;
    }

    /**
     * Returns the century for a given year
     *
     * In the context of these calculations the years 1900-1999 have a century of 20;
     * the years 2000-2999 a century of 21 and so on.
     *
     * @param int $year
     * @return int
     */
    private static function calculateCentury($year)
    {
        return (int) floor($year / 100) + 1;
    }

    /**
     * Return the solar equation for the century
     *
     * The Solar Equation (S) is an expression of the difference between the Julian and the Gregorian calendar.
     * The value of S increases by one in every century year that is not a leap year.
     *
     * @param int $century
     * @return int
     */
    private static function calculateSolarEquation($century)
    {
        return (int) floor((3 * $century) / 4);
    }

    /**
     * Return the lunar equation for the century
     *
     * The Lunar Equation (L) is an expression of the difference between the Julian calendar and the Metonic cycle.
     * The value of L increases by one 8 times every 2500 years.
     *
     * @param int $century
     * @return int
     */
    private static function  calculateLunarEquation($century)
    {
        return (int) floor(((8 * $century) + 5) / 25);
    }

    /**
     * Return the normalized epact.
     *
     * The epact must be a value between 1 and 30
     *
     * @param int $epact
     * @return int
     */
    private static function normalizeGregorianEpact($epact)
    {
        if ($epact < 1) {
            do {
                $epact += 30;
            } while ($epact < 1);
        }
        if ($epact > 30) {
            do {
                $epact -= 30;
            } while ($epact > 30);
        }

        return $epact;
    }

    /**
     * Return the first sunday (Easter sunday) after this Full Moon
     *
     * If this Full Moon day is a Sunday, the next Sunday is used.
     *
     * @param \DateTime $day
     * @return \DateTime
     */
    private static function getEasterFromPaschalFullMoon(\DateTime $day)
    {
        if ($day->format('D') === 'Sun') {
            $day->add(new \DateInterval('P1D'));
        }

        return self::getNextWeekday($day, 'Sun');
    }

    /**
     * Return the Paschal Full Moon based on the Golden Number of the year (Julian Calendar)

     * The Paschal Full Moon is the first full moon after march, 21th.
     *
     * @param int $goldenNumber
     * @return string
     */
    private static function getJulianPaschalFullMoonForGoldenNumber($goldenNumber)
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

        return $lookUp[$goldenNumber];
    }

    /**
     * Return the Paschal Full Moon based on the Epact of the year (Gregorian Calendar)

     * The Paschal Full Moon is the first full moon after march, 21th.
     *
     * @param int $epact
     * @param int $goldenNumber
     * @return string
     */
    private static function getJulianPaschalFullMoonForEpact($epact, $goldenNumber)
    {
        $lookUp = [
            1 => '04-12',
            2 => '04-11',
            3 => '04-10',
            4 => '04-09',
            5 => '04-08',
            6 => '04-07',
            7 => '04-06',
            8 => '04-05',
            9 => '04-04',
            10 => '04-03',
            11 => '04-02',
            12 => '04-01',
            13 => '03-31',
            14 => '03-30',
            15 => '03-29',
            16 => '03-28',
            17 => '03-27',
            18 => '03-26',
            19 => '03-25',
            20 => '03-24',
            21 => '03-23',
            22 => '03-22',
            23 => '03-21',
            24 => '04-18',
            25 => null,
            26 => '04-17',
            27 => '04-16',
            28 => '04-15',
            29 => '04-14',
            30 => '04-13',
        ];

        if ($epact === 25) {
            return $goldenNumber > 11 ? '04-17' : '04-18';
        }

        return $lookUp[$epact];
    }
}
