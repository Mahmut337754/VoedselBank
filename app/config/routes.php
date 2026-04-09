<?php
 
/**
* ============================================================
* ROUTES.PHP
* Centrale route-definities van de applicatie
* Project: VoedselBank Maaskantje
* ============================================================
*
* Opbouw:
* 'url-pad' => [
*     'controller' => 'NaamController',
*     'method' => 'methodeNaam'
* ]
*
* Let op:
* - Gebruik GEEN trailing slash aan het einde van routes
* - Alles loopt via public/index.php
*/
 
return [
 
    /**
     * --------------------------------------------------------
     * Home
     * --------------------------------------------------------
     */
    '/' => [
        'controller' => 'HomeController',
        'method' => 'index'
    ],
 
    /**
     * --------------------------------------------------------
     * Authenticatie
     * --------------------------------------------------------
     */
    '/login' => [
        'controller' => 'AuthController',
        'method' => 'login'
    ],
 
    '/register' => [
        'controller' => 'AuthController',
        'method' => 'register'
    ],
 
    '/logout' => [
        'controller' => 'AuthController',
        'method' => 'logout'
    ],
 
    '/forgot-password' => [
        'controller' => 'AuthController',
        'method' => 'forgotPassword'
    ],
 
    /**
     * --------------------------------------------------------
     * Dashboard
     * --------------------------------------------------------
     */
    '/dashboard' => [
        'controller' => 'DashboardController',
        'method' => 'index'
    ],
 
    /**
     * --------------------------------------------------------
     * Accounts / Gebruikersbeheer
     * --------------------------------------------------------
     */
    '/accounts' => [
        'controller' => 'AccountController',
        'method' => 'index'
    ],
 
    '/accounts/create' => [
        'controller' => 'AccountController',
        'method' => 'create'
    ],
 
    '/accounts/store' => [
        'controller' => 'AccountController',
        'method' => 'store'
    ],
 
    '/accounts/show' => [
        'controller' => 'AccountController',
        'method' => 'show'
    ],
 
    '/accounts/edit' => [
        'controller' => 'AccountController',
        'method' => 'edit'
    ],
 
    '/accounts/update' => [
        'controller' => 'AccountController',
        'method' => 'update'
    ],
 
    '/accounts/delete' => [
        'controller' => 'AccountController',
        'method' => 'delete'
    ],
 
    /**
     * --------------------------------------------------------
     * Users
     * --------------------------------------------------------
     */
    '/users' => [
        'controller' => 'UserController',
        'method' => 'index'
    ],
 
    '/users/create' => [
        'controller' => 'UserController',
        'method' => 'create'
    ],
 
    '/users/store' => [
        'controller' => 'UserController',
        'method' => 'store'
    ],
 
    '/users/show' => [
        'controller' => 'UserController',
        'method' => 'show'
    ],
 
    '/users/edit' => [
        'controller' => 'UserController',
        'method' => 'edit'
    ],
 
    '/users/update' => [
        'controller' => 'UserController',
        'method' => 'update'
    ],
 
    '/users/delete' => [
        'controller' => 'UserController',
        'method' => 'delete'
    ],
 
    /**
     * --------------------------------------------------------
     * Klanten
     * --------------------------------------------------------
     */
    '/klanten' => [
        'controller' => 'KlantController',
        'method' => 'index'
    ],
 
    '/klanten/create' => [
        'controller' => 'KlantController',
        'method' => 'create'
    ],
 
    '/klanten/store' => [
        'controller' => 'KlantController',
        'method' => 'store'
    ],
 
    '/klanten/show' => [
        'controller' => 'KlantController',
        'method' => 'show'
    ],
 
    '/klanten/edit' => [
        'controller' => 'KlantController',
        'method' => 'edit'
    ],
 
    '/klanten/update' => [
        'controller' => 'KlantController',
        'method' => 'update'
    ],
 
    '/klanten/delete' => [
        'controller' => 'KlantController',
        'method' => 'delete'
    ],
 
    /**
     * --------------------------------------------------------
     * Leveranciers
     * --------------------------------------------------------
     */
    '/leveranciers' => [
        'controller' => 'LeverancierController',
        'method' => 'index'
    ],
 
    '/leveranciers/create' => [
        'controller' => 'LeverancierController',
        'method' => 'create'
    ],

    '/leveranciers/nieuw' => [
        'controller' => 'LeverancierController',
        'method' => 'create'
    ],
 
    '/leveranciers/store' => [
        'controller' => 'LeverancierController',
        'method' => 'store'
    ],
 
    '/leveranciers/show' => [
        'controller' => 'LeverancierController',
        'method' => 'show'
    ],
 
    '/leveranciers/edit' => [
        'controller' => 'LeverancierController',
        'method' => 'edit'
    ],
 
    '/leveranciers/update' => [
        'controller' => 'LeverancierController',
        'method' => 'update'
    ],
 
    '/leveranciers/delete' => [
        'controller' => 'LeverancierController',
        'method' => 'delete'
    ],
 
    /**
     * --------------------------------------------------------
     * Producten
     * --------------------------------------------------------
     */
    '/producten' => [
        'controller' => 'ProductController',
        'method' => 'index'
    ],
 
    '/producten/create' => [
        'controller' => 'ProductController',
        'method' => 'create'
    ],
 
    '/producten/store' => [
        'controller' => 'ProductController',
        'method' => 'store'
    ],
 
    '/producten/show' => [
        'controller' => 'ProductController',
        'method' => 'show'
    ],
 
    '/producten/edit' => [
        'controller' => 'ProductController',
        'method' => 'edit'
    ],
 
    '/producten/update' => [
        'controller' => 'ProductController',
        'method' => 'update'
    ],
 
    '/producten/delete' => [
        'controller' => 'ProductController',
        'method' => 'delete'
    ],
 
    /**
     * --------------------------------------------------------
     * Voorraad
     * --------------------------------------------------------
     */
    '/voorraad' => [
        'controller' => 'VoorraadController',
        'method' => 'index'
    ],
 
    '/voorraad/create' => [
        'controller' => 'VoorraadController',
        'method' => 'create'
    ],
 
    '/voorraad/store' => [
        'controller' => 'VoorraadController',
        'method' => 'store'
    ],
 
    '/voorraad/show' => [
        'controller' => 'VoorraadController',
        'method' => 'show'
    ],
 
    '/voorraad/edit' => [
        'controller' => 'VoorraadController',
        'method' => 'edit'
    ],
 
    '/voorraad/update' => [
        'controller' => 'VoorraadController',
        'method' => 'update'
    ],
 
    '/voorraad/delete' => [
        'controller' => 'VoorraadController',
        'method' => 'delete'
    ],
 
    /**
     * --------------------------------------------------------
     * Voedselpakketten
     * --------------------------------------------------------
     */
    '/voedselpakketten' => [
        'controller' => 'VoedselpakketController',
        'method' => 'index'
    ],
 
    '/voedselpakketten/create' => [
        'controller' => 'VoedselpakketController',
        'method' => 'create'
    ],
 
    '/voedselpakketten/store' => [
        'controller' => 'VoedselpakketController',
        'method' => 'store'
    ],
 
    '/voedselpakketten/show' => [
        'controller' => 'VoedselpakketController',
        'method' => 'show'
    ],
 
    '/voedselpakketten/edit' => [
        'controller' => 'VoedselpakketController',
        'method' => 'edit'
    ],
 
    '/voedselpakketten/update' => [
        'controller' => 'VoedselpakketController',
        'method' => 'update'
    ],
 
    '/voedselpakketten/delete' => [
        'controller' => 'VoedselpakketController',
        'method' => 'delete'
    ],
 
    /**
     * --------------------------------------------------------
     * Allergieën / Wensen
     * --------------------------------------------------------
     */
    '/allergieen' => [
        'controller' => 'AllergieController',
        'method' => 'index'
    ],
 
    '/allergieen/create' => [
        'controller' => 'AllergieController',
        'method' => 'create'
    ],
 
    '/allergieen/store' => [
        'controller' => 'AllergieController',
        'method' => 'store'
    ],
 
    '/allergieen/show' => [
        'controller' => 'AllergieController',
        'method' => 'show'
    ],
 
    '/allergieen/edit' => [
        'controller' => 'AllergieController',
        'method' => 'edit'
    ],
 
    '/allergieen/update' => [
        'controller' => 'AllergieController',
        'method' => 'update'
    ],
 
    '/allergieen/delete' => [
        'controller' => 'AllergieController',
        'method' => 'delete'
    ],
];