<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Vocabulary;
use App\Models\VocabularyResult;
use Carbon\Carbon;

class LearningController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Frontend/Learning/Index');
    }

    public function check(Request $request)
    {
        $wordType = $request['wordType'];
        $questionType = $request['questionType'];
        $fromDate = Carbon::createFromFormat('Y-m-d', $request['fromDate'])->format('Y/m/d');
        $toDate = Carbon::createFromFormat('Y-m-d', $request['toDate'])->format('Y/m/d');

        $totalQuestion = Vocabulary::get();

        switch($questionType) {
            case('Vocabulary'): 
                $questions = Vocabulary::whereBetween('date', [$fromDate, $toDate])->get();
                break;
        }

        if ($questions->isEmpty()) {
            return response()->json([
                'message' => 'Không có câu hỏi nào trong ngày bạn chọn.'
            ], 404);
        }

        if($totalQuestion->count() < 4) {
            return response()->json([
                'message' => 'Hãy tạo ít nhất 4 từ vựng.'
            ], 404);
        }       

        return response()->json([
            'redirect' => route('learning.vocabulary', ['fromDate' => $fromDate, 'toDate' => $toDate, 'word_type' => $wordType ])
        ]);
    }

    public function vocabularyData(Request $request)
    {
        $date = $request['date'];

        $test = Carbon::createFromFormat('Y-m-d', $date)->format('Y/m/d');
        
        $questions = Vocabulary::where('date', $test)->get();

        return response()->json(['questions' => $questions]);
    }

    public function vocabulary(Request $request)
    {
        $fromDate = $request['fromDate'];
        $toDate = $request['toDate'];
        $wordType = $request['word_type'];

        $questions = Vocabulary::whereBetween('date', [$fromDate, $toDate])
            ->where('word_type', $wordType)
            ->inRandomOrder()
            ->get();
        
        $formattedQuestions = $questions->map(function ($question) {

            $distractors = Vocabulary::where('id', '!=', $question->id)
                                    ->inRandomOrder()
                                    ->limit(3)
                                    ->pluck('word')
                                    ->toArray();
        
            $correctAnswer = $question->word;

            $options = array_merge($distractors, [$correctAnswer]);
            shuffle($options);

            return [
                'word' => $question->word,
                'meaning' => $question->meaning,
                'example' => $question->example,
                'options' => $options,
            ];
        });
    
        return Inertia::render('Frontend/Learning/Vocabulary', [
            'questions' => $formattedQuestions,
        ]);
    }

    public function fillOut(Request $request)
    {
        $questions = Vocabulary::all()->shuffle();

        return Inertia::render('Frontend/Learning/Fillout', [
            'questions' => $questions,
            'total' => $questions->count(),
        ]);
    }

    public function saveResult(Request $request, $type)
    {
        $userId = auth()->user()->id;

        switch($type) {
            case ('fill-out'):
                try {
                    $incorrects = $request->input('incorrectIds', []);
                    $correct = $request->input('correctCount', 0);
                    $total = $request->input('total', 0);
                    
                    $percent = $total > 0 ? (int) round($correct * 100 / $total) : 0;

                    return VocabularyResult::create([
                        'user_id' => $userId,
                        'percent' => $percent,  
                        'correct' => $correct,
                        'total' => $total,
                        'incorrect' => json_encode($incorrects),
                    ]);

                } catch (\Throwable $th) {
                    return response()->json([
                        'error' => 'Error: ' . $th
                    ], 403);
                }
            break;
        }
    }

    public function results(Request $request)
    {
        $userId = auth()->user()->id;
        $type = request()->type;
        switch($type) {
            case ('fill-out'):
                $results = VocabularyResult::where('user_id', $userId)->orderBy('id', 'desc')->get();

                return response()->json([
                    'results' => $results
                ], 200);

                break;
        }
    }

    public function incorrectDetail(Request $request, $id)
    {
        $result = VocabularyResult::find($id);
        $incorrectIds = json_decode($result->incorrect, true);
        
        $wordList = [];
        foreach($incorrectIds as $incorrectId) {
            $word = Vocabulary::find($incorrectId)->word;
            $wordList[] = $word;
        }

        return response()->json([
            'list' => json_encode($wordList)
        ], 200);
    }
}