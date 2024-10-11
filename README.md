<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Documentations de l'api

<p>Cette API développée avec Laravel permet de gérer les utilisateurs, les événements, les catégories d'événements, ainsi que les réservations, tout en offrant un haut niveau de sécurité pour chaque requête.</p>

## Authentification
### Inscription
**Accessibilité :** `Tout les utilisateurs`<br>
**Méthode :** `POST` <br>
**URL :** `http://127.0.0.1:8000/api/register`<br>
**Corps de la requête :**
```
{
    "name": "User Name",
    "email": "user@example.com",
    "password": "votre_mot_de_passe"
    "password_confirmation": "votre_mot_de_passe_pour_confirmer"
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "user": {...},
    "token": "votre_token",
    "token_type": "Bearer"
}
```

### Connexion
**Accessibilité :** `Tout les utilisateurs`<br>
**Méthode :** `POST` <br>
**URL :** `http://127.0.0.1:8000/api/login`<br>
**Corps de la requête :**
```
{
    "email": "user@example.com",
    "password": "votre_mot_de_passe"
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "user": {...},
    "token": "votre_token",
    "token_type": "Bearer"
}
```

### Déconnexion
**Accessibilité :** `Tout les utilisateurs`<br>
**Méthode :** `POST` <br>
**URL :** `http://127.0.0.1:8000/api/logout`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": "Déconnexion réussie",
}
```

## Utilisateurs

### Obtenir tout les utilisateurs
**Accessibilité :** `Administrateurs uniquement`<br>
**Méthode :** `GET` <br>
**URL :** `http://127.0.0.1:8000/api/users`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "users": [...],
}
```

### Obtenir un seul utilisateur
**Accessibilité :** `L'utilisateur pour lui-même et les administrateurs pour tout le monde`<br>
**Méthode :** `GET` <br>
**URL :** `http://127.0.0.1:8000/api/users/{id}`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "user": {...},
}
```

### Modifier un utilisateur
**Accessibilité :** `L'utilisateur pour lui-même et les administrateurs pour tout le monde`<br>
**Méthode :** `PUT` <br>
**URL :** `http://127.0.0.1:8000/api/users/{id}`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Corps de la requête :**
```
{
    "name": "User Name",
    "email": "user@example.com",
    "password": "votre_mot_de_passe"
    "password_confirmation": "votre_mot_de_passe_pour_confirmer"
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": 'L'utilisateur a bien été modifié,
    "user": {...},
}
```

### Retirer un utilisateur
**Accessibilité :** `L'utilisateur pour lui-même et les administrateurs pour tout le monde`<br>
**Méthode :** `DELETE` <br>
**URL :** `http://127.0.0.1:8000/api/users/{id}`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": 'Utilisateur supprimé avec succès,
}
```

### Passage d'un utilisateur en administrateur
**Accessibilité :** `L'utilisateur pour lui-même et les administrateurs pour tout le monde`<br>
**Méthode :** `PUT` <br>
**URL :** `http://127.0.0.1:8000/api/makeAdmin/{key}/{id?}`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": 'L'utilisateur est désormais administrateur,
}
```

### Récupérer des reservations d'un l'utilisateur
**Accessibilité :** `Tout les utilisateurs`<br>
**Méthode :** `GET` <br>
**URL :** `http://127.0.0.1:8000/api/myReservations`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": "Voici vos réservations",
    "reservations": [...],
}
```

### Annuler une réservation
**Accessibilité :** `Tout les utilisateurs`<br>
**Méthode :** `PUT` <br>
**URL :** `http://127.0.0.1:8000/api/cancelReservation/{reservation_id}`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": "La réservation a été annulée avec succès",
    "reservation": $reservation,
}
```

## Categorie
### Récupérer toutes les catégories
**Accessibilité :** `Tout les utilisateurs`<br>
**Méthode :** `GET` <br>
**URL :** `http://127.0.0.1:8000/api/categories`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "categories": [...],
}
```

### Création d'une catégorie
**Accessibilité :** `Administrateurs uniquement`<br>
**Méthode :** `POST` <br>
**URL :** `http://127.0.0.1:8000/api/categories`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Corps de la requête :**
```
{
    "name": "Category Name",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": "La catégorie a bien été créée",
    "category" => {...},
}
```

### Récupérer une catégorie
**Accessibilité :** `Tout les utilisateurs`<br>
**Méthode :** `GET` <br>
**URL :** `http://127.0.0.1:8000/api/categories/{id}`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": "Catégorie récupérée avec succès",
    "category": {...},
}
```

### Modifier une catégorie
**Accessibilité :** `Administrateurs uniquement`<br>
**Méthode :** `PUT` <br>
**URL :** `http://127.0.0.1:8000/api/categories/{id}`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Corps de la requête :**
```
{
    "name": "Category Name",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": "La catégorie a bien été mise à jour",
    "category": {...},
}
```

### Retirer une catégorie
**Accessibilité :** `Administrateurs uniquement`<br>
**Méthode :** `DELETE` <br>
**URL :** `http://127.0.0.1:8000/api/categories/{id}`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": "Catégorie supprimée avec succès",
}
```

## Événement
### Récupérer tout les événements
**Accessibilité :** `Tout les utilisateurs`<br>
**Méthode :** `GET` <br>
**URL :** `http://127.0.0.1:8000/api/events`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "events": [...],
}
```

### Création d'un événement
**Accessibilité :** `Administrateurs uniquement`<br>
**Méthode :** `POST` <br>
**URL :** `http://127.0.0.1:8000/api/events`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Corps de la requête :**
```
{
    "title": "Event Title",
    "description": "Event Description",
    "start_datetime": "2024-10-11 11:30:00",
    "end_datetime": "2024-10-11 17:30:00",
    "address": "11 Rue jean nicoli",
    "capacity": 50,
    "category_id": 1,
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": "L\'événement a bien été créé",
    "event": {...},
}
```

### Récupérer un événement
**Accessibilité :** `Tout les utilisateurs`<br>
**Méthode :** `GET` <br>
**URL :** `http://127.0.0.1:8000/api/events/{id}`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": "Événement récupéré avec succès",
    "event": {...},
}
```

### Modifier un événement
**Accessibilité :** `Tout les utilisateurs`<br>
**Méthode :** `PUT` <br>
**URL :** `http://127.0.0.1:8000/api/events/{id}`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Corps de la requête :**
```
{
    "title": "Event Title",
    "description": "Event Description",
    "start_datetime": "2024-10-11 11:30:00",
    "end_datetime": "2024-10-11 17:30:00",
    "address": "11 Rue jean nicoli",
    "capacity": 50,
    "category_id": 1,
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": "L'événement a bien été mis à jour",
    "event": {...},
}
```

### Retirer un événement
**Accessibilité :** `Administrateurs uniquement`<br>
**Méthode :** `DELETE` <br>
**URL :** `http://127.0.0.1:8000/api/events/{id}`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": "Événement supprimé avec succès",
}
```

## Réservation
### Récupérer toutes les réservations
**Accessibilité :** `Administrateurs uniquement`<br>
**Méthode :** `GET` <br>
**URL :** `http://127.0.0.1:8000/api/reservations`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "reservations": [...],
}
```

### Création d'une réservation
**Accessibilité :** `Tout les utilisateurs`<br>
**Méthode :** `POST` <br>
**URL :** `http://127.0.0.1:8000/api/reservations`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Corps de la requête :**
```
{
    "status": "reserved",
    "number_of_seat": 4,
    "user_id": 1,
    "event_id": 1,
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": "La réservation a bien été créée",
    "reservation": {...},
}
```

### Récupérer une réservation
**Accessibilité :** `Tout les utilisateurs`<br>
**Méthode :** `GET` <br>
**URL :** `http://127.0.0.1:8000/api/reservations/{id}`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": "Réservation récupérée avec succès",
    "reservation": {...},
}
```

### Modifier une réservation
**Accessibilité :** `Tout les utilisateurs`<br>
**Méthode :** `PUT` <br>
**URL :** `http://127.0.0.1:8000/api/reservations/{id}`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Corps de la requête :**
```
{
    "status": "reserved",
    "number_of_seat": 4,
    "user_id": 1,
    "event_id": 1,
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": "La réservation a bien été mise à jour",
    "reservation": {...},
}
```

### Retirer une réservation
**Accessibilité :** `Tout les utilisateurs`<br>
**Méthode :** `DELETE` <br>
**URL :** `http://127.0.0.1:8000/api/reservations/{id}`<br>
**En-tête de la requête :**
```
{
    "Authorization": "Bearer {auth_token}",
}
```
**Code de statut attendue:** 200 <br>
**Corps de la réponse :**
```
{
    "success": "Réservation supprimée avec succès",
}
```