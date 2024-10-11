<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // GET: Renvoyer tout les utilisateurs
    public function index()
    {
        try {
            // Récupération des utilisations et leurs retours
            $users = User::paginate(15);
            return response()->json(
                ['users' => $users],
                200);
        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de la récupération des utilisateurs' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors de la récupération des utilisateurs',
            ], 500);
        }
    }

    // GET: Recevoir les informations d'un utilisateur
    public function show(Request $request, $id)
    {
        try {
            // Récupération de l'utilisateur qui est recherché
            $user = User::find($id);

            // Nous faisons déjà la vérification de si l'utilisateur existe ou non dans le middleware UserAccessMiddleware

            // Renvoie de l'utilisateur
            return response()->json([
                'user' => $user,
            ], 200);

        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de la récupération de l\'utilisateur' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors de la récupération de l\'utilisateur',
            ], 500);
        }
    }

    // POST: Modifier un utilisateur
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            // Nous faisons déjà la vérification de si l'utilisateur existe ou non dans le middleware UserAccessMiddleware

            //Règles de validation pour l'utilisateurs
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8|confirmed',
            ], [
                'name.required' => 'Le nom est requis.',
                'name.string' => 'Le nom doit être une chaîne de caractères.',
                'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
                'email.required' => 'L\'adresse e-mail est requise.',
                'email.string' => 'L\'adresse e-mail doit être une chaîne de caractères.',
                'email.max' => 'L\'adresse e-mail ne doit pas dépasser 255 caractères.',
                'email.email' => 'Veuillez fournir une adresse e-mail valide.',
                'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
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

            // Mettre à jour les informations
            $user->fill($request->only(['name', 'email']));

            if ($request->input('password')) {
                $user->fill($request->only('password'));
            }

            // Enregistrer les informations dans la bdd
            $user->save();

            // Renvoie de l'utilisateur modifié et d'un message
            return response()->json([
                'success' => 'L\'utilisateur a bien été modifié',
                'user' => $user,
            ], 200);

        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de la modification de l\'utilisateur' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors de la modification de l\'utilisateur',
            ], 500);
        }
    }

    // DELETE: Retirer un utilisateur
    public function destroy(Request $request, $id)
    {
        try {
            // Récupération de l'utilisateur avec l'id
            $user = User::find($id);

            // Nous faisons déjà la vérification de si l'utilisateur existe ou non dans le middleware UserAccessMiddleware

            // Suppression de tout ses tokens
            $user->tokens()->delete();

            // Suppression de l'utilisateur
            $user->delete();

            // Retourner une réponse de succès
            return response()->json([
                'success' => 'Utilisateur supprimé avec succès',
            ], 200);

        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de la suppression de l\'utilisateur' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors de la suppression de l\'utilisateur',
            ], 500);
        }
    }

    // PUT: Mettre un utilisateur administrateur
    /***
     * Le rôle de cette fonction est de permettre à un utilisateur qui possède une clé précise de passer administrateur
     * la clé est: 4f3a1b2c5d6e7f8a9b0c1d2e3f4a5b6c
     ***/
    public function makeAdmin(Request $request, $key, $id = null)
    {
        try {
            // Si un ID d'utilisateur est fourni, on le récupère, sinon on utilise l'utilisateur actuel
            $targetUser = $id ? User::find($id) : $request->user();

            // Vérification de la clé
            if ($key == "4f3a1b2c5d6e7f8a9b0c1d2e3f4a5b6c") {
                if ($targetUser) {
                    // Passage de l'utilisateur en administrateur
                    $targetUser->role = User::ROLE_ADMIN;
                    $targetUser->save();

                    // Envoie d'un message de confirmation
                    return response()->json([
                        'success' => 'L\'utilisateur est désormais administrateur',
                    ], 200);
                } else {
                    // Envoie d'une erreur si l'utilisateur cible n'existe pas
                    return response()->json([
                        'errors' => 'Utilisateur non trouvé',
                    ], 404);
                }
            } else {
                // Envoie d'une erreur
                return response()->json([
                    'errors' => 'La clé fournie n\'est pas bonne',
                ], 401);
            }

        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur inattendue lors du passage de l\'utilisateur en administrateur' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur inattendue lors du passage de l\'utilisateur en administrateur',
            ], 500);
        }
    }

    // GET: Récuperer les réservations d'un utilisateur
    public function getReservations(Request $request)
    {
        try {

            // Récupération de l'utilisateur
            $user = $request->user();

            // Vérification de l'existence de l'utilisations
            if (!$user) {
                return response()->json([
                    'errors' => 'Cet utilisateur n\'existe pas.',
                ], 404);
            }

            // Récupération des reservations
            $reservations = $user->reservations()->get();

            // Envoie de la réponse
            return response()->json([
                'success' => 'Voici vos réservations',
                'reservations' => $reservations,
            ], 200);

        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de la récupération des réservations de l\'utilisateur' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors de la récupération des réservations de l\'utilisateur',
            ], 500);
        }
    }

    // PUT: Retirer une réservation
    public function cancelReservation(Request $request, $reservation_id)
    {
        try {
            // Récupération de la réservation
            $reservation = Reservation::find($reservation_id);

            // Vérifier si la réservation existe
            if (!$reservation) {
                return response()->json([
                    'errors' => 'Cette réservation n\'existe pas',
                ], 404);
            }

            // Vérifier si la réservation n'est pas déjà annulée
            if ($reservation->status === 'cancelled') {
                return response()->json([
                    'errors' => 'Cette réservation est déjà annulée',
                ], 400);
            }

            // Modification du statut de la réservation
            $reservation->status = 'cancelled';
            $reservation->save();

            // Modification des places disponibles dans l'événement
            $event = $reservation->event;
            $event->remainingPlaces += $reservation->number_of_seat;
            $event->save();

            // Récupération des gens en liste d'attente
            $reservationsWaiting = Reservation::where('event_id', $event->id)
                ->where('status', 'waiting')
                ->orderBy('created_at', 'asc')
                ->get();

            // Faire en sorte de remplir les places qui ont été libérés avec les gens en liste d'attente
            foreach ($reservationsWaiting as $waitingReservation) {
                if ($waitingReservation->number_of_seat <= $event->remainingPlaces) {
                    // Mettre à jour le statut de la réservation
                    $waitingReservation->status = "reserved";
                    $waitingReservation->save();

                    // Mettre à jour le statut de l'événement
                    $event->remainingPlaces -= $waitingReservation->number_of_seat;
                    $event->save();
                } else if ($event->remainingPlaces > 0) {
                    // S'il y'a de la place mais que ça n'a pas matché avec le nombre de place de la reservartion
                    // Mise à jour de la reservation
                    $waitingReservation->number_of_seat = $event->remainingPlaces;
                    $waitingReservation->status = 'reserved';
                    $waitingReservation->save();

                    // Mise à jour de l'événement
                    $event->remainingPlaces = 0;
                    $event->save();
                } else {
                    // Si rien ne match
                    break;
                }
            }

            // Renvoie de l'annulation
            return response()->json([
                'success' => 'La réservation a été annulée avec succès',
                'reservation' => $reservation,
            ], 200);
        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur inattendue lors de l\'annulation de la réservation : ' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur inattendue lors de l\'annulation de la réservation',
            ], 500);
        }
    }

}
