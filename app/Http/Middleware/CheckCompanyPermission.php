<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCompanyPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Get company from route parameter
        $companyId = $request->route('company');
        
        if (!$companyId) {
            abort(404, 'Company not found');
        }
        
        // Check if user is member of the company
        $membership = $user->companyMemberships()
            ->where('company_id', $companyId)
            ->where('is_active', true)
            ->first();
        
        if (!$membership) {
            abort(403, 'You are not a member of this company');
        }
        
        // Check if user has the required permission
        if (!$membership->hasPermission($permission)) {
            abort(403, 'You do not have permission to perform this action');
        }
        
        // Add company membership to request for easy access
        $request->merge(['companyMembership' => $membership]);
        
        return $next($request);
    }
}
