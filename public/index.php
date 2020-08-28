<?php
include '../src/config/constants.php';
include 'autoload.inc.php';

use src\routing\Route;
use src\routing\Request;
use src\routing\Router;
use src\controllers\BaseController;
use src\controllers\AboutUsController;
use src\controllers\ContactUsController;
use src\controllers\JSONController;


/**
 * This is the base, anything directed to the
 * base address will be directed here.
 */
Route::set('index.php', function () {
	BaseController::createView('home');
});

/**
 * These examples returns a view that can be html or php.
 */
Route::set('about-us', function () {
	AboutUsController::createView('about-us');
});
Route::set('contact-us', function () {
	ContactUsController::createView('contact-us');
});

/**
 * This route is an example of getting json data.
 */
Route::set('get-puppy', function () {
	header('Content-Type: application/json');
	echo JSONController::getJSON();
});

/**
 * This route is an example of setting json data.
 */
Route::set('set-puppy', function () {
	JSONController::setJSON();
});


$router = new Router(new Request);
$router->get('/profile', function ($request) {
	return "<h1>Request is working!</h1>";
});
$router->get('/', function ($request) {
	return "<h1>home is working!</h1>";
});
$router->post('/something', function ($request) {
	$body = $request->getBody();
});
