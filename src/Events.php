<?php

namespace litepubl\core\data;

use litepubl\core\container\factories\FactoryInterface;

class Events extends Data
{
    const EVENTS = 'events';
    protected $eventManager;

    public function __construct(FactoryInterface $factory)
    {
        parent::__construct($factory);
        $this->eventManager = $this->factory->createEventManager($this);
        $this->load();
    }

    public function getData(): array
    {
        $result = $this->data;
        $result[static::EVENTS] = $this->eventManager->getData();
        return $result;
    }

    public function setData(array $data)
    {
        $this->eventManager->setData($data);
        unset($data[static::EVENTS]);
        $this->data = $data;
    }
}
