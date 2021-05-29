<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use App\Models\ActorType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Jetstream\Jetstream;

class RecorderController extends Controller
{
    public function index(Request $request, User $user)
    {
        return Jetstream::inertia()->render($request, 'Utils/VoiceRecord',
            [
                'tenant' => $user
            ]
        );
    }

    public function store(Request $request, User $user)
    {
        $sluggedName = Str::slug($user->name);
        $path = $request->file('recording')->storeAs(
            "recordings/$sluggedName", $request->get('intent').'.wav'
        );
        return response()->json($path);
    }
}
