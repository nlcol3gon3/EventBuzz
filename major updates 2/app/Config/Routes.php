<?php

use CodeIgniter\Router\RouteCollection;

$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->get('events', 'Home::events');

$routes->group('auth', ['filter' => 'notLoggedIn'], function ($routes) {
    // Registration routes
    $routes->get('user/register', 'UserController::register');
    $routes->post('user/store', 'UserController::store');

    $routes->get('organizer/register', 'OrganizerController::register');
    $routes->post('organizer/store', 'OrganizerController::store');

    $routes->get('admin/register', 'AdminController::register');
    $routes->post('admin/store', 'AdminController::store');

    // Login routes
    $routes->get('user/login', 'UserController::login');
    $routes->post('user/login/action', 'UserController::login_action');

    $routes->get('organizer/login', 'OrganizerController::login');
    $routes->post('organizer/login/action', 'OrganizerController::login_action');

    $routes->get('admin/login', 'AdminController::login');
    $routes->post('admin/login/action', 'AdminController::login_action');
});

$routes->group('auth', function ($routes) {
    // Logout routes
    $routes->get('user/logout', 'UserController::logout');
    $routes->get('organizer/logout', 'OrganizerController::logout');
    $routes->get('admin/logout', 'AdminController::logout');
});

$routes->group('admin', ['filter' => 'adminAuth'], function ($routes) {
    // Admin dashboard and management routes
    $routes->get('/', 'AdminController::index');
    $routes->get('dashboard', 'AdminController::index');
    $routes->get('events', 'EventController::index');
    $routes->get('users', 'UserController::index');
    $routes->get('organizers', 'OrganizerController::index');
});

$routes->group('organizer', ['filter' => 'userAuth:organizer'], function ($routes) {
    // Organizer dashboard and event management routes
    $routes->get('/', 'EventController::index');
    $routes->get('dashboard', 'EventController::index');
    $routes->get('events', 'EventController::index');
    $routes->get('events/create', 'EventController::create');
    $routes->post('events/store', 'EventController::store');
    $routes->get('events/edit/(:num)', 'EventController::edit/$1');
    $routes->post('events/update/(:num)', 'EventController::update/$1');
    $routes->get('events/delete/(:num)', 'EventController::delete/$1');
});

$routes->group('user', ['filter' => 'userAuth:user'], function ($routes) {
    // User dashboard and event browsing routes
    $routes->get('/', 'EventController::index');
    $routes->get('dashboard', 'EventController::index');
    $routes->get('events', 'EventController::events');
    $routes->get('events/search', 'EventController::search');
    $routes->get('events/book/(:num)', 'BookingController::create/$1');
    $routes->post('events/bookings/store', 'BookingController::store/$1');
    $routes->get('bookings/payment/(:num)', 'BookingController::payment/$1');
    $routes->get('bookings/cancel/(:num)', 'BookingController::cancel/$1');
});

// Additional routes for payment and booking callbacks
$routes->post('bookings/stk-query', 'BookingController::stkQuery');
$routes->post('bookings/stk-push', 'BookingController::stkPush');
$routes->post('bookings/paymentCallback', 'BookingController::paymentCallback');

?>

