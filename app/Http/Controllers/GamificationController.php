<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\Achievement;
use app\Models\Mission;
use app\Models\Reward;
use app\Models\Leaderboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GamificationController extends Controller
{
    /**
     * Display the Gamification Dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Fetch data for the gamification hub
      //  $achievements = Achievement::where('user_id', $user->id)->get();
       // $missions = Mission::where('user_id', $user->id)->get();
       // $leaderboard = Leaderboard::orderBy('points', 'desc')->take(10)->get();
        //$rewards = Reward::all();

        // Fetch progress overview data
      //  $progress = DB::table('missions')
        //    ->where('user_id', $user->id)
         //   ->select(DB::raw('SUM(progress) as progress, SUM(goal) as goal'))
         //   ->first();

        return view('gamification.board', [
          //  'achievement' => $achievements,
           // 'missions' => $missions,
           // 'leaderboard' => $leaderboard,
           // 'rewards' => $rewards,
           // 'progress' => $progress,
        ]);
    }

    /**
     * Mark a mission as completed.
     */
    public function completeMission($id)
    {
        $mission = Mission::findOrFail($id);

        if ($mission->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $mission->completed = true;
        $mission->save();

        // Add points to leaderboard
        $leaderboard = Leaderboard::firstOrCreate(
            ['user_id' => Auth::id()],
            ['points' => 0]
        );
        $leaderboard->points += $mission->reward_points;
        $leaderboard->save();

        return redirect()->route('gamification.board')->with('success', 'Mission completed!');
    }

    /**
     * Redeem a reward.
     */
    public function redeemReward($id)
    {
        $reward = Reward::findOrFail($id);
        $leaderboard = Leaderboard::where('user_id', Auth::id())->first();

        if (!$leaderboard || $leaderboard->points < $reward->cost) {
            return redirect()->back()->with('error', 'Not enough points to redeem this reward.');
        }

        $leaderboard->points -= $reward->cost;
        $leaderboard->save();

        return redirect()->route('gamification.board')->with('success', 'Reward redeemed successfully!');
    }
}
