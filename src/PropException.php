<?php

namespace litepubl\core\data;

class PropException extends \UnexpectedValueException
{
    public $propName;
    public $className;

    public function __construct($className, $propName)
    {
        $this->className = $className;
        $this->propName = $propName;

        parent::__construct(sprintf('The requested property "%s" not found in class  %s', $propName, $className), 404);
    }
}
