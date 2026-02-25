<?php
namespace app\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use app\Models\User; // Import the User model

class TeamLogsController extends Controller
{
    public function showTeamLogs()
    {
       
        // Get the currently authenticated user
        $user = auth()->user();

        // Retrieve the account_id of the authenticated user
        $accountId = $user->account_id;

        // Fetch all user IDs under the same account_id
        $userIds = User::where('account_id', $accountId)->pluck('id');

        // Retrieve activities for all users under the same account_id
        $activities = Activity::whereIn('causer_id', $userIds)
            ->with('causer') // Eager load the 'causer' relation
            ->latest()
            ->paginate(10);

        return view('admin.team.logs', compact('activities'));
    }
}