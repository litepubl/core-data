<?php
namespace litepubl\core\data;

interface DBItemsInterface extends ItemsInterface
{
    public function loadAll();
    public function loadItems(array $items);
    public function select(string $where, string $limit): array;
    public function res2items($res);
    public function findItem($where);
}
