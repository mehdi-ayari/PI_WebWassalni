<?php


namespace AppBundle\Listener;
use AncaRebeca\FullCalendarBundle\Event\CalendarEvent;
use AncaRebeca\FullCalendarBundle\Model\Event;
use AncaRebeca\FullCalendarBundle\Model\FullCalendarEvent;
use Doctrine\ORM\EntityManager;
use ReservationBundle\Entity\ReservationBusiness;
class LoadDataListener
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param CalendarEvent $calendarEvent
     *
     * @return FullCalendarEvent[]
     */

    public function loadData(CalendarEvent $calendarEvent)
    {
        //$startDate = $calendarEvent->getStart();
        //$endDate = $calendarEvent->getEnd();
        //$filters = $calendarEvent->getFilters();

       $repository = $this->em->getRepository('ReservationBundle:ReservationBusiness');
        $res = $repository->findAll();
        /** @var ReservationBusiness $schedule */
        foreach ($res as $schedule) {
            $calendarEvent->addEvent(new Event($schedule->getNomClientEntreprise(),$schedule->getDateDepart()));
       }
 //       $calendarEvent->addEvent(new Event('Event Title 1',new \DateTime('now')));

    }

}