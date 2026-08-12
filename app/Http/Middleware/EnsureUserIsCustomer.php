<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user belum login atau bukan Customer
        if (! $request->user() || $request->user()->role !== UserRole::CUSTOMER) {
            if ($request->user() && $request->user()->role === UserRole::ADMIN) {
                return redirect()->route('admin.dashboard');
            }
            abort(403, 'Unauthorized access. Customer only.');
        }

        // Tambahan keamanan: Pastikan customer_id tidak null
        if (is_null($request->user()->customer_id)) {
            abort(403, 'Customer account is not linked to a company profile.');
        }

        return $next($request);
    }
}