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
            return response()->json(User::findOrFail($request->get('tenant_id'))->actors);
        }
        return response()->json(Actor::all());
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'actor_id'=> ['required', 'int'],
            'fullname'=> ['required'],
            'phone_number'=> ['required'],
        ]);
        $actor = $user->actors()->updateOrCreate([
            'actor_id'=>$request->get('actor_id')
        ], [
            'fullname' => $request->get('fullname'),
            'phone_number' => $request->get('phone_number'),
        ]);
        return response()->json($actor);
    }
}
