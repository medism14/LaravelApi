<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class UserAccessMiddleware
{
    /**
     * Ce middleware va s'appliquer dans des routes que l'utilisateur comme l'administrateur auront le droit
     * C'est à dire que l'utilisateur aura le droit que d'intéragir avec lui même et l'administrateur avec soi-même
     * et tout le monde
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Récupération des informations
        $userId = $request->route('id');
        $userRequest = $request->user();

        // Récupration de l'utilisateur avec id
        $targetUser = User::find($userId);

        // Si l'utilisateur qui engagne la requête n'existe pas
        if (!$userRequest) {
            return response()->json([
                'message' => 'Vous n\'avez pas le droit nécessaires pour effectuer cette action.',
            ], 403);
        }

        // Si l'utilisateur visé n'existe pas
        if (!$targetUser) {
            if ($userRequest->isAdmin()) {
                return response()->json([
                    'message' => 'Cet utilisateur n\'existe pas.',
                ], 404);
            }

            return response()->json([
                'message' => 'Vous n\'avez pas le droit nécessaires pour effectuer cette action.',
            ], 403);
        }

        // Si un utilisateur qui n'est pas administrateur veut accéder à un autre utilisateur
        if ($targetUser->id != $userRequest->id && !$userRequest->isAdmin()) {
            return response()->json([
                'message' => 'Vous n\'avez pas le droit nécessaires pour effectuer cette action.',
            ], 403);
        }

        return $next($request);
    }
}
