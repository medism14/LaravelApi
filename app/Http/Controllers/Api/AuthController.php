<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/***
 * Gère l'authentification des utilisateurs.
 ***/

class AuthController extends Controller
{
    // POST: Inscription d'un nouveau utilisateur
    public function register(Request $request)
    {
        try {
            // Validation des champs dans la requête
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|string|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'name.required' => 'Le nom est obligatoire.',
                'name.string' => 'Le nom doit être une chaîne de caractères.',
                'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
                'email.required' => 'L\'adresse e-mail est obligatoire.',
                'email.email' => 'Veuillez fournir une adresse e-mail valide.',
                'email.string' => 'L\'adresse e-mail doit être une chaîne de caractères.',
                'email.max' => 'L\'adresse e-mail ne doit pas dépasser 255 caractères.',
                'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
                'password.required' => 'Le mot de passe est obligatoire.',
                'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
                'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            ]);

            // Verification de la validation
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 401);
            }

            // Création d'un utilisateur
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            // Génération d'un token pour l'utilisateur
            $authToken = $user->createToken('auth_token')->plainTextToken;

            // Si tout se passe bien alors renvoyer l'utilisateur et le token ainsi que le type de token
            return response()->json([
                'user' => $user,
                'access_token' => $authToken,
                'token_type' => 'Bearer',
            ], 200);

        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de l\'enregistrement : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Une erreur innatendue est survenue lors de la création de votre compte, veuillez réessayez plus tard',
            ], 500);
        }
    }

    // POST: Connexion de l'utilisateur
    public function login(Request $request)
    {
        try {
            // Validation des champs dans la requête
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8',
            ], [
                'email.required' => 'L\'adresse e-mail est obligatoire.',
                'email.email' => 'Veuillez fournir une adresse e-mail valide.',
                'email.max' => 'L\'adresse e-mail ne doit pas dépasser 255 caractères.',
                'password.required' => 'Le mot de passe est obligatoire.',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            ]);

            // Verification de la validation
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 401);
            }

            $user = User::where('email', $request->input('email'))->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'errors' => 'Les informations d\'identification sont incorrectes',
                ], 401);
            }

            // Supprimer les tokens déjà présent dans la table
            $user->tokens()->delete();

            // Création d'un nouveau token
            $authToken = $user->createToken('auth_token')->plainTextToken;

            // Si tout se passe bien alors renvoyer l'utilisateur et le token ainsi que le type de token
            return response()->json([
                'user' => $user,
                'access_token' => $authToken,
                'token_type' => 'Bearer',
            ], 200);

        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur inattendue lors de la connexion : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Une erreur inattendue est survenue lors de la connexion à votre compte, veuillez réessayer plus tard',
            ], 500);
        }
    }

    // POST: Déconnexion d'un utilisateur (nous pouvons supprimer avec l'id de l'utilisateur aussi)
    public function logout(Request $request)
    {
        try {
            // Récupération de l'utilisateur qui initie la requête
            $user = $request->user();

            // Suppression de tous les tokens de l'utilisateur
            $user->tokens()->delete();

            // Renvoie d'un message de confirmation de déconnexion
            return response()->json([
                'success' => 'Déconnexion réussie',
            ], 200);

        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur inattendue lors de la déconnexion : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Une erreur inattendue est survenue lors de la déconnexion,'
            ], 500);
        }
    }
}
