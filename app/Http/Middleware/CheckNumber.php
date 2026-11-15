<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckNumber
{
   public function handle(Request $request, Closure $next): Response
   {
       $number1 = $request->number1;
       $number2 = $request->number2;

       // check if $number1 or $number2 is not a number
       if (!is_numeric($number1) or !is_numeric($number2)) {
           // we could use session to put error message!
           session()->flash("error", "Input must be number");
           return redirect("/number");
       }

       return $next($request);
   }
}
