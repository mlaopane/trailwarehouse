<?php

namespace TrailWarehouse\AppBundle\Tests;

use TrailWarehouse\AppBundle\Services\WhatDate;
use PHPUnit\Framework\TestCase;

/**
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

    private $today;

    /**
     * Fetch the WhatDate service
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->whatDate = new WhatDate();
    }

    /**
     * @test
     */
    public function getDateTime()
    {
        $dateTime = $this->whatDate->getDateTime();
        $this->assertInstanceOf(\DateTimeImmutable::class, $dateTime);

        return $dateTime;
    }

    /**
     * @test
     */
    public function getDate()
    {
        $date = $this->whatDate->getDate();
        $this->assertEquals('2017-09-26', $date);

        return $date;
    }
    /**
    * @test
    */
    public function getYear()
    {
        $year = $this->whatDate->getYear();
        $this->assertEquals('2017', $year);
        return $year;
    }

    /**
    * @test
    */
    public function getMonth()
    {
        $month = $this->whatDate->getMonth();
        $this->assertEquals('09', $month);

        return $month;
    }

    /**
     * @test
     */
    public function getDay()
    {
        $day = $this->whatDate->getDay();
        $this->assertEquals('27', $day);

        return $day;
    }

    /**
     * @test
     */
    public function getWeekDay()
    {
        $weekday = $this->whatDate->getWeekDay();
        $this->assertEquals('Mardi', $weekday, 'Jour de la semaine en français');

        return $weekday;
    }

}
