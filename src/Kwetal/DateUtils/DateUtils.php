<?php

namespace Kwetal\DateUtils;

/**
 * Class DateUtils
 * @package Kwetal\DateUtils
 */
class DateUtils
{
    const EASTER_JULIAN   = 1;
    const EASTER_ORTHODOX = 2;
    const EASTER_WESTERN  = 3;

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
        if (! in_array($weekday, self::$validWeekdays)) {
            throw new \InvalidArgumentException("Invalid value For weekday (must be one of 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun').");
        }

        try {
            $day = new \DateTime($year . '-' . $month . '-1');
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        if ($start) {
            if ($start->format('Y') !== (string) $year || $start->format('n') !== (string) $month) {
                throw new \InvalidArgumentException('Invalid $start parameter (year and month must match with other parameters).');
            }
            $day = $start;
        }

        $counter = 0;
        while (true) {
            if ($day->format('n') !== (string) $month) {
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
        if (! in_array($weekday, self::$validWeekdays)) {
            throw new \InvalidArgumentException("Invalid value For weekday (must be one of 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun').");
        }

        try {
            $day = new \DateTime($year . '-' . $month . '-' . self::getLastDayOfMonth($year, $month)->format('d'));
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
            $date = new \DateTime($year . '-' . $month . '-1');
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        $date->add(new \DateInterval('P1M'));
        $date->sub(new \DateInterval('P1D'));

        return $date;
    }
}
