<?php

namespace App\Services;

use App\Assistant\Question;
use App\Exceptions\NoActorsAvailableException;
use App\Exceptions\NoEventsAvailableException;
use App\Models\Action;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

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
            if (rand(0,10)  >7 && $user->actors()->count() > 1)
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
                $event->sentence = str_replace(
                    array('{{ event }}', '{{ action }}', '{{ actor }}'),
                    array($event->sentence, $event->action->sentence, $event->actor->sentence()),
                    Question::single()
                );
            }
        }
        return $events;
    }
}
