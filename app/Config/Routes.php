
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');
$routes->post('/login', 'Auth::doLogin');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/logout', 'Auth::logout');



$routes->get('/dashboard/status', 'Dashboard::status');
$routes->get('/device/(:any)', 'Device::details/$1');

$routes->get('/graphs', 'Graphs::index');
$routes->get('/graphs/data', 'Graphs::data');
$routes->get('graphs/devices', 'Graphs::devices');
$routes->get('graphs/sections/(:any)', 'Graphs::sections/$1');
$routes->get('graphs/parameters/(:any)/(:any)', 'Graphs::parameters/$1/$2');

$routes->get('/reports', 'Reports::index');
$routes->get('/reports/devices', 'Reports::devices');
$routes->get('/reports/sections/(:any)', 'Reports::sections/$1');
$routes->get('/reports/parameters/(:any)/(:any)', 'Reports::parameters/$1/$2');
$routes->get('/reports/data', 'Reports::data');
$routes->get('users', 'Users::index');
$routes->post('users/create', 'Users::create');
$routes->post('users/update', 'Users::update');
$routes->get('users/delete/(:num)', 'Users::delete/$1');
$routes->delete('users/delete/(:num)', 'Users::delete/$1');
$routes->post('users/update/(:num)', 'Users::update/$1');
$routes->get('traps', 'TrapController::index');
$routes->get('trap-refresh', 'TrapController::refresh');
$routes->get('traps/testCacti', 'TrapController::testCacti');
$routes->get('reports/getDevices', 'Reports::getDevices');
$routes->post('reports/getReportData', 'Reports::getReportData');
$routes->post('reports/exportRaw', 'Reports::exportRaw');
