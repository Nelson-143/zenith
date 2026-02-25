<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Account;

class ProfileCurrencyController extends Controller
{
    public function currencyUpdate(Request $request)
    {
        Log::info('Currency update requested', [
            'user_id' => auth()->id(),
            'request_data' => $request->all()
        ]);

        try {
            // Validate the request - The key change is here
            $validated = $request->validate([
                'currency' => 'required|in:TZS,KES,UGX,USD,EUR',
                'tax_rate' => 'required|numeric|min:0|max:100',
                // Remove the activate_currency validation since we'll handle it manually
            ]);
            
            Log::info('Request validation passed', ['validated_data' => $validated]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return redirect()->route('profile.settings')
                ->withErrors($e->errors())
                ->withInput();
        }

        $user = auth()->user();
        Log::info('User retrieved', ['user_id' => $user->id]);
        
        // Check if the user has an account
        $account = $user->account;
        if (!$account) {
            Log::warning('No account found for user, creating one', ['user_id' => $user->id]);
            try {
                $account = new Account([
                    'user_id' => $user->id,
                    'currency' => 'USD', // Default currency
                    'is_currency_active' => false,
                    'tax_rate' => 0,
                ]);
                $account->save();
                Log::info('New account created for user', ['account_id' => $account->id]);
                
            } catch (\Exception $e) {
                Log::error('Failed to create account', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                return redirect()->route('profile.settings')
                    ->with('error', 'Failed to create account. Please contact support.');
            }
        } else {
            Log::info('Existing account found', [
                'account_id' => $account->id,
                'current_currency' => $account->currency,
                'current_is_active' => $account->is_currency_active,
                'current_tax_rate' => $account->tax_rate
            ]);
        }

        try {
            // Log the pre-update state
            Log::info('Pre-update account state', [
                'account_id' => $account->id,
                'old_currency' => $account->currency,
                'old_is_active' => $account->is_currency_active,
                'old_tax_rate' => $account->tax_rate
            ]);

            // THIS IS THE FIX: Properly handle the checkbox value
            // Checkboxes send "on" when checked, or don't send the field at all when unchecked
            $isActive = $request->has('activate_currency');
            
            Log::info('Processing form values', [
                'new_currency' => $request->currency,
                'has_activate_currency' => $request->has('activate_currency'),
                'activate_currency_value' => $request->input('activate_currency'),
                'is_active_resolved' => $isActive,
                'new_tax_rate' => $request->tax_rate
            ]);
            
            // Update account properties
            $account->currency = $request->currency;
            $account->is_currency_active = $isActive; // This will be true if checkbox is checked, false otherwise
            $account->tax_rate = $request->tax_rate;
            
            // Save changes
            $saved = $account->save();
            
            // Compare values after save to verify changes were persisted
            $account->refresh();
            
            Log::info('Account update completed', [
                'save_successful' => $saved,
                'account_id' => $account->id,
                'new_currency' => $account->currency,
                'new_is_active' => $account->is_currency_active,
                'new_tax_rate' => $account->tax_rate
            ]);
            
            return redirect()->route('profile.settings')
                ->with('success', 'Currency and tax settings updated successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to update currency settings', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'account_id' => $account->id
            ]);
            
            return redirect()->route('profile.settings')
                ->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }
}