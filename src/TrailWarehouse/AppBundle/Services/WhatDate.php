<?php

namespace TrailWarehouse\AppBundle\Services;

/**
 * @author Mickaël LAO-PANE
 *
 * It's all about Today !
 * You can fetch : Date, Year, Month, Day, WeekDay (and even the DateTimeImmutable for Paris)
 */
class WhatDate
{

    /**
     * provided date
     *
     * @var \DateTimeImmutable
     */
    protected $dateTime;

    /**
     * date format
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d';

    /**
     * Week days
     *
     * @var array
     */
    protected $weekDays;

    /**
     * Initialize base properties
     *
     */
    public function __construct(\DateTimeImmutable $dateTime = NULL, array $weekDays = [])
    {
        $this->initDateTime($dateTime);
        $this->initWeekDays($weekDays);
    }

    protected function initDateTime($dateTime)
    {
        if (NULL === $dateTime) {
            $this->dateTime = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));
        }
        else {
            $this->dateTime = $dateTime;
        }
    }

    protected function initWeekDays($weekDays)
    {
        if (empty($weekDays)) {
            $this->weekDays = [
                'Monday'    => 'Lundi',
                'Tuesday'   => 'Mardi',
                'Wednesday' => 'Mercredi',
                'Thursday'  => 'Jeudi',
                'Friday'    => 'Vendredi',
                'Saturday'  => 'Samedi',
                'Sunday'    => 'Dimanche',
            ];
        }
        else {
            $this->weekDays = $weekDays;
        }
    }

    public function __toString()
    {
        return "What time is it ?";
    }

    /**
     * Date (YYYY-MM-DD)
     *
     * @return string
     */
    public function getDate()
    {
        return $this->dateTime->format($this->dateFormat);
    }

    /**
     * Year (YYYY)
     *
     * @return string
     */
    public function getYear()
    {
        return explode('-', $this->getDate())[0];
    }

    /**
     * Month (MM)
     *
     * @return string
     */
    public function getMonth()
    {
        return explode('-', $this->getDate())[1];
    }

    /**
     * Day (DD)
     *
     * @return string
     */
    public function getDay()
    {
        return explode('-', $this->getDate())[2];
    }


    /**
     * Name of the day;
     *
     * @return string
     */
    public function getWeekDay()
    {
        $engWeekDay = $this->dateTime->format('l');

        return $this->weekDays[$engWeekDay];
    }

    /**
     * Get the value of provided date
     *
     * @return \DateTimeImmutable
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set the value of provided date
     *
     * @param \DateTimeImmutable dateTime
     *
     * @return self
     */
    public function setDateTime(\DateTimeImmutable $dateTime)
    {
        $this->dateTime = new \DateTimeImmutable($time, new \DateTimeZone('Europe/Paris'));

        return $this;
    }


    /**
     * Get the value of date format
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * Get the value of Week days ENG->FR
     *
     * @return array
     */
    public function getWeekDays()
    {
        return $this->weekDays;
    }

}
