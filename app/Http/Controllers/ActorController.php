<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use App\Models\User;
use Illuminate\Http\Request;

class ActorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('tenant_id'))
        {
            // Should filter available actor by tenant
            return response()->json(Actor::all());
        }
        return response()->json(Actor::all());
    }
}
