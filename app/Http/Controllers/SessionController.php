<?php


namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function showExpiredSession()
    {
        return view('session.expired'); // Asegúrate de que esta vista exista
    }
}
