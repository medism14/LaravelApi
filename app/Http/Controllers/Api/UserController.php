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

    // GET: Récuperer les réservations d'un utilisateur
    public function getReservations(Request $request, $id)
    {
        try {
            // Récupération de l'utilisateur
            $user = User::find($id);

            // Nous faisons déjà la vérification de si l'utilisateur existe ou non dans le middleware UserAccessMiddleware

            // Récupération des reservations
            $reservations = $user->reservations()->event()->get();

            // Envoie de la réponse
            return response()->json([
                "message" => "Voici vos réservations",
                "reservations" => $reservations,
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

            $reservation->status = 'cancelled';

            // Modification des places disponibles dans l'événement
            $disponibleSeat = $reservation->number_of_seat;
            $event = $reservation->event;
            $event->remainingPlaces += $disponibleSeat;
            $event->save();

            // Récupération des gens en liste d'attente
            $reservationsWaiting = Reservation::where('event_id', $event->id)->where('status', 'waiting')->get();

            foreach ($reservationsWaiting as $waitingReservation) {
                // Si le nombre de sièges demandés est supérieur aux places restantes, on continue à la prochaine réservation
                if ($waitingReservation->number_of_seat > $event->remainingPlaces) {
                    continue;
                } else {
                    // Mettre à jour le statut de la réservation
                    $waitingReservation->status = "reserved";
                    $waitingReservation->save();
                    $event->remainingPlaces -= $waitingReservation->number_of_seat;
                }
            }

            // Renvoie de l'annulation
            return response()->json([
                'message' => 'La réservation a été annulée avec succès',
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
                'message' => 'L\utilisateur a bien été modifié',
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

    // PUT: Mettre un utilisateur administrateur
    /***
     * Le rôle de cette fonction est de permettre à un utilisateur qui possède une clé précise de passer administrateur
     * la clé est: 4f3a1b2c5d6e7f8a9b0c1d2e3f4a5b6c
     ***/
    public function makeAdmin(Request $request, $key)
    {
        try {
            $user = $request->user();

            /***
             * Aucune vérification d'utilisateur n'est nécessaire ici, car Sanctum vérifie déjà
             * que l'utilisateur est authentifié et existe.
             ***/

            //  Verfication de la clé
            if ($key == "4f3a1b2c5d6e7f8a9b0c1d2e3f4a5b6c") {
                // Passage de l'utilisateur en administrateur
                $user->role = User::ROLE_ADMIN;
                $user->save();

                // Envoie d'un message de confirmation
                return response()->json([
                    'message' => 'L\'utilisateur est désormais administrateur',
                ], 200);
            } else {
                // Envoie d'une erreur
                return response()->json([
                    'errors' => 'La clé fournis n\'est pas bonne',
                ], 401);
            }

        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors du passage de l\'utilisateur en administrateur' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors du passage de l\'utilisateur en administrateur',
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
                'message' => 'Utilisateur supprimé avec succès',
            ], 200);

        } catch (\Exception $e) {
            // Enregistrer un log d'erreur et envoyer une erreur à l'utilisateur
            Log::error('Erreur innatendue lors de la suppression de l\'utilisateur' . $e->getMessage());
            return response()->json([
                'errors' => 'Erreur innatendue lors de la suppression de l\'utilisateur',
            ], 500);
        }
    }

}
