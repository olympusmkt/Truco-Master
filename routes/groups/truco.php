<?php 
use App\Http\Controllers\TrucoController;

//Truco routes
Route::get('/user/truco', [TrucoController::class, 'start_Truco']);
// Route::get('/user/jogar-truco', [TrucoController::class, 'start_Truco_Page']);

Route::post('Add_Request_Truco_Room', [TrucoController::class, 'Add_Request_Truco_Room'])->name('Add_Request_Truco_Room.post');
Route::post('Add_Request_Truco_Room_Start', [TrucoController::class, 'Add_Request_Truco_Room_Start'])->name('Add_Request_Truco_Room_Start.post');
Route::post('Add_Request_Truco_Room_Cancel', [TrucoController::class, 'Add_Request_Truco_Room_Cancel'])->name('Add_Request_Truco_Room_Cancel.post');
Route::post('Get_start_Synchronize', [TrucoController::class, 'Get_start_Synchronize'])->name('Get_start_Synchronize.post');

Route::post('Add_Request_Room_Moviment_User', [TrucoController::class, 'Add_Request_Room_Moviment_User'])->name('Add_Request_Room_Moviment_User.post');
Route::post('Get_Moviments_Updater', [TrucoController::class, 'Get_Moviments_Updater'])->name('Get_Moviments_Updater.post');

//Route::post('Get_Wallet_Api', [TrucoController::class, 'Get_Wallet_Api'])->name('Get_Wallet_Api.post');
// Route::match(['get', 'post'], 'Get_Wallet_Api', [TrucoController::class, 'Get_Wallet_Api']);
Route::get('/Get_Wallet_Api/{type}',[TrucoController::class, 'Get_Wallet_Api']);
Route::get('/',[TrucoController::class, 'home_user']);
