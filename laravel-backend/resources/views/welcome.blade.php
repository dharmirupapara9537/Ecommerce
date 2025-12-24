<!DOCTYPE html>
<html lang="">
    <head>
            <title>Laravel</title>

       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
   
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
  
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
       
        <!--<li class="nav-item">
          <a class="nav-link" href="{{url('contactus')}}">Contact Us</a>
        </li>-->
       @guest
        
        <li class="nav-item">
          <a class="nav-link" href="{{url('register')}}">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{url('login')}}">Login</a>
        </li>
        @endguest
      @auth
       <li class="nav-item">
          <a class="nav-link" href="{{url('welcome')}}">Home</a>
        </li>
      <li class="nav-item">
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button class="nav-link" type="submit">Logout</button>
    </form>
         
        </li>
      @endauth
      </ul>
    </div>
  </div>
</nav>
  @if(session('success'))
    <div style="color: green; margin-bottom: 10px;">
        {{ session('success') }}
    </div>
@endif

@if(Auth::check())
    <h3>Welcome, {{ Auth::user()->firstname }}!</h3>
    @endif
    </body>
</html>
