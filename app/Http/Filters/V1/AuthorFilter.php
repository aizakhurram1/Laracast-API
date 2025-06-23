<?php

namespace App\Http\Filters\V1;

class AuthorFilter extends QueryFilter
{
    protected $sortable = [
        'name',
        'email',
        'id',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    public function include($value)
    {
        return $this->builder->with($value);
    }

    public function createdAt($value)
    {
        $dates = explode(',', $value);

        // more than 1 dates to filter
        if (count($dates) > 1) {

            return $this->builder->where('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    public function id($value)
    {

        // to get comma separated id codes
        return $this->builder->whereIn('id', explode(',', $value));
    }

    public function email($value)
    {
        // to search email based on some words, you donot need to write complete title
        $likestr = str_replace('*', '%', $value);

        return $this->builder->where('email', 'like', $likestr);
    }

    public function name($value)
    {
        // to search name based on some words, you donot need to write complete title
        $likestr = str_replace('*', '%', $value);

        return $this->builder->where('name', 'like', $likestr);
    }

    public function updatedAt($value)
    {
        $dates = explode(',', $value);

        // more than 1 dates to filter
        if (count($dates) > 1) {

            return $this->builder->where('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }
}
