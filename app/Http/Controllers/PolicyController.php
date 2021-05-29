<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Actor;
use App\Models\ActorType;
use App\Models\Policy;
use App\Models\User;
use App\Services\PolicyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Laravel\Jetstream\Jetstream;

class PolicyController extends Controller
{

    /**
     * @var PolicyService
     */
    private $policyService;

    public function __construct(PolicyService $policyService)
    {
        $this->policyService = $policyService;
    }

    /**
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function index(Request $request, User $user): Response
    {
        return Jetstream::inertia()->render($request, 'Policies/Index',
            [
                'tenant' => $user->load([
                    'policies.event.triggerable',
                    'policies.action_actor.actor',
                    'actors'
                ]),
                'actions' => Action::all(),
                'actor_types' => ActorType::all()
            ]
        );
    }

    /**
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function assistant(Request $request, User $user): Response
    {
        return Jetstream::inertia()->render($request, 'Policies/VoiceAssistant',
            [
                'tenant' => $user->load([
                    'policies.event.triggerable',
                    'policies.action_actor.actor',
                    'actors'
                ])
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
                'actor_id'=>$action_actor["actor"]["id"]
            ]);
        }
        return response()->json($policy->load('action_actor.actor', 'event'));
    }


    public function storeFromQuestions(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'events'=> ['required']
        ]);

        $events = json_decode($request->get('events'), true);

        $policies = array();

        foreach ($events as $event)
        {

            if (!is_array($event["answer"]))
                continue;

            $policy = $user->policies()->firstOrCreate([
                'event_id' => $event["id"]
            ]);

            foreach ($event["answer"] as $answer){
                $policy->action_actor()->create([
                    'action_id' => $event["action"]["id"],
                    'actor_id' => $answer["id"]
                ]);
            }

            $policy->load('action_actor.actor', 'event');

            array_push($policies, $policy);
        }
        return response()->json(collect($policies));
    }

    public function storeFromQuestion(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'event'=> ['required']
        ]);

        $event = json_decode($request->get('event'), true);
        $policy = $this->policyService->store($user, $event);
        return response()->json($policy);
    }

    public function destroy(Request $request, Policy $policy): JsonResponse
    {
        $policy->delete();
        return response()->json([],200);
    }

}
