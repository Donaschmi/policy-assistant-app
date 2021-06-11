<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use App\Models\ActorType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Jetstream\Jetstream;

class ActorController extends Controller
{
    public function index(Request $request, User $user)
    {
        if ($request->get('json'))
        {
            return response()->json($user->actors);
        }
        elseif ($request->get('types'))
        {
            return response()->json(ActorType::all(['sentence', 'intent']));
        }
        return Jetstream::inertia()->render($request, 'Actors/Index',
            [
                'tenant' => $user->load(['actors']),
                'actor_types' => ActorType::all()
            ]
        );
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'actor_type_id'=> ['required', 'int'],
            'fullname'=> ['required'],
            'phone_number'=> ['required'],
        ]);
        $actor = $user->actors()->updateOrCreate([
            'actor_type_id'=>$request->get('actor_type_id')
        ], [
            'fullname' => $request->get('fullname'),
            'phone_number' => $request->get('phone_number'),
        ]);
        return response()->json($actor->load('actorType'));
    }

    public function other(Request $request, User $user)
    {
        $request->validate([
            'event_id'=> ['required', 'int'],
            'actors'=> ['required'],
        ]);
        $ids = explode(',', $request->actors);
        return response()->json($user->actors()->whereNotIn('id', $ids)->limit(2)->get()->load('actorType'));
    }

    public function destroy(Request $request, Actor $actor)
    {
        $actor->delete();
        return response()->json([],200);
    }
}
