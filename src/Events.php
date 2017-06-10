<?php

namespace litepubl\core\data;

use LitePubl\Core\Events\EventManagerInterface;

class Events extends Instances
{
    protected $eventManager;

    protected function create()
    {
        parent::create();
        $this->eventManager = $this->getFactory()->get(EventManagerInterface::class);
        $this->instances[] =$this->eventManager;
    }
}
