<?php

namespace Shapecode\Bundle\CronBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class CronJob
 *
 * @package Shapecode\Bundle\CronBundle\Annotation
 * @author  Nikita Loges
 *
 * @Annotation
 */
class CronJob extends Annotation
{

    /** @var string */
    protected $arguments;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getArguments()
    {
        return $this->arguments;
    }

}
