<?php

namespace Kwetal\DateUtils;

/**
 * Class DateUtils
 * @package Kwetal\DateUtils
 */
class DateUtils
{
    /**
     * Get the nth weekday in a given month and year.
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
    public static function getNthWeekdayInMonthAndYear($year, $month, $weekday, $num = 1, \DateTime $start = null)
    {
        if (! in_array($weekday, ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])) {
            throw new \InvalidArgumentException("Invalid value For weekday (must be one of 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun')");
        }

        try {
            $day = new \DateTime($year . '-' . $month . '-' . '1');
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
        while(true) {
            if ($day->format('n') !== (string) $month) {
                return null;
            }

            if ($day->format('D') === $weekday) {
                $counter++;
            }

            if ($counter == $num) {
                break;
            }

            $day = $day->add(new \DateInterval('P1D'));
        }

        return $day;
    }
}

