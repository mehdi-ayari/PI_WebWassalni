<?php

namespace Shapecode\Bundle\CronBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\NonUniqueResultException;
use Shapecode\Bundle\CronBundle\Entity\CronJobInterface;
use Shapecode\Bundle\CronBundle\Entity\CronJobResultInterface;

/**
 * Interface CronJobResultRepositoryInterface
 *
 * @package Shapecode\Bundle\CronBundle\Repository
 * @author  Nikita Loges
 */
interface CronJobResultRepositoryInterface extends ObjectRepository
{

    /**
     * @param \DateTime $time
     *
     * @return mixed
     */
    public function deleteOldLogs(\DateTime $time);

    /**
     * @param CronJobInterface $job
     *
     * @return CronJobResultInterface
     * @throws NonUniqueResultException
     */
    public function findMostRecent(CronJobInterface $job = null);
}
