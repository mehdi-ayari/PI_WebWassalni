<?php

/**
 * This file is part of the TchoulomViewCounterBundle package.
 *
 * @package    TchoulomViewCounterBundle
 * @author     Original Author <tchoulomernest@yahoo.fr>
 *
 * (c) Ernest TCHOULOM <https://www.tchoulom.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tchoulom\ViewCounterBundle\Manager;

use Tchoulom\ViewCounterBundle\Repository\RepositoryInterface;


/**
 * Class CounterManager
 */
class CounterManager
{
    /**
     * @var RepositoryInterface
     */
    protected $counterRepository;

    /**
     * CounterManager constructor.
     * @param RepositoryInterface $counterRepository
     */
    public function __construct(RepositoryInterface $counterRepository)
    {
        $this->counterRepository = $counterRepository;
    }

    /**
     * Saves the object.
     *
     * @param $object
     */
    public function save($object)
    {
        $this->counterRepository->save($object);
    }

    /**
     * Finds One By.
     *
     * @param array $criteria
     * @param null $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return mixed
     */
    public function findOneBy(array $criteria, $orderBy = null, $limit = null, $offset = null)
    {
        $result = $this->counterRepository->findOneBy($criteria, $orderBy, $limit, $offset);

        return $result;
    }

    /**
     * Loads Metadata.
     *
     * @param $object
     *
     * @return $this
     */
    public function loadMetadata($object)
    {
        $this->metadata = $this->counterRepository->loadMetadata($object);

        return $this;
    }

    /**
     * Gets the property.
     *
     * @return mixed
     */
    public function getProperty()
    {
        return $this->counterRepository->getProperty();
    }

    /**
     * Gets the Class.
     *
     * @return mixed
     */
    public function getClass()
    {
        return $this->counterRepository->getClass();
    }
}