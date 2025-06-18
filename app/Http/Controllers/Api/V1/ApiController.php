<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use ApiResponses;
    // This method retrieves the include query parameter, splits it by commas,
    // normalizes to lowercase, and checks if the requested relationship is included.
    public function include(string $relationship) : bool {

        $param = request()->get('include');

        if(!isset($param)){
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    
    
    }





}
