<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    
    //renvoie la page du formulaire de login
    public function login_form(){
        return view('auth.log_form');
    }

    //traitement des informations reccuperer par le formulaire de login
    public function login(Request $request){

        $request->validate([
            'login' => 'required|string|max:40|min:1',
            'mdp' => 'required|string|max:40|min:1',
        ]);

        //on sauvegarde le login et le mot de passe
        //laveral utilise le champ de base 'password' nous utilisons ici 'mdp'
        $credit = [
            'login'=>$request->input('login'),
            'password'=>$request->input('mdp')
        ];

        //si authentification reussi
        if(Auth::attempt($credit)){
            $request->session()->regenerate();//regenerer la session
            $request->session()->flash('etat','Login reussi !');

            //mettre redirection vers page admin ou user ou cook
            if($request->user()->isAdmin()){
                return redirect()->intended('/admin');
            }
            return redirect()->intended('/home');//rediriger la ou il voulait aller
        }

        //si fail renvoie page precedente
        return back()->withErrors([
            'login'=>'Les informations saisis exite pas',
        ]);

    }

    //deconnection du compte
    public function logout(Request $request){
        Auth::logout();//deco
        $request->session()->invalidate();//lutte attaque
        $request->session()->regenerateToken();
        $request->session()->flash('etat','Déconnexion réussie !');
        return redirect('/');
    }


}
