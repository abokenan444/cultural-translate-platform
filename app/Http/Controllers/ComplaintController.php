<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        return view('complaints');
    }
    
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'category' => 'required|in:technical,billing,feature_request,bug_report,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        $complaint = Complaint::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'status' => 'open',
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);
        
        // Send notification email to admin
        // Mail::to(config('mail.admin_email'))->send(new NewComplaintMail($complaint));
        
        // Send confirmation email to user
        // Mail::to($complaint->email)->send(new ComplaintReceivedMail($complaint));
        
        return redirect()->back()->with('success', 'تم إرسال شكواك بنجاح! رقم التذكرة: ' . $complaint->ticket_number);
    }
    
    public function track(Request $request)
    {
        $ticketNumber = $request->input('ticket_number');
        
        if (!$ticketNumber) {
            return view('track-complaint');
        }
        
        $complaint = Complaint::where('ticket_number', $ticketNumber)->first();
        
        if (!$complaint) {
            return back()->with('error', 'رقم التذكرة غير صحيح');
        }
        
        return view('track-complaint', compact('complaint'));
    }
}
