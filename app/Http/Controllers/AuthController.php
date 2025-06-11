<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index(){
        return view('auth.login');
    }    
    public function login(){
        return view('auth.register');
    }    
        public function tampilan(){
        return view('auth.deliverbook');
    }    
        public function about(){
        return view('auth.aboutus');
    }   
            public function contact(){
        return view('auth.contactus');
    }   
}
