<?php
namespace litepubl\core\data;

use litepubl\core\storage\Storable;
use litepubl\core\storage\StorableTrait;
use litepubl\core\storage\StorageInterface;

/**
 * This is the base class to storage data
 *
 * @property-read App $app
 * @property-read Storage $storage
 * @property-read DB $db
 * @property-read string $thisTable
 */

class Data implements Storable
{
    use StorableTrait;
    use PropsTrait;
    use DBTrait;
    use InstallerTrait;

    const ZERODATE = '0000-00-00 00:00:00';
    public $table;

    public static function i()
    {
        return static ::iGet(get_called_class());
    }

    public static function iGet(string $class)
    {
        return static ::getAppInstance()->classes->getInstance($class);
    }

    public static function getAppInstance(): App
    {
        return litepubl::$app;
    }

    public function __construct()
    {
        if (!$this->basename) {
            $class = get_class($this);
            $this->basename = substr($class, strrpos($class, '\\') + 1);
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

    public function free()
    {
    }

    public function error($Msg, $code = 0)
    {
        throw new \Exception($Msg, $code);
    }

    public function getApp(): App
    {
        return static ::getAppInstance();
    }

    public function install()
    {
        $this->externalchain('Install');
    }

    public function uninstall()
    {
        $this->externalchain('Uninstall');
    }

    public function validate($repair = false)
    {
        $this->externalchain('Validate', $repair);
    }

    protected function externalChain($func, $arg = null)
    {
        $parents = class_parents($this);
        array_splice($parents, 0, 0, get_class($this));
        foreach ($parents as $class) {
            $this->externalFunc($class, $func, $arg);
        }
    }

    public function getExternalFuncName($class, $func)
    {
        $reflector = new \ReflectionClass($class);
        $filename = $reflector->getFileName();

        if (strpos($filename, 'kernel.')) {
            $filename = dirname($filename) . DIRECTORY_SEPARATOR . basename(str_replace('\\', DIRECTORY_SEPARATOR, $class)) . '.php';
        }

        $externalname = basename($filename, '.php') . '.install.php';
        $dir = dirname($filename) . DIRECTORY_SEPARATOR;
        $file = $dir . 'install' . DIRECTORY_SEPARATOR . $externalname;
        if (!file_exists($file)) {
            $file = $dir . $externalname;
            if (!file_exists($file)) {
                return false;
            }
        }

        include_once $file;

        $fnc = (is_object($class) ? get_class($class) : (string) $class) . $func;
        if (function_exists($fnc)) {
            return $fnc;
        }

        return false;
    }

    public function externalFunc($class, $func, $args)
    {
        if ($fnc = $this->getExternalFuncName($class, $func)) {
            if (is_array($args)) {
                array_unshift($args, $this);
            } else {
                $args = [
                    $this,
                    $args
                ];
            }

            return \call_user_func_array($fnc, $args);
        }
    }

    public function getStorage()
    {
        return $this->getApp()->storage;
    }

    public function getClass(): string
    {
        return get_class($this);
    }

    public function getDbversion()
    {
        return false;
    }

    public function getDb($table = '')
    {
        $table = $table ? $table : $this->table;
        if ($table) {
            $this->getApp()->db->table = $table;
        }

        return $this->getApp()->db;
    }

    protected function getThisTable()
    {
        return $this->getApp()->db->prefix . $this->table;
    }

    public static function getClassName($c): string
    {
        return is_object($c) ? get_class($c) : trim($c);
    }
}
