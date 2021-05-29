<?php

namespace App\Http\Controllers;

use App\Assistant\Question;
use App\Exceptions\NoActorsAvailableException;
use App\Exceptions\NoEventsAvailableException;
use App\Models\User;
use App\Services\QuestionService;
use Exception;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * @var QuestionService
     */
    private $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function index(Request $request, User $user)
    {
        $request->validate(['question_type'=>'required']);
        if ($request->get('question_type') === 'random')
        {
            try {
                $questions = $this->questionService->generateRandomQuestions($user);
                return response()->json($questions);
            }
            catch (Exception $exception)
            {
                if ($exception instanceof NoEventsAvailableException){
                    return response()->json('event_missing', 404);
                }
                elseif ($exception instanceof NoActorsAvailableException){
                    return response()->json('actor_missing', 404);
                }
                else {
                    return response()->json([], 500);
                }
            }
        }
        else if ($request->get('question_type') === 'best')
        {
            try {
                $question = $this->questionService->generateBestQuestion($user);
                return response()->json($question);
            }
            catch (Exception $exception)
            {
                if ($exception instanceof NoEventsAvailableException){
                    return response()->json('event_missing', 404);
                }
                elseif ($exception instanceof NoActorsAvailableException){
                    return response()->json('actor_missing', 404);
                }
                else {
                    return response()->json($exception, 500);
                }
            }
        }
    }
}
