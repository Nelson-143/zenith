<?php
namespace app\Http\Controllers;

use app\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use app\Models\EmailVerification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use app\Mail\auth\VerifyEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']); // Ensure authentication
    }

    /**
     * Display the team management page (list users & manage them).
     */
    public function index()

    {
       
        // Fetch users for the logged-in user's account
        $accountId = auth()->user()->account_id;
        $users = User::with('roles')
            ->where('account_id', $accountId) // Filter by account_id
            ->get();

        // Fetch roles for the logged-in user's account (if roles are scoped to accounts)
        $roles = Role::all(); // If roles are global, fetch all roles

        return view('admin.team.index', compact('users', 'roles'));
    }

    /**
     * Store or update a user (handles both create & update).
     */
    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $request->user_id,
            'password' => $request->user_id ? 'nullable|string|min:6' : 'required|string|min:6',
            'role'     => 'required|exists:roles,name',
        ]);

        // Get the logged-in user's account_id
        $accountId = auth()->user()->account_id;

        // If updating an existing user
        if ($request->user_id) {
            $user = User::where('account_id', $accountId) // Ensure the user belongs to the same account
                ->findOrFail($request->user_id);

            $user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            if ($request->password) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            $user->syncRoles([$request->role]); // Update role
            return redirect()->route('admin.team.index')->with('success', 'User updated successfully.');
        }

        // If creating a new user
        $user = User::create([
            'uuid'       => Str::uuid(),
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'account_id' => $accountId, // Assign the logged-in user's account_id
        ]);

        // Assign the selected role to the user
        $user->assignRole($request->role);

        // Generate and store the email verification token
        $token = Str::random(64);
        EmailVerification::create([
            'user_id'    => $user->id,
            'email'      => $user->email,
            'token'     => $token,
            'expires_at' => Carbon::now()->addHours(24),
        ]);

        // Send the verification email
        $verificationUrl = route('verification.verify', ['token' => $token]);
        Mail::to($user->email)->send(new VerifyEmail($verificationUrl));

        return redirect()->route('admin.team.index')->with('success', 'User created successfully. A verification email has been sent.');
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        // Ensure the user belongs to the same account
        if ($user->account_id !== auth()->user()->account_id) {
            abort(403, 'Unauthorized action.');
        }

        $user->delete();
        return redirect()->route('admin.team.index')->with('success', 'User deleted successfully.');
    }
}