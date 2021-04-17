<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('tenant_id'))
        {
            $user = User::findOrFail($request->get('tenant_id'));
            return response()->json($user->uncoveredEvents());
        }
        return response()->json(Event::all());
    }
}
