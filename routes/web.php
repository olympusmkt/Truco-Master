<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// App users
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserPublicController;
use App\Http\Controllers\DrakenController;


///////////////////////// PUBLIC ROUTES ////////////////////////////
// Route Get Page -> PUBLIC
// Route::get('/', function () {
//     return redirect('user/login');
// });

// Route Get Page -> DASHBOARD
Route::get('/dashboard/',[DashboardController::class, 'dashboardHome']);
Route::get('/dashboard/gerenciador-de-arquivos',[DashboardController::class, 'dashboardHomeManagerFiller']);

//////////////////////// DASHBOARD PUBLIC ROUTES ////////////////////////
// Route Get Pages-> login, registro, minha conta, sair
Route::get('/user/', function () { return redirect('../'); });
Route::get('/user/login',[UserPublicController::class, 'userPublicLoginPage']);
Route::get('/user/registro',[UserPublicController::class, 'userPublicRegisterPage']);
Route::get('/user/minha-conta',[UserPublicController::class, 'userPublicProfile']);
Route::get('/user/sair',[UserPublicController::class, 'userPublicLogout']);
Route::get('/user/cron',[UserPublicController::class, 'userCronExpira']);

// Route Post Ajax -> registro, login, updater

Route::post('RegisterPublicUserPost', [UserPublicController::class, 'RegisterPublicUserPost'])->name('RegisterPublicUserPost.post');


Route::post('LoginPublicUserPost', [UserPublicController::class, 'LoginPublicUserPost'])->name('LoginPublicUserPost.post');
Route::post('updaterPublicUserPost', [UserPublicController::class, 'updaterPublicUserPost'])->name('updaterPublicUserPost.post');
Route::post('updaterPublicPassUserPost', [UserPublicController::class, 'updaterPublicPassUserPost'])->name('updaterPublicPassUserPost.post');
Route::post('updaterUserPostListPublic', [UserPublicController::class, 'updaterUserPostListPublic'])->name('updaterUserPostListPublic.post');
Route::post('RegisterUserPostDashboard', [UserPublicController::class, 'RegisterUserPostDashboard'])->name('RegisterUserPostDashboard.post');

//////////////////////// DASHBOARD ROUTES ////////////////////////
// Route Get Pages-> login, registro, minha conta, sair
Route::get('/dashboard/login',[UserController::class, 'userLoginPage']);
Route::get('/dashboard/minha-conta',[UserController::class, 'userProfile']);
Route::get('/dashboard/sair',[UserController::class, 'userLogout']);

// Route Get Pages-> usuario, add usuario, usuarios
Route::match(['get', 'post'], '/dashboard/usuarios',[UserPublicController::class, 'dashboardUsersList']);
Route::get('/dashboard/usuario/{id_user}',[UserPublicController::class, 'dashboardUsersEdit']);
Route::get('/dashboard/adicionar-usuario',[UserPublicController::class, 'userPublicRegisterPageDashboard']);

// Route Get Pages-> administrador, add usuario, usuarios
Route::match(['get', 'post'], '/dashboard/administradores',[UserController::class, 'dashboardUsersList']);
Route::get('/dashboard/administrador/{id_user}',[UserController::class, 'dashboardUsersEdit']);
Route::get('/dashboard/adicionar-administrador',[UserController::class, 'userPublicRegisterPageDashboard']);

// Route Post Ajax -> registro, login, updater
Route::post('LoginUserPost', [UserController::class, 'LoginUserPost'])->name('LoginUserPost.post');
Route::post('updaterUserPost', [UserController::class, 'updaterUserPost'])->name('updaterUserPost.post');
Route::post('updaterPassUserPost', [UserController::class, 'updaterPassUserPost'])->name('updaterPassUserPost.post');

// Route Get Pages-> list update, add admin dashboard
Route::post('updaterUserPostList', [UserController::class, 'updaterUserPostList'])->name('updaterUserPostList.post');
Route::post('RegisterUserPostDashboard', [UserController::class, 'RegisterUserPostDashboard'])->name('RegisterUserPostDashboard.post');

// Route Get Pages-> GERENCIADOR DE ARQUIVOS
Route::post('dashboardHomeManagerFillerPost', [DashboardController::class, 'dashboardHomeManagerFillerPost'])->name('dashboardHomeManagerFillerPost.post');

// Route Get Pages-> Create Function
Route::post('add_Function_Dash', [DrakenController::class, 'add_Function_Dash'])->name('add_Function_Dash.post');
Route::get('/dashboard/source', [DrakenController::class, 'preview_Source']);
Route::match(['get', 'post'], '/dashboard/funcoes/', [DrakenController::class, 'preview_Source_List']);
Route::post('delet_Function_Dash', [DrakenController::class, 'delet_Function_Dash'])->name('delet_Function_Dash.post');

// FUNCTIONS USER APP
include_once(__DIR__ . '/groups/truco.php');

// CACHE ROUTER
Route::get('/clear', function() {
    Artisan::call('optimize:clear');
});

