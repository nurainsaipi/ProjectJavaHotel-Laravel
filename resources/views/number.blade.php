<div>
   <form action="/number" method="post">
       @csrf
       <input type="text" name="number1" placeholder="Enter First Number"/>
       <br/>
       <input type="text" name="number2" placeholder="Enter Second Number"/>
       <br/>
       <input type="submit" name="submit" value="Submit"/>
   </form>
</div>

<div>
   @if (isset($result))
       <h3>{{ $number1 }} + {{ $number2 }} is {{ $result }}</h3>
   @endif
</div>

<div>
   @if (!empty(session("error", "")))
       <h4>{{ session("error", "") }}</h4>
   @endif
</div>
