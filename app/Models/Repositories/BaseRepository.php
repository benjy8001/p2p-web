<?php

declare(strict_types=1);

namespace App\Models\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param $ids
     *
     * @return array
     */
    public function getByIds($ids): array
    {
        if (null === $ids) {
            return [];
        }
        if (\is_array($ids)) {
            return $this->model->whereIn('id', $ids)->get();
        }

        throw new \InvalidArgumentException('Argument must be an array of indexes to get');
    }

    /**
     * @return Model
     */
    public function newInstance()
    {
        return $this->model->newInstance();
    }

    /**
     * @param object|array $data
     *
     * @return mixed
     */
    public function create(object|array $data)
    {
        if (\is_array($data)) {
            return $this->model->create($data);
        }

        if (\get_class($data) === \get_class($this->model)) {
            return $data->save();
        }

        throw new \InvalidArgumentException("L'argument de create doit être de type array ou de type ".\get_class($this->model));
    }

    /**
     * @param array|Model $data
     * @param int         $id
     * @param string      $attribute
     *
     * @return bool
     */
    public function update(Model|array $data, int $id = 0, string $attribute = 'id'): bool
    {
        if ($data instanceof Model) {
            return $data->save();
        }
        if (\is_array($data)) {
            return $this->model->where($attribute, '=', $id)->update($data);
        }

        throw new \InvalidArgumentException('Arguments must be a model or an array with an ID');
    }

    /**
     * @param $ids
     *
     * @return int
     */
    public function delete($ids): int
    {
        return $this->model->destroy($ids);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->model->count();
    }
}
