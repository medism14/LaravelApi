<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    // GET: Renvoyer toutes les événements avec filtrage et recherche
    public function index(Request $request)
    {
        try {
            // Récupération des événements
            $query = Event::query();

            // Vérification de s'il y'a une recherche et application de la requête
            if ($search = $request->input('search')) {
                $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%']);
            }

            // Vérification de s'il y'a une date de début et application de la requête
            if ($start_date = $request->input('start_date')) {
                $query->whereDate('start_datetime', '=', $start_date);
            }

            // Vérification de s'il y'a une date de fin et application de la requête
            if ($end_date = $request->input('end_date')) {
                $query->whereDate('end_datetime', '=', $end_date);
            }

            // Vérification de s'il y a une adresse et application de la requête
            if ($address = $request->input('address')) {
                $query->whereRaw('LOWER(address) LIKE ?', ['%' . strtolower($address) . '%']);
            }

            // Retour des événements
            return response()->json([
                'events' => $events,
            ], 200);

        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de la récupération des événements : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors de la récupération des événements',
            ], 500);
        }
    }

    // POST: Créer un événement
    public function create(Request $request)
    {
        try {
            // Validation du formulaire
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:150',
                'description' => 'required|string|max:255',
                'datetime' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'capacity' => 'required|integer|min:1',
                'categorie_id' => 'required|integer|exists:categories,id',
            ], [
                'title.required' => 'Le titre est requis.',
                'title.string' => 'Le titre doit être une chaîne de caractères.',
                'title.max' => 'Le titre ne peut pas dépasser 150 caractères.',
                'description.required' => 'La description est requise.',
                'description.string' => 'La description doit être une chaîne de caractères.',
                'description.max' => 'La description ne peut pas dépasser 255 caractères.',
                'datetime.required' => 'La date et l\'heure sont requises.',
                'datetime.string' => 'La date et l\'heure doivent être une chaîne de caractères.',
                'address.required' => 'L\'adresse est requise.',
                'address.string' => 'L\'adresse doit être une chaîne de caractères.',
                'address.max' => 'L\'adresse ne peut pas dépasser 255 caractères.',
                'capacity.required' => 'La capacité est requise.',
                'capacity.integer' => 'La capacité doit être un nombre entier.',
                'capacity.min' => 'La capacité doit être au moins 1.',
                'categorie_id.required' => 'La catégorie est requise.',
                'categorie_id.integer' => 'L\'ID de la catégorie doit être un nombre entier.',
                'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            ]);

            // Vérification de la validation
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 401);
            }

            // Création de l'événement
            $event = Event::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'start_datetime' => $request->input('start_datetime'),
                'end_datetime' => $request->input('end_datetime'),
                'address' => $request->input('address'),
                'capacity' => $request->input('capacity'),
                'remainingPlaces' => $request->input('capacity'),
                'category_id' => $request->input('categorie_id'),
            ]);

            // Renvoie de la réponse
            return response()->json([
                'message' => 'L\'événement a bien été créé',
                'event' => $event,
            ], 200);
        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de la création de l\'événement : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors de la création de l\'événement',
            ], 500);
        }
    }

    // GET: Afficher un événement
    public function show(string $id)
    {
        try {
            // Récupérer l'événement
            $event = Event::find($id);

            // Vérifier si l'événement existe
            if (!$event) {
                return response()->json([
                    'errors' => 'Cet événement n\'existe pas',
                ], 404);
            }

            // Retourner la réponse
            return response()->json([
                'message' => 'Événement récupéré avec succès',
                'event' => $event,
            ], 200);
        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de la récupération de l\'événement : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors de la récupération de l\'événement',
            ], 500);
        }
    }

    // PUT: Mettre à jour un événement
    public function update(Request $request, string $id)
    {
        try {
            // Récupérer l'événement
            $event = Event::find($id);

            // Vérifier si l'événement existe
            if (!$event) {
                return response()->json([
                    'errors' => 'Cet événement n\'existe pas',
                ], 404);
            }

            // Validation du formulaire
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:150',
                'description' => 'required|string|max:255',
                'datetime' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'capacity' => 'required|integer|min:1',
                'categorie_id' => 'required|integer|exists:categories,id',
            ], [
                'title.required' => 'Le titre est requis.',
                'title.string' => 'Le titre doit être une chaîne de caractères.',
                'title.max' => 'Le titre ne peut pas dépasser 150 caractères.',
                'description.required' => 'La description est requise.',
                'description.string' => 'La description doit être une chaîne de caractères.',
                'description.max' => 'La description ne peut pas dépasser 255 caractères.',
                'datetime.required' => 'La date et l\'heure sont requises.',
                'datetime.string' => 'La date et l\'heure doivent être une chaîne de caractères.',
                'address.required' => 'L\'adresse est requise.',
                'address.string' => 'L\'adresse doit être une chaîne de caractères.',
                'address.max' => 'L\'adresse ne peut pas dépasser 255 caractères.',
                'capacity.required' => 'La capacité est requise.',
                'capacity.integer' => 'La capacité doit être un nombre entier.',
                'capacity.min' => 'La capacité doit être au moins 1.',
                'categorie_id.required' => 'La catégorie est requise.',
                'categorie_id.integer' => 'L\'ID de la catégorie doit être un nombre entier.',
                'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            ]);

            // Vérification de la validation
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 401);
            }

            // Retirer les reservations récentes lié si les places restantes sont supérieur ou égales à la capacité
            if ($event->remainingPlaces > $request->input('capacity')) {
                $event->reservations()->delete();
                $event->remainingPlaces = $request->input('capacity');
            } 

            $event->fill($request->only('title', 'description', 'start_datetime', 'end_datetime', 'address', 'capacity', 'categorie_id'));

            // Modifier l'événement et enregistrement
            $event->save();

            return response()->json([
                'message' => 'L\'événement a bien été mis à jour',
                'event' => $event,
            ], 200);
        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de la mise à jour de l\'événement : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors de la mise à jour de l\'événement',
            ], 500);
        }
    }

    // DELETE: Supprimer un événement
    public function destroy(string $id)
    {
        try {
            // Récupérer l'événement par ID
            $event = Event::find($id);

            // Vérifier si l'événement existe
            if (!$event) {
                return response()->json([
                    'errors' => 'Cet événement n\'existe pas',
                ], 404);
            }

            // Logique pour supprimer l'événement
            $event->delete();

            // Retourner une réponse de succès
            return response()->json([
                'message' => 'Événement supprimé avec succès',
            ], 200);
        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de la suppression de l\'événement : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors de la suppression de l\'événement',
            ], 500);
        }
    }
}
