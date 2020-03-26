<?php


namespace ReservationBundle\Controller;


class ReservationController
{
    public function enum()
    {
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
}