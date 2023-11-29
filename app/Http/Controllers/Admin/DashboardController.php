<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Items;
use App\Models\user;
use App\Models\Note;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {

        $items = Items::count();
        $users = User::where('role_as', '0')->count();
        $admins = User::where('role_as', '1')->count();


        return view('admin.dashboard', compact('items', 'users', 'admins'));
    }



    public function saveNote(Request $request)
    {
        try {
            $note = new Note;
            $note->date = $request->input('date');
            $note->note = $request->input('note');
            $note->created_by = $request->input('created_by');
            $note->save();

            Log::info('Note saved successfully', ['note' => $note]); // Log the saved note for debugging
            return response()->json(['message' => 'Note saved successfully']);
        } catch (\Exception $e) {
            Log::error('Error saving note: ' . $e->getMessage());
            return response()->json(['message' => 'Error saving note. Please try again.'], 500);
        }
    }





    public function getNotes(Request $request)
    {
        // Retrieve events from the 'notes' table based on the date range provided by FullCalendar
        $start = $request->input('start');
        $end = $request->input('end');

        $events = Note::whereBetween('date', [$start, $end])
            ->select(['id', 'note as title', 'date as start']) // Rename columns for FullCalendar
            ->get();

        return response()->json($events);
    }




    public function getUsername($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['name' => $user->name]);
    }
}
