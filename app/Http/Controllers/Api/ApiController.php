<?php namespace App\Http\Controllers\Api;

/**
 * Written by Jeremy Payne
 */

use App\Http\Controllers\Controller;
use Response;

class ApiController extends Controller
{
    public function result($result, $httpCode)
    {
        return Response::json($result, $httpCode);
    }
}
