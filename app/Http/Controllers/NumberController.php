<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NumberController extends Controller
{
   public function show() {
       return view("number");
   }

   public function check(Request $request) {
       $number1 = $request->number1;
       $number2 = $request->number2;
       $result = $number1 + $number2;
       return view("number", [
           "result" => $result,
           "number1" => $number1,
           "number2" => $number2,
       ]);
   }
}
