<?php
namespace App\Http\Filters\V1;

class TicketFilter extends QueryFilter {

    protected $sortable = [
        'title', 
        'status',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];
    public function include($value){
       return $this->builder->with($value);
    }

    public function createdAt($value){
        $dates = explode(',', $value);

        //more than 1 dates to filter
        if(count($dates) > 1){

            return $this->builder->where('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }
    public function status($value){

        //to get comma separated status codes 
        return $this->builder-> whereIn("status", explode(',', $value));
    }

    public function title($value){
        // to search title based on some words, you donot need to write complete title
        $likestr = str_replace('*','%', $value);
        return $this->builder->where('title', 'like', $likestr);
    }
    
    public function updatedAt($value){
        $dates = explode(',', $value);

        //more than 1 dates to filter
        if(count($dates) > 1){

            return $this->builder->where('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }

}
