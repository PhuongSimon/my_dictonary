<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\VocabularyRequest;
use App\Models\Vocabulary;
use Carbon\Carbon;

class VocabularyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vocabularies = Vocabulary::paginate(10);
        return Inertia::render('Frontend/Vocabulary/Index', [
            'vocabularies' => $vocabularies
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $date = Carbon::now();
        $today = $date->format('Y/m/d');

        return Inertia::render('Frontend/Vocabulary/Create', [
            'today' => $today
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VocabularyRequest $request)
    {
        $date = Carbon::now();
        $today = $date->format('Y/m/d');

        Vocabulary::create([
            'user_id' => auth()->user()->id,
            'word' => $request->word,
            'word_type' => $request->word_type,
            'meaning' => $request->meaning,
            'example' => $request->example,
            'date' => $today
        ]);

        return redirect()->route('vocabulary.index')->with('message', 'Created Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vocabulary = Vocabulary::findOrFail($id);

        return Inertia::render('Frontend/Vocabulary/Create', [
            'vocabulary' => $vocabulary
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $vocabulary = Vocabulary::findOrFail($id);

        $vocabulary->update([
            'word' => $request->word,
            'word_type' => $request->word_type,
            'meaning' => $request->meaning,
            'example' => $request->example,
        ]);
    
        return redirect()->route('vocabulary.index')->with('message', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
