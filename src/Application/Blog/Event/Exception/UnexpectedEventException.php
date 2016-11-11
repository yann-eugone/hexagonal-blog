<?php

namespace Acme\Application\Blog\Event\Exception;

use LogicException;

class UnexpectedEventException extends LogicException
{
    /**
     * @param object $subscriber
     * @param object $event
     *
     * @return UnexpectedEventException
     */
    public static function create($subscriber, $event)
    {
        return new self(
            sprintf(
                '%s event subscriber should not be subscribed to %s event.',
                get_class($subscriber),
                get_class($event)
            )
        );
    }
}
