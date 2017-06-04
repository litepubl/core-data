<?php
namespace litepubl\core\data;

use Psr\Container\ContainerInterface;

interface ItemsInterface extends ContainerInterface
{
    public function getCount(): int;
    public function getItem($id);
    public function getValue($id, $name);
    public function setValue($id, $name, $value);
    public function indexOf($name, $value);
    public function addItem(array $item)
    public function delete($id);
}
