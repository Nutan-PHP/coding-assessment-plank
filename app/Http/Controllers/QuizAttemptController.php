<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttemptStoreRequest;
use App\Models\Attempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizAttemptController extends Controller
{

    /**
     * Store an attempt at answering a question for the current user.
     *
     * @param AttemptStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AttemptStoreRequest $request)
    {
        $data = $request->validated();

        $attempt = Attempt::query()->create(array_merge($data, ['user_id' => Auth::id()]));
        $attempt->load('question');

        return response()->json($attempt);
    }
}
