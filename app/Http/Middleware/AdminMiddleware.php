<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Ce middleware sert à vérifier si l'utilisateur qui effectue la requête est un administrateur
     * ou non
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = $request->user();

        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'message' => 'Vous n\'avez pas le droit nécessaires pour effectuer cette action.',
            ], 403);
        }

        return $next($request);
    }
}
