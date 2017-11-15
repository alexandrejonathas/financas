<?php

declare(strict_types=1);

namespace MMoney\Repository;


use Illuminate\Database\Eloquent\Model;

class DefaultRepository implements RepositoryInterface
{
    private $modelClass;

    /**
     * @var Model
     */
    private $model;


    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
        $this->model = new $this->modelClass;
    }

    public function all(): array
    {
        return $this->model->all()->toArray();
    }

    public function find($id, bool $failIfNotExist = true)
    {
        return $failIfNotExist ? $this->model->findOrFail($id) : $this->model->find($id);
    }

    public function create(array $data)
    {
        $this->model->fill($data);
        $this->model->save();
        return $this->model;
    }

    protected function findInternal($id){
        return is_array($id) ? $this->findOneBy($id) : $this->find($id);
    }

    public function update($id, array $data)
    {
        $model = $this->findInternal($id);
        $model->fill($data);
        $model->save();
        return $model;
    }

    public function delete($id)
    {
        $model = $this->findInternal($id);
        $model->delete();
    }

    public function findByField(string $field, $valor)
    {
       return $this->model->where($field, "=", $valor)->get();
    }

    function findOneBy(array $search)
    {
        $queryBuilder = $this->model;
        foreach ($search as $field => $value){
            $queryBuilder = $queryBuilder->where($field, "=", $value);
        }
        return $queryBuilder->firstOrFail();
    }
}