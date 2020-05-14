<?php

namespace Glooby\TaskBundle\Annotation;

use Cron\CronExpression;

/**
 * @author Emil Kilhage
 * @Annotation
 */
class Schedule
{
    /**
     * @var array
     */
    private static $map = [
        '@yearly' => '0 0 1 1 *',
        '@annually' => '0 0 1 1 *',
        '@monthly' => '0 0 1 * *',
        '@weekly' => '0 0 * * 0',
        '@daily' => '0 0 * * *',
        '@hourly' => '0 * * * *',
        '@semi_hourly' => '*/30 * * * *',
        '@twenty_minutes' => '*/20 * * * *',
        '@quarter_hourly' => '*/15 * * * *',
        '@ten_minutes' => '*/10 * * * *',
        '@five_minutes' => '*/5 * * * *',
        '*' => '* * * * *',
    ];

    /**
     * @var string
     */
    public $interval;

    /**
     * @var bool
     */
    public $active = true;

    /**
     * @var null|int
     */
    public $timeout = 0;

    /**
     * @var int
     */
    public $version = 10;

    /**
     * @var array
     */
    public $params = [];

    /**
     * @param array $options
     * @throws \InvalidArgumentException
     */
    public function __construct(array $options)
    {
        $options = $this->setDefault($options);
        $options = $this->ensureExpressionExist($options);
        $options = $this->mapExpression($options);

        $this->populate($options);

        $this->validateExpression();
        $this->validateTimeout();
        $this->validateParams();
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function validateTimeout()
    {
        if (isset($this->timeout) && !is_numeric($this->timeout)) {
            throw new \InvalidArgumentException('Property "timeout" must be an int');
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function validateParams()
    {
        if (!is_array($this->params)) {
            throw new \InvalidArgumentException('Property "params" must be an array');
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function validateExpression()
    {
        CronExpression::factory($this->interval);
    }

    /**
     * @param array $options
     * @throws \InvalidArgumentException
     */
    public function populate(array $options)
    {
        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }

            $this->$key = $value;
        }
    }

    /**
     * @param array $options
     * @return array
     */
    public function mapExpression(array $options)
    {
        if (isset(self::$map[$options['interval']])) {
            $options['interval'] = self::$map[$options['interval']];
        }

        return $options;
    }

    /**
     * @param array $options
     * @return array
     */
    public function setDefault(array $options)
    {
        if (isset($options['value'])) {
            $options['interval'] = $options['value'];
            unset($options['value']);
        }

        return $options;
    }

    /**
     * @param array $options
     * @throws \InvalidArgumentException
     * @return array
     */
    public function ensureExpressionExist(array $options)
    {
        if (empty($options['interval'])) {
            throw new \InvalidArgumentException('Missing property interval');
        }
        return $options;
    }
}
