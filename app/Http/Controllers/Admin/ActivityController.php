<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function latest(Request $request)
    {
        $lastId = $request->query('last_id', 0);
        $newActivities = ActivityLog::where('id', '>', $lastId)
                                    ->where('title', 'LIKE', '%Lead Submitted%')
                                    ->latest()
                                    ->get();

        return response()->json([
            'count' => $newActivities->count(),
            'activities' => $newActivities,
            'latest_id' => ActivityLog::max('id') ?: 0
        ]);
    }
}
