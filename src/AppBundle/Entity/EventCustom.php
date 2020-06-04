<?php

namespace AppBundle\Entity;

use AncaRebeca\FullCalendarBundle\Model\FullCalendarEvent;

class CalendarEvent extends FullCalendarEvent
{

    /**
     * @var string
     */
    protected $title;
    /**
     * @var \DateTime
     */
    protected $startDate;

    /**
     * CalendarEvent constructor.
     * @param $title
     * @param \DateTime $start
     */
        public function __construct($title, \DateTime $start)
    {
        $this->title = $title;
        $this->startDate = $start;
    }

}