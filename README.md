<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

### Documentations de l'api

<p>Cette api réalisé avec laravel permet la gestion d'utilisateurs, d'événments, de catégories d'événements et surtout de réservation avec une sécurité assez conséquente pour toute requete.</p>

## Authentification
### Connexion
- Envoi de la requête :
**Méthode :** POST  
**URL :** `http://127.0.0.1:8000/api/login`  
**Corps de la requête :** 
{
    "email": "user@example.com",
    "password": "votre_mot_de_passe"
}
- Réponse attendue :
**Code de statut :** 200  
**Corps de la réponse :** 
{
    "user": {...},
    "token": "votre_token",
    "token_type": "Bearer"
}
