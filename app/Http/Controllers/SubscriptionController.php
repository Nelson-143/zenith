<?php
namespace app\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use app\Models\UserSubscription;
use app\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::withCount('userSubscriptions')->select('subscriptions.*', 'subscriptions.id as subscription_id')->get();
           // Fetch the authenticated user's subscription
           $userSubscription = UserSubscription::where('user_id', Auth::id())->first();
            // Fetch user's payments (if user has a subscription)
    $payments = $userSubscription ? $userSubscription->payments : collect([]);

        return view('subscriptions.index', compact('subscriptions', 'userSubscription','payments'));
    }

    public function create()
    {
        return view('subscriptions.pay');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'max_branches' => 'nullable|integer|min:0',
            'max_users' => 'nullable|integer|min:0',
            'features' => 'nullable|array',
        ]);

        $validated['features'] = json_encode($validated['features'] ?? []);
        $validated['uuid'] = Str::uuid(); // Generate UUID

        Subscription::create($validated);

        return redirect()->route('subscriptions.index')->with('success', 'Subscription created successfully.');
    }

    public function edit(Subscription $subscription)
    {
        return view('subscriptions.edit', compact('subscription'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'max_branches' => 'nullable|integer|min:0',
            'max_users' => 'nullable|integer|min:0',
            'features' => 'nullable|array',
        ]);

        $validated['features'] = json_encode($validated['features'] ?? []);

        $subscription->update($validated);

        return redirect()->route('subscriptions.index')->with('success', 'Subscription updated successfully.');
    }

    public function destroy(Subscription $subscription)
    {
        // Prevent deletion if users are subscribed
        if ($subscription->userSubscriptions()->exists()) {
            return redirect()->route('subscriptions.index')->with('error', 'Cannot delete, users are subscribed to this plan.');
        }

        $subscription->delete();
        return redirect()->route('subscriptions.index')->with('success', 'Subscription deleted successfully.');
    }

    public function showPlans()
{
    $plans = Subscription::all();
    return view('subscriptions.plans', compact('plans'));
}

public function subscribe(Request $request, $planId)
{
    $user = auth()->user();
    $plan = Subscription::findOrFail($planId);
//$user->subscription()->associate($plan);
//$user->save();
    return redirect()->route('dashboard')->with('success', 'You have successfully subscribed to the ' . $plan->name . ' plan.');
}

public function pay($subscriptionId)
{
    $subscription = Subscription::findOrFail($subscriptionId);
    return view('subscriptions.pay', compact('subscription'));
}

public function processPayment(Request $request, $subscriptionId)
{
    $subscription = Subscription::findOrFail($subscriptionId);
    $user = auth()->user();

    // Simulate payment processing (replace with actual payment gateway logic)
    $paymentStatus = 'success'; // Assume payment is successful

    if ($paymentStatus === 'success') {
        // Save user subscription
        UserSubscription::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(), // 1-month subscription
            'status' => 'active',
        ]);

        return redirect()->route('subscriptions.index')->with('success', 'Payment successful! Your subscription is now active.');
    } else {
        return redirect()->route('subscriptions.pay', $subscriptionId)->with('error', 'Payment failed. Please try again.');
    }
}
}
