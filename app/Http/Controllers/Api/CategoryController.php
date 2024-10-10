<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // GET: Renvoyer toutes les catégories
    public function index()
    {
        try {
            // Récupération des catégories
            $categories = Category::all();

            // Retour des catégories
            return response()->json([
                'categories' => $categories,
            ], 200);
        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de la récupération des catégories : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors de la récupération des catégories',
            ], 500);
        }
    }

    // POST: Créer une catégorie
    public function create(Request $request)
    {
        try {
            // Validation du formulaire
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:150|unique:categories',
            ], [
                'name.required' => 'Le nom est requis.',
                'name.string' => 'Le nom doit être une chaîne de caractères.',
                'name.max' => 'Le nom ne peut pas dépasser 150 caractères.',
                'name.unique' => 'Ce nom de catégorie est déjà utilisé.',
            ]);

            // Vérification de la validation
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 401);
            }

            // Création de la catégorie
            $category = Category::create([
                'name' => $request->input('name'),
            ]);

            // Renvoie de la réponse
            return response()->json([
                'message' => 'La catégorie a bien été créée',
                'category' => $category,
            ], 200);
        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de la création de la catégorie : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors de la création de la catégorie',
            ], 500);
        }
    }

    // GET: Afficher une catégorie
    public function show(string $id)
    {
        try {
            // Récupérer la catégorie
            $category = Category::find($id);

            // Vérifier si la categorie existe
            if (!$category) {
                return response()->json([
                    'errors' => 'Cette catégorie n\'existe pas',
                ], 404);
            }

            // Retourner la réponse
            return response()->json([
                'message' => 'Catégorie récupérée avec succès',
                'category' => $category,
            ], 200);
        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de la récupération de la catégorie : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors de la récupération de la catégorie',
            ], 500);
        }
    }

    // PUT: Mettre à jour une catégorie
    public function update(Request $request, string $id)
    {
        try {
            // Récupérer la catégorie
            $category = Category::find($id);

            // Vérifier si la catégorie existe
            if (!$category) {
                return response()->json([
                    'errors' => 'Cette catégorie n\'existe pas',
                ], 404);
            }

            // Validation du formulaire
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:150|unique:categories,name,' . $category->id,
            ], [
                'name.required' => 'Le nom est requis.',
                'name.string' => 'Le nom doit être une chaîne de caractères.',
                'name.max' => 'Le nom ne peut pas dépasser 150 caractères.',
                'name.unique' => 'Ce nom de catégorie est déjà utilisé.',
            ]);

            // Vérification de la validation
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 401);
            }
            // Modifier de la categorie et enregistrement
            $category->fill($request->only('name'));
            $category->save();

            return response()->json([
                'message' => 'La catégorie a bien été mise à jour',
                'category' => $category,
            ], 200);
        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de la mise à jour de la catégorie : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors de la mise à jour de la catégorie',
            ], 500);
        }
    }

    // DELETE: Supprimer une catégorie
    public function destroy(Request $request, string $id)
    {
        try {
            // Récupérer la catégorie par ID
            $category = Category::find($id);

            // Vérifier si la catégorie existe
            if (!$category) {
                return response()->json([
                    'errors' => 'Cette catégorie n\'existe pas',
                ], 404);
            }

            // Logique pour supprimer la catégorie
            $category->delete();

            // Retourner une réponse de succès
            return response()->json([
                'message' => 'Catégorie supprimée avec succès',
            ], 200);
        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de la suppression de la catégorie : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors de la suppression de la catégorie',
            ], 500);
        }
    }
}
