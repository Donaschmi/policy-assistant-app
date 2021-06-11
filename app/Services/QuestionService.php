<?php

namespace App\Services;

use App\Assistant\Question;
use App\Exceptions\NoActorsAvailableException;
use App\Exceptions\NoEventsAvailableException;
use App\Models\Action;
use App\Models\Actor;
use App\Models\Event;
use App\Models\PolicyActionActor;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Redis;

class QuestionService
{
    /**
     * @param User $user
     * @return Collection|null
     * @throws NoEventsAvailableException
     * @throws NoActorsAvailableException
     */
    public function generateRandomQuestions(User $user): ?Collection
    {
        $availableEvents = $user->uncoveredEvents();
        if ($availableEvents->count() === 0){
            throw new NoEventsAvailableException();
        }
        if ($user->actors()->count() === 0){
            throw new NoActorsAvailableException();
        }
        $events = $availableEvents->random(min([$availableEvents->count(), Question::AMOUNT]));
        foreach ($events as $event)
        {
            if (rand(0,10)  >2 && $user->actors()->count() > 1)
            {
                $actors = $user->actors()->inRandomOrder()->take(2)->get();
                $event->actor_1 = $actors[0];
                $event->actor_2 = $actors[1];
                $event->action = Action::inRandomOrder()->first();
                $event->sentence = str_replace(
                    array('{{ event }}', '{{ action }}', '{{ actor_1 }}', '{{ actor_2 }}'),
                    array($event->sentence, $event->action->sentence, $event->actor_1->sentence(), $event->actor_2->sentence()),
                    Question::multiple()
                );
            }
            else
            {
                $event->actor = $user->actors()->inRandomOrder()->first();
                $event->action =  Action::inRandomOrder()->first();
                $event->question = str_replace(
                    array('{{ event }}', '{{ action }}', '{{ actor }}'),
                    array($event->sentence, $event->action->sentence, $event->actor->sentence()),
                    Question::single()
                );
            }
        }
        return $events;
    }

    /**
     * @param User $user
     * @return Collection|null
     * @throws NoActorsAvailableException
     * @throws NoEventsAvailableException
     * @throws \Exception
     */
    public function generateBestQuestion(User $user): ?Event
    {
        $availableEvents = $user->uncoveredEvents();
        if ($availableEvents->count() === 0){
            throw new NoEventsAvailableException();
        }
        if ($user->actors()->count() === 0){
            throw new NoActorsAvailableException();
        }

        $highest_reactivity = collect($availableEvents)->sortByDesc('reactivity')->first()->reactivity;
        $usefulEvents =  $availableEvents->where('reactivity', $highest_reactivity);
        $sorted = $usefulEvents->sortByDesc('physical_health');
        $event = $sorted->first();

        $topActorByPolicy = PolicyActionActor::whereIn('actor_id', $user->actors()->pluck('id'))
            ->selectRaw('actor_id, COUNT(*) as count')
            ->groupBy('actor_id')
            ->orderBy('count', 'desc')
            ->first();
        if ($topActorByPolicy){
            $topActor = Actor::find($topActorByPolicy->actor_id);
        }
        else {
            $topActor = $user->actors()->with('actorType')
                ->whereHas('actorType', function (Builder $query) use ($event) {
                    $query->where('privacy', $event->privacy);
                })
                ->inRandomOrder()
                ->first();
            if (!$topActor){
                $topActor = $user->actors()->inRandomOrder()->first();
            }
        }

        $secondActor = null;

        if ($user->actors()->count() > 1) {
            $secondActor = $user->actors()->with('actorType')
                ->where('id', '!=', $topActor->id)
                ->whereHas('actorType', function(Builder $query) use ($event){
                    $query->where('privacy', $event->privacy);
                })
                ->inRandomOrder()
                ->first();
            if (!$secondActor){
                $secondActor = $user->actors()->where('id', '!=', $topActor->id)->inRandomOrder()->first();
            }
        }

        $event->actor_1 = $topActor;

        $event->action = Action::where('reactivity', $event->reactivity)->first();
        if ($secondActor){
            $event->actor_2 = $secondActor;
            $event->question = str_replace(
                array('{{ event }}', '{{ action }}', '{{ actor_1 }}', '{{ actor_2 }}'),
                array($event->sentence,
                    $event->action->sentence,
                    $event->actor_1->sentence(),
                    $event->actor_2->sentence()),
                Question::multiple()
            );
        }
        else{
            $event->question = str_replace(
                array('{{ event }}', '{{ action }}', '{{ actor }}'),
                array($event->sentence, $event->action->sentence, $event->actor->sentence()),
                Question::single()
            );
        }
        return $event;
    }

}
