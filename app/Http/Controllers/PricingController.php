<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::active()
            ->notCustom()
            ->ordered()
            ->get();
        
        // Add custom plan at the end
        $customPlan = SubscriptionPlan::active()
            ->where('is_custom', true)
            ->first();
        
        if ($customPlan) {
            $plans->push($customPlan);
        }
        
        return view('pricing-new', compact('plans'));
    }
    
    public function contactCustomPlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);
        
        // Create a complaint/inquiry for custom plan
        \App\Models\Complaint::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'category' => 'other',
            'priority' => 'high',
            'status' => 'open',
            'subject' => 'طلب باقة مخصصة',
            'message' => $validated['message'],
        ]);
        
        // Send notification email to admin
        // Mail::to(config('mail.admin_email'))->send(new CustomPlanRequestMail($validated));
        
        return redirect()->back()->with('success', 'تم إرسال طلبك بنجاح! سنتواصل معك قريباً.');
    }
}
