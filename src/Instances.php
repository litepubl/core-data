<?php

namespace LitePubl\Core\Data;

use litepubl\core\container\factories\FactoryInterface;
use litepubl\core\container\IterableContainerInterface;
use litepubl\core\container\IterableContainerTrait;
use litepubl\Core\Storage\storables\StorableInterface;
use LitePubl\Core\Storage\Storables\SaveableAwareInterface;

class Instances extends Data implements IterableContainerInterface
{
    use IterableContainerTrait;

    const DATA = 'data';
    const STORABLES = 'storables';

    protected $instances;

    public function __construct(FactoryInterface $factory)
    {
        parent::__construct($factory);
        $this->forInstances(SaveableAwareInterface::class)->setSaveable($this);
        $this->load();
    }

    public function getInstances()
    {
        return $this->instances;
    }

    public function getData(): array
    {
        $result = [static::DATA => $this->data];
        $instances = $this->forInstances(StorableInterface::class)->getInstances();
        foreach ($instances as $instance) {
                $result[static::STORABLES][$instance->getBaseName()] = $instance->getData();
        }

        return $result;
    }

    public function setData(array $data)
    {
        $this->data = $data[static::DATA];
        $this->forInstances(StorableInterface::class)->setData($data[static::STORABLES]);
    }
}
