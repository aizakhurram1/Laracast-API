<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseUserRequest extends FormRequest
{
    public function mappedAttributes(array $other_attributes=[])
    {
        $attribute_map = array_merge( [
            'data.attributes.name' => 'name',
            'data.attributes.email' => 'email',
            'data.attributes.password' => 'password',
            'data.attributes.is_manager' => 'is_manager',
            
            

        ], $other_attributes);

        $attributes_to_update = [];


        foreach ($attribute_map as $key => $attribute) {
            if ($this->has($key)) {
               $value = $this->input($key);

               if($attribute === 'password'){
                $value = bcrypt($value);
               }

               $attributes_to_update[$attribute] = $value;
            }
        }

        return $attributes_to_update;


    }

}
