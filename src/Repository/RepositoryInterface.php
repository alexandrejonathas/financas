<?php

declare(strict_types=1);

namespace MMoney\Repository;


interface RepositoryInterface
{
    function all(): array;
    function find($id, bool $failIfNotExist);
    function create(array $data);
    function update($id, array $data);
    function delete($id);
    function findByField(string $field, $valor);
    function findOneBy(array $search);
}