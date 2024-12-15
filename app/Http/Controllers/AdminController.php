<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Method to view logs
    public function viewLogs(Request $request)
    {
        // Get the search term and date filter from the request
        $searchTerm = $request->input('search', '');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Start building the query
        $query = DB::table('logs');

        // Apply search filter for action and details
        if ($searchTerm) {
            $query->where('action', 'like', '%' . $searchTerm . '%')
                  ->orWhere('details', 'like', '%' . $searchTerm . '%');
        }

        // Apply date range filter if provided
        if ($dateFrom && $dateTo) {
            $query->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        // Get the filtered logs, ordered by most recent
        $logs = $query->orderBy('created_at', 'desc')->get();

        // Return the view with filtered logs
        return view('users.logs', compact('logs', 'searchTerm', 'dateFrom', 'dateTo'));
    }
}
