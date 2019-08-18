<?php
namespace kuaukutsu\console\task\events;

use Exception;
use yii\base\Event;

/**
 * Class ErrorEvent
 * @package kuaukutsu\console\task
 */
class ErrorEvent extends Event
{
    /**
     * @var Exception
     */
    public $exception;

    /**
     * ErrorEvent constructor.
     * @param Exception $exception
     * @param array $config
     */
    public function __construct(Exception $exception, array $config = [])
    {
        $this->exception = $exception;
        parent::__construct($config);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->exception->getMessage();
    }
}