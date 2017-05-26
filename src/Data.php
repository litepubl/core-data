<?php
namespace litepubl\core\data;

use litepubl\core\storage\Data as DataStorage;
use litepubl\core\container\factories\InstallableInterface;
use litepubl\core\container\factories\InstallableTrait;
use litepubl\core\container\factories\FactoryInterface;

/**
 * This is the base class to storage data
 *
 * @property-read App $app
 * @property-read Storage $storage
 * @property-read DB $db
 * @property-read string $thisTable
 */

class Data extends DataStorage implements InstallableInterface
{
    use InstallableTrait;
    use PropsTrait;
    use DBTrait;

    const ZERODATE = '0000-00-00 00:00:00';
    public $table;
    protected $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
        if (!$this->basename) {
            $className = get_class($this);
            $this->baseName = substr($className, strrpos($className, '\\') + 1);
        }

        $this->create();
    }

    protected function create()
    {
        $this->createData();
    }

    //method to override in traits when in base class declared create method
    protected function createData()
    {
    }

    public function __destruct()
    {
        $this->free();
    }

    protected function free()
    {
    }

    public function getFactory(): FactoryInterface
    {
        return $this->factory;
    }

    public function error($Msg, $code = 0)
    {
        throw new \Exception($Msg, $code);
    }

    public function getApp(): App
    {
        return static ::getAppInstance();
    }
}
