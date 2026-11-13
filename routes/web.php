<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\NewsEventController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\TravelGuideController;
use App\Http\Controllers\TripPlannerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Elahera Sirisara Web Routes
|--------------------------------------------------------------------------
*/

// Home & Global
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/locale/{locale}', [HomeController::class, 'switchLocale'])->name('locale.switch');
Route::get('/about', function () {
    return view('about');
})->name('about');

// Destinations
Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{slug}', [DestinationController::class, 'show'])->name('destinations.show');
Route::post('/destinations/{id}/review', [DestinationController::class, 'storeReview'])
    ->middleware('auth')
    ->name('destinations.review');
Route::delete('/destinations/reviews/{id}', [DestinationController::class, 'destroyReview'])
    ->middleware('auth')
    ->name('destinations.reviews.destroy');

// Smart Trip Planner
Route::get('/trip-planner', [TripPlannerController::class, 'index'])->name('planner.index');
Route::post('/trip-planner/save', [TripPlannerController::class, 'saveTrip'])->name('planner.save');
Route::get('/trip-planner/print', [TripPlannerController::class, 'printView'])->name('planner.print');
Route::post('/trip-planner/calculate-transit', [TripPlannerController::class, 'calculateTransit'])->name('planner.calculate-transit');
Route::post('/trip-planner/optimize-transit-sequence', [TripPlannerController::class, 'optimizeTransitSequence'])->name('planner.optimize-transit-sequence');
Route::get('/trip-planner/bus-stops', [TripPlannerController::class, 'getBusStops'])->name('planner.bus-stops');

// Interactive Map
Route::get('/map', [MapController::class, 'index'])->name('map.index');

// Public Transport
Route::get('/public-transport', [TransportController::class, 'index'])->name('transport.index');

// Community Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
Route::post('/blog/store', [BlogController::class, 'store'])->name('blog.store');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{id}/comment', [BlogController::class, 'storeComment'])->name('blog.comment');
Route::delete('/blog/comments/{id}', [BlogController::class, 'destroyComment'])
    ->middleware('auth')
    ->name('blog.comments.destroy');

// Forum (Community Q&A)
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/create', [ForumController::class, 'create'])->name('forum.create');
Route::post('/forum/store', [ForumController::class, 'store'])->name('forum.store');
Route::get('/forum/{slug}', [ForumController::class, 'show'])->name('forum.show');
Route::post('/forum/{id}/reply', [ForumController::class, 'storeReply'])->name('forum.reply');

// News & Events
Route::get('/news', [NewsEventController::class, 'index'])->name('news.index');
Route::get('/news/create', [NewsEventController::class, 'create'])->name('news.create');
Route::post('/news/store', [NewsEventController::class, 'store'])->name('news.store');
Route::get('/news/{slug}', [NewsEventController::class, 'show'])->name('news.show');
Route::post('/news/{id}/comment', [NewsEventController::class, 'storeComment'])->name('news.comment');
Route::delete('/news/comments/{id}', [NewsEventController::class, 'destroyComment'])
    ->middleware('auth')
    ->name('news.comments.destroy');

// Travel Guide & Emergency Directory
Route::get('/travel-guide', [TravelGuideController::class, 'index'])->name('guide.index');
Route::get('/emergency', [EmergencyController::class, 'index'])->name('emergency.index');

// Contact Form
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.forgot');
Route::post('/forgot-password/find', [AuthController::class, 'verifyForgotPasswordAccount'])->name('password.find');
Route::post('/forgot-password/reset', [AuthController::class, 'resetPasswordWithSecurityAnswer'])->name('password.reset.security');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated User Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('user.profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/user/saved-trips', [UserController::class, 'savedTrips'])->name('user.saved-trips');
    Route::delete('/user/saved-trips/{id}', [UserController::class, 'deleteTrip'])->name('user.delete-trip');
    Route::get('/user/my-blogs', [UserController::class, 'myBlogPosts'])->name('user.my-blogs');
});
