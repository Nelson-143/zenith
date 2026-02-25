<?php

namespace app\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
class OnboardingController extends Controller
{
    public function showOnboarding()
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('auth.onboarding'); // Point to your onboarding Blade view
    }
    public function completeOnboarding(Request $request)
{
    // Mark the onboarding as complete
    $user = auth()->user();
    $user->onboarding_completed = true;
    $user->save();
    // Redirect to the dashboard
    return redirect()->route('dashboard')->with('status', 'Onboarding completed successfully.');
}
}
