<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class GreetingController extends Controller
{
   public function index() {
       return "Hello from Controller";
   }

   public function greet(string $name) {
       return view("greeting", ["name" => $name]);
   }
}
