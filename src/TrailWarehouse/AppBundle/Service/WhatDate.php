<?php

namespace TrailWarehouse\AppBundle\Service;

/**
 * @author Mickaël LAO-PANE
 *
 * It's all about Today !
 * You can fetch : Date, Year, Month, Day, WeekDay (and even the DateTime for Paris)
 */
class WhatDate implements \ArrayAccess
{

    /**
     * provided date
     *
     * @var \DateTime
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
     * For the ArrayAccess methods
     *
     * @var array
     */
    protected $container;

    /**
     * Initialize base properties
     *
     */
    public function __construct(\DateTime $dateTime = NULL, array $weekDays = [])
    {
        $this->initDateTime($dateTime);
        $this->initWeekDays($weekDays);
        $this->initDate();
    }

    protected function initDateTime($dateTime)
    {
        if (NULL === $dateTime) {
            $this->dateTime = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        }
        else {
            $this->dateTime = $dateTime;
        }
        $this->container['dateTime'] = $this->dateTime;

        return $this;
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
        $this->container['weekdays'] = $this->weekDays;

        return $this;
    }

    protected function initDate()
    {
        $this->container['dateTime'] = $this->getDateTime();
        $this->container['date'] = $this->getDate();
        $this->container['year'] = $this->getYear();
        $this->container['month'] = $this->getMonth();
        $this->container['day'] = $this->getDay();
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
    public function getDate(): string
    {
        return $this->dateTime->format($this->dateFormat);
    }

    /**
     * Year (YYYY)
     *
     * @return string
     */
    public function getYear(): string
    {
        return explode('-', $this->getDate())[0];
    }

    /**
     * Month (MM)
     *
     * @return string
     */
    public function getMonth(): string
    {
        return explode('-', $this->getDate())[1];
    }

    /**
     * Day (DD)
     *
     * @return string
     */
    public function getDay(): string
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
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set the value of provided date
     *
     * @param \DateTime dateTime
     *
     * @return self
     */
    public function setDateTime(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;

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

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

}
