<?php

namespace TrailWarehouse\AppBundle\Tests;

use TrailWarehouse\AppBundle\Service\WhatDate;
use PHPUnit\Framework\TestCase;

/**
 *
 * You have to replace the values to match with today's date
 *
 * @author Mickaël LAO-PANE
 *
 */
class WhatDateTest extends TestCase
{

    /**
     * WhatDate Service
     *
     * @var WhatDate
     */
    private $whatDate;

    /**
     * Fetch the WhatDate service
     */
    public function __construct()
    {
        parent::__construct();

        $this->whatDate = new WhatDate();

        /* Replace the following arguments with today's values */
        $this->initData('2017-09-28', 'Jeudi');
    }

    /**
     *
     *
     * @param string date
     * @param string weekDay
     *
     */
    protected function initData(string $date, string $weekDay): void
    {
        $this->date = $date;
        $exploded_date = explode('-', $date);
        $this->year = $exploded_date[0];
        $this->month = $exploded_date[1];
        $this->day = $exploded_date[2];
        $this->weekDay = $weekDay;
    }

    /**
     * @test
     */
    public function getDateTime()
    {
        $dateTime = $this->whatDate->getDateTime();
        $this->assertInstanceOf(\DateTime::class, $dateTime);

        return $dateTime;
    }

    /**
     * @test
     */
    public function getDate()
    {
        $date = $this->whatDate->getDate();
        $this->assertEquals($this->date, $date, "Check your calendar for the actual date");

        return $date;
    }
    /**
    * @test
    */
    public function getYear()
    {
        $year = $this->whatDate->getYear();
        $this->assertEquals($this->year, $year, "Check your calendar for the actual year (4 digits)");
        return $year;
    }

    /**
    * @test
    */
    public function getMonth()
    {
        $month = $this->whatDate->getMonth();
        $this->assertEquals($this->month, $month, "Check your calendar for the actual month (2 digits)");

        return $month;
    }

    /**
     * @test
     */
    public function getDay()
    {
        $day = $this->whatDate->getDay();
        $this->assertEquals($this->day, $day, "Check your calendar for the actual day (2 digits)");

        return $day;
    }

    /**
     * @test
     */
    public function getWeekDay()
    {
        $weekday = $this->whatDate->getWeekDay();
        $this->assertEquals($this->weekDay, $weekday, "Check your calendar for the actual french week day");

        return $weekday;
    }

}
