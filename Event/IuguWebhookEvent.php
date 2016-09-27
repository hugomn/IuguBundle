<?php

namespace Hugomn\IuguBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * Webhook event class.
 *
 * @author Hugo Magalhães <hugomn@gmail.com>
 */
class IuguWebhookEvent extends Event
{
    /**
     *
     * @var string
     */
    protected $event;

    /**
     *
     * @var string
     */
    protected $data;

    /**
     *  Constructor
     */
    public function __construct($event, $data)
    {
        $this->event = $event;
        $this->data = $data;
    }

    /**
     * Get event
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }
}
