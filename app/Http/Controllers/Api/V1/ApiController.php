<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Policies\TicketPolicy;
use App\Traits\ApiResponses;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use ApiResponses;
    use AuthorizesRequests;
    protected $policy_class;
    // This method retrieves the include query parameter, splits it by commas,
    // normalizes to lowercase, and checks if the requested relationship is included.
    public function include(string $relationship): bool
    {

        $param = request()->get('include');

        if (!isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);


    }

    public function isAble($ability, $target_model)
    {
        return $this->authorize($ability, [$target_model, $this->policy_class]);
        //return $this->authorize($ability, $target_model);
    }






}
