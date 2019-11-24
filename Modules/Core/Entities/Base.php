<?php

namespace Modules\Core\Entities;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Facades\MyApp;

abstract class Base extends Model
{

    /**
     * Get preview uri
     *
     * @return null|string string or null
     */
    public function previewUri()
    {
        if (! $this->id) {
            return null;
        }
        return $this->getPublicUri(true);
    }

    /**
     * Get public uri
     *
     * @return string|null string or null
     */
    public function getPublicUriIndex()
    {
        $uri = $this->getPublicUri(false, true);
        return $uri;
    }

    /**
     * Get models that have status set to 1.
     *
     * @param Builder $query
     *
     * @return Builder $query
     */
    public function scopeOnline(Builder $query)
    {
        return $query->where('status', 1);
    }

    /**
     * Attach files to model
     *
     * @param  Builder $query
     * @param  boolean $all : all models or online models
     * @return Builder $query
     */
    public function scopeFiles(Builder $query, $all = false)
    {
        return $query->with(
            array('files' => function (Builder $query) use ($all) {
                $query->orderBy('id', 'asc');
            })
        );
    }

    /**
     * Order items according to GET value or model value, default is id asc
     *
     * @param  Builder $query
     * @return Builder $query
     */
    public function scopeOrder(Builder $query)
    {
        if ($order = config(str_replace('_','',$this->getTable()) . '.order')) {
            foreach ($order as $column => $direction) {
                $query->orderBy($column, $direction);
            }
        }
        return $query;
    }

    /**
     * Get status attribute from table
     * and append it to main model attributes
     * @return string title
     */
    public function getThumbAttribute($value)
    {
        return $this->present()->thumbSrc(null, 22);
    }

    /**
     * Get backend’s edit url of model
     *
     * @return string|void
     */
}
