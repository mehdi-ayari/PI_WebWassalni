<?php

namespace VoyageBundle\Repository;

use Shapecode\Bundle\CronBundle\Annotation\CronJob;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\PhpProcess;
use VoyageBundle\Entity\Voyage;
use ReservationBundle\Entity\Reservation;
use AppBundle\Entity\User;

/**
 * VoyageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */




class VoyageRepository extends \Doctrine\ORM\EntityRepository
{



    public function myfinddate(){

            $qb = $this->getEntityManager()->createQueryBuilder()
                ->select('R.dateReservation', 'U.id','R.idRes' ,'R.destination')
                ->from('ReservationBundle:reservation', 'R')
                ->innerJoin('AppBundle:user' , 'U')
                ->where("R.dateReservation between DATE_ADD(CURRENT_TIMESTAMP(), '-1' ,'month') and DATE_ADD(CURRENT_TIMESTAMP() , 1 , 'month') and R.userClient = U");
            $query = $qb->getQuery();



        return $query->getResult();
    }

    public function myfindVoyage(){

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('V')
            ->from('VoyageBundle:voyage', 'V')
            ->innerJoin('ReservationBundle:Reservation','R')
            ->where("V.dateVoyage between DATE_ADD(CURRENT_TIMESTAMP(), '+300' ,'second') and DATE_ADD(CURRENT_TIMESTAMP() , 1 , 'month') and R = V.reservationRes");
        $query = $qb->getQuery();



        return $query->getResult();
    }

    public function myfindVoyageDone(){

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('V')
            ->from('VoyageBundle:voyage', 'V')
            ->innerJoin('ReservationBundle:Reservation','R')
            ->where("V.dateVoyage between DATE_ADD(CURRENT_TIMESTAMP(), '-12' ,'day') and DATE_ADD(CURRENT_TIMESTAMP(), '+300' ,'second') and R = V.reservationRes");
        $query = $qb->getQuery();



        return $query->getResult();
    }





}
