<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class GetHelpController extends Controller
{
    public function index()
    {
        return view('gethelp');
    }

    public function contact(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        try {
            $user = Auth::user();
            
            // Log the support request
            Log::info('Support Request', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'timestamp' => now(),
            ]);

            // TODO: Send email to support team
            // Uncomment when email is configured
            // Mail::to('support@moneta.com')->send(new SupportRequest($validated));

            return redirect()->route('help')
                ->with('success', 'Your message has been sent! We will get back to you soon.');
                
        } catch (\Exception $e) {
            Log::error('Support Request Failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('help')
                ->with('error', 'Failed to send message. Please try again later.');
        }
    }
}