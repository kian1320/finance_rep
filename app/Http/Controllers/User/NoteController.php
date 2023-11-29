<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Facades\Log;

class NoteController extends Controller
{
    public function saveNote(Request $request)
    {

        try {
            $note = new Note;
            $note->date = $request->input('date');
            $note->time = $request->input('time'); // Add time field
            $note->note = $request->input('note');
            $note->save();

            Log::info('Note saved successfully');
            return response()->json(['message' => 'Note saved successfully']);
        } catch (\Exception $e) {
            Log::error('Error saving note: ' . $e->getMessage());
            return response()->json(['message' => 'Error saving note. Please try again.'], 500);
        }
    }

    public function getNotes(Request $request)
    {
        $date = $request->input('date');
        $notes = Note::where('date', $date)->get();

        return response()->json(['notes' => $notes]);
    }
}
