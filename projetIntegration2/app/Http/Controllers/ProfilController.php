<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Requests\ConnexionRequest;



class ProfilController extends Controller
{
    public function index()
    {
        return View('profil.connexion');
    }

    public function connexion(ConnexionRequest $request)
    {
        dd('fonction');
    }

    public function profil()
    {
        return View('profil.profil');
    }

    public function pageModification()
    {
        return View('profil.modification');
    }
}
