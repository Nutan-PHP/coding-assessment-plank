<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizStoreRequest;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class QuizController extends Controller
{

    /**
     * Get all available quizzes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $quizzes = Quiz::query()->paginate();
        return response()->json($quizzes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Imaginary front-end implementation / view...
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuizStoreRequest $request)
    {
        $quizData = collect($request->validated());
        $quiz = Quiz::query()->create($quizData->only(['title', 'description'])->toArray());
        $quiz->questions()->createMany($quizData['questions']);

        return response()->json($quiz->load('questions'));

    }

    /**
     * Get a single quiz, along with its questions.
     * If the user has attempted the quiz before, return their attempts as well.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     * @throws ModelNotFoundException
     */
    public function show(string $id)
    {
        $quiz = Quiz::with([
            'questions' => function (HasMany $q) {
                $q->select('id', 'quiz_id', 'question', 'choices');
            },
            'questions.attempts' => function (HasMany $q) {
                $q->where('user_id', Auth::id());
            }
        ])->findOrFail($id);

        return response()->json($quiz);

    }

    /**
     * Delete a quiz and its associated questions
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     * @throws ModelNotFoundException
     */
    public function destroy(string $id)
    {
        $quiz = Quiz::query()->findOrFail($id);
        if (Auth::user()?->cannot('delete', $quiz)) {
            abort(403);
        }

        $quiz->delete();
        return response()->json(['deleted' => true]);
    }
}
