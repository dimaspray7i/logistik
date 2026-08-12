<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user belum login atau bukan Admin
        if (! $request->user() || $request->user()->role !== UserRole::ADMIN) {
            // Redirect ke halaman yang sesuai atau abort 403
            if ($request->user() && $request->user()->role === UserRole::CUSTOMER) {
                return redirect()->route('customer.dashboard');
            }
            abort(403, 'Unauthorized access. Admin only.');
        }

        return $next($request);
    }
}