<?php

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

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
Route::get('/qr', function()
{
    $user = [];
    $user['id'] = 1;
    $user["name"] = "Robert";
    $user["description"] = "Student ce doreste nota mare la licenta!";
    $user["status"] = "cu capu' in nori";
    $text = json_encode($user);
    $renderer = new ImageRenderer(
        new RendererStyle(400),
        new SvgImageBackEnd()
    );
    $writer = new Writer($renderer);
    $writer->writeFile($text, 'qrcode.svg');
    return view("welcome");
});