<?php

namespace App\Http\Controllers;

use App\Jobs\LogRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LogRequestController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        LogRequest::dispatch($request->__toString());

        return Response::json([
            'status' => 'success',
            'request' => $request->__toString(),
        ]);
    }
}
