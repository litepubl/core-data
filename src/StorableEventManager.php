<?php
namespace litepubl\core\data;

use litepubl\core\events\EventManager;
use litepubl\core\storage\storables\StorableInterface;
use litepubl\core\storage\storables\StorableItemsTrait;
use litepubl\core\storage\storables\SaveableAwareInterface;
use litepubl\core\storage\storables\SaveableAwareTrait;

class StorableEventManager extends EventManager implements StorableInterface, SaveableAwareInterface
{
    use StorableItemsTrait;
    use SaveableAwareTrait;

    protected function changed()
    {
        return $this->getSaveable()->save();
    }
}
