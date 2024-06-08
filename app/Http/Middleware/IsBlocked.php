<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class isBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifique se o usuário está autenticado e se é administrador
        if (Auth::check() && Auth::user()->is_blocked === 0) {
            return $next($request);
        }

        // Opcional: Redirecionar para uma página específica
        // return redirect('/not-authorized');

        // Retorne uma resposta de erro se o usuário não for administrador
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
