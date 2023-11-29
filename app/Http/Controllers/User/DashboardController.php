<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Items;
use App\Models\user;
use App\Models\Note;
use App\Models\Budget;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $items = Items::count();
        $users = User::where('role_as', '0')->count();
        $admins = User::where('role_as', '1')->count();
        $currentYear = date('Y');

        $userId = Auth::id();

        // Query for data1 (budgets)
        $data1 = DB::table('budgets')
            ->join('btypes', 'budgets.btypes_id', '=', 'btypes.id')
            ->where('budgets.year', $currentYear)
            ->where('budgets.created_by', $userId) // Filter by user who created the record
            ->groupBy('btypes.name')
            ->select('btypes.name', DB::raw('SUM(budgets.amount) as total_amount'))
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'total_amount' => (float)$item->total_amount,
                ];
            });

        // Query for data2 (expenses)
        $data2 = DB::table('types')
            ->join('expenses', 'types.id', '=', 'expenses.type_id')
            ->whereRaw('YEAR(expenses.encashment) = ?', [$currentYear])
            ->where('expenses.created_by', $userId) // Filter by user who created the record
            ->groupBy('types.name')
            ->select('types.name', DB::raw('SUM(expenses.amount) as total_amount'))
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'total_amount' => (float)$item->total_amount,
                ];
            });


        //dd($data2);

        return view('user.dashboard', compact('items', 'users', 'admins',  ['data1', 'data2']));
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
        $users = User::find($id);

        if (!$users) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['name' => $users->name]);
    }
}
