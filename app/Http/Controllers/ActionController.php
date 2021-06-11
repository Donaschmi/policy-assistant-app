<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\User;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->has('actions')){
            $ids = explode(',', $request->actions);
            return response()->json(Action::whereNotIn('id', $ids)->limit(1)->get());
        }
        else{
            return response()->json(Action::get());
        }
    }
}
