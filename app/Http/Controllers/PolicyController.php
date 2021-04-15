<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Actor;
use App\Models\Policy;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Laravel\Jetstream\Jetstream;

class PolicyController extends Controller
{
    /**
     * @param Request $request
     * @param $user
     * @return Response
     */
    public function index(Request $request, User $user): Response
    {
        return Jetstream::inertia()->render($request, 'Policies/Index',
            [
                'tenant' => $user->load(['policies.event.triggerable', 'policies.action_actor']),
                'actions' => Action::all(),
                'actors' => Actor::all()
            ]
        );
    }

    public function store(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'event_id'=> ['required', 'int']
        ]);
        $policy = $user->policies()->firstOrCreate([
            'event_id' => $request->get('event_id')
        ]);
        $action_actor_array = json_decode($request->get('action_actor'), true);
        foreach($action_actor_array as $action_actor){
            $policy->action_actor()->create([
                'action_id'=>$action_actor["action"]["id"],
                'actor_id'=>$action_actor["actor"]["actor_id"]
            ]);
        }
        return response()->json($policy->load('action_actor', 'event'));
    }

    public function destroy(Request $request, Policy $policy)
    {
        $policy->delete();
        return response()->json([],200);
    }
}
