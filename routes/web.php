<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::any('bot', [\App\Http\Controllers\Botcontroller::class,"index"]);

// Route::any('/bot', function(Request $input)
// {
//     if (isset($input['hub_verify_token'])) { ## allows facebook verify that this is the right webhook
//         if ($input['hub_verify_token'] ==="EmmaToken") {
//             return $input['hub_challenge'];
//             dd();
//         } else {
//             echo 'Invalid Verify Token';
//             dd();
//         }
//     }
// });
