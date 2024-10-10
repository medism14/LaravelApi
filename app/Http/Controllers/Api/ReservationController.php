<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReservationController extends Controller
{
    // GET: Avoir toutes les réservations
    public function index()
    {
        try {
            // Récupération des réservations
            $reservations = Reservation::all();

            // Retour des réservations
            return response()->json([
                'reservations' => $reservations,
            ], 200);
        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur inattendue lors de la récupération des réservations : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur inattendue lors de la récupération des réservations',
            ], 500);
        }
    }

    // POST: Faire une réservation
    public function create(Request $request)
    {
        try {
            // Validation du formulaire
            $validator = Validator::make($request->all(), [
                'number_of_seat' => 'required|integer|min:1',
                'user_id' => 'required|exists:users,id',
                'event_id' => 'required|exists:events,id',
            ], [
                'number_of_seat.required' => 'Le nombre de sièges est requis.',
                'user_id.required' => 'L\'ID de l\'utilisateur est requis.',
                'event_id.required' => 'L\'ID de l\'événement est requis.',
            ]);

            // Vérification de la validation
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 401);
            }

            // Récupération de l'évenemnt lié
            $event = Event::find($request->input('event_id'));
            $today = Carbon::now(); 

            // Vérifier si l'événement est fini ou non
            if ($today > $event->end_date) {
                return response()->json([
                    'errors' => 'Vous ne pouvez plus réserver dans cet événement car il est fini',
                ]);
            }

            // Voir s'il reste de la place ou pas
            if ($request->input('number_of_seat') > $event->remainingPlaces) {
                // Création de la réservation en attente
                $reservation = Reservation::create([
                    'status' => 'waiting',
                    'number_of_seat' => $request->input('number_of_seat'),
                    'user_id' => $request->input('user_id'),
                    'event_id' => $request->input('event_id'),
                ]);
            } else {
                // Création de la réservation
                $reservation = Reservation::create([
                    'status' => 'reserved',
                    'number_of_seat' => $request->input('number_of_seat'),
                    'user_id' => $request->input('user_id'),
                    'event_id' => $request->input('event_id'),
                ]);

                // Mise à jour des places restantes
                $event->remainingPlaces -= $request->input('number_of_seat');
                $event->save();
            }

            // Renvoie de la réponse
            return response()->json([
                'message' => 'La réservation a bien été créée',
                'reservation' => $reservation,
            ], 200);
        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur inattendue lors de la création de la réservation : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur inattendue lors de la création de la réservation',
            ], 500);
        }
    }

    // GET: Avoir le rendu d'une réservation
    public function show(string $id)
    {
        try {
            // Récupérer la réservation
            $reservation = Reservation::find($id);

            // Vérifier si la réservation existe
            if (!$reservation) {
                return response()->json([
                    'errors' => 'Cette réservation n\'existe pas',
                ], 404);
            }

            // Retourner la réponse
            return response()->json([
                'message' => 'Réservation récupérée avec succès',
                'reservation' => $reservation,
            ], 200);
        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur inattendue lors de la récupération de la réservation : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur inattendue lors de la récupération de la réservation',
            ], 500);
        }
    }

    // PUT: Mettre à jour une réservation
    public function update(Request $request, string $id)
    {
        try {
            // Récupérer la réservation
            $reservation = Reservation::find($id);

            // Vérifier si la réservation existe
            if (!$reservation) {
                return response()->json([
                    'errors' => 'Cette réservation n\'existe pas',
                ], 404);
            }

            // Validation du formulaire
            $validator = Validator::make($request->all(), [
                'status' => 'required|string',
                'number_of_seat' => 'required|integer|min:1',
                'user_id' => 'required|exists:users,id',
                'event_id' => 'required|exists:events,id',
            ], [
                'status.required' => 'Le statut est requis.',
                'number_of_seat.required' => 'Le nombre de sièges est requis.',
                'user_id.required' => 'L\'ID de l\'utilisateur est requis.',
                'event_id.required' => 'L\'ID de l\'événement est requis.',
            ]);

            // Vérification de la validation
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 401);
            }

            // Modifier la réservation et enregistrement
            $reservation->fill($request->only('number_of_seat', 'status', 'user_id', 'event_id'));
            $reservation->save();

            return response()->json([
                'message' => 'La réservation a bien été mise à jour',
                'reservation' => $reservation,
            ], 200);
        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur inattendue lors de la mise à jour de la réservation : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur inattendue lors de la mise à jour de la réservation',
            ], 500);
        }
    }

    // DELETE: Retirer une réservation
    public function destroy(string $id)
    {
        try {
            // Récupérer la réservation par ID
            $reservation = Reservation::find($id);

            // Vérifier si la réservation existe
            if (!$reservation) {
                return response()->json([
                    'errors' => 'Cette réservation n\'existe pas',
                ], 404);
            }

            // Logique pour supprimer la réservation
            $reservation->delete();

            // Retourner une réponse de succès
            return response()->json([
                'message' => 'Réservation supprimée avec succès',
            ], 200);
        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur inattendue lors de la suppression de la réservation : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur inattendue lors de la suppression de la réservation',
            ], 500);
        }
    }
}
