<?php
namespace Modules\Core\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class RepositoriesAbstract implements RepositoryInterface
{
    protected $model;

    /**
     * Get empty model
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get table name
     *
     * @return string
     */
    public function getTable()
    {
        return $this->model->getTable();
    }

    /**
     * Make a new instance of the entity to query on
     *
     * @param array $with
     */
    public function make(array $with = array())
    {
        return $this->model->with($with);
    }

    /**
     * Find a single entity
     *
     * @param string $key
     * @param string $value
     * @param array $with
     */
    public function getFirst(array $with = array())
    {
        $query = $this->make($with);
        return $query->first();
    }

    /**
     * Find a single entity by key value
     *
     * @param string $key
     * @param string $value
     * @param array $with
     */
    public function getFirstBy($key, $value, array $with = array(), $all = false)
    {
        $query = $this->make($with);
        return $query->where($key, '=', $value)->first();
    }

    /**
     * Retrieve model by id
     * regardless of status
     *
     * @param  int $id model ID
     * @return Model
     */
    public function byId($id, array $with = array())
    {
        $query = $this->make($with)->where('id', $id);

        $model = $query->firstOrFail();

        return $model;
    }

    /**
     * Get all models
     *
     * @param  array $with Eager load related models
     * @param  boolean $all Show published or all
     * @return Collection|NestedCollection
     */
    public function all(array $with = array(), $all = false)
    {
        $query = $this->make($with);
        // Get
        return $query->get();
    }

    /**
     * Get all models by key/value
     *
     * @param  string $key
     * @param  string $value
     * @param  array $with
     * @param  boolean $all
     * @return Collection
     */
    public function allBy($key, $value, array $with = array(), $all = false)
    {
        $query = $this->make($with);

        $query->where($key, $value);
        // Get
        $models = $query->get();

        return $models;
    }


    /**
     * Get latest models
     *
     * @param  integer $number number of items to take
     * @param  array $with array of related items
     * @return Collection
     */
    public function latest($number = 10, array $with = array())
    {
        $query = $this->make($with);
        return $query->order()->take($number)->get();
    }

    /**
     * Get single model by Slug
     *
     * @param  string $slug slug
     * @param  array $with related tables
     * @return mixed
     */
    public function bySlug($slug, array $with = array())
    {
        $model = $this->make($with)
            ->where('slug', '=', $slug)
            ->first();

        return $model;

    }

    /**
     * Return all results that have a required relationship
     *
     * @param string $relation
     * @param array $with
     * @return Collection
     */
    public function has($relation, array $with = array())
    {
        $entity = $this->make($with);

        return $entity->has($relation)->get();
    }

    /**
     * Create a new model
     *
     * @param  array $data
     * @return mixed Model or false on error during save
     */
    public function create(array $data)
    {
        // Create the model
        $model = $this->model->fill($data);

        $model->save();
        return $model;
    }

    public function getNew(array $attributes = array())
    {
        return $this->model->newInstance($attributes);
    }


    /**
     * Update an existing model
     *
     * @param  array $data
     * @return boolean
     */
    public function update(array $data)
    {
        $model = $this->model->where('id',$data['id'])->orwhere('slug',$data['id'])->first();

        $model->fill($data);

        if ($model->save()) {
            return $model;
        }

        return false;

    }

    /**
     * Build a select menu for a module
     *
     * @param  string $method with method to call from the repository ?
     * @param  boolean $firstEmpty generate an empty item
     * @param  string $value witch column as value ?
     * @param  string $key witch column as key ?
     * @return array               array with key = $key and value = $value
     */
    public function select($method = 'all', $firstEmpty = false, $value = 'title', $key = 'id')
    {
        $items = $this->$method()->pluck($value, $key)->all();
        if ($firstEmpty) {
            $items = ['' => ' -- Select -- '] + $items;
        }
        return $items;
    }


    /**
     * Delete model
     *
     * @return boolean
     */
    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }


    public function countAll()
    {
        return $this->model->count();
    }

}
