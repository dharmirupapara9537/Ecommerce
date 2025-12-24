
<!DOCTYPE html>
<html>
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="jquery.js"></script>
  <title>Login Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
     
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <h3 class="mb-4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Login Form</h3>
        
        <form method="POST" action="{{ route('login') }}" enctype="multipart/form-data">
          <!-- Full Name -->
           @csrf
          <div class="row mb-2">
            <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Email Id</label>
            <div class="col-sm-6">
               <input type="text" class="form-control " name="email" value="{{ old('email') }}">
               @error('email') <div style="color: red;">{{ $message }}</div> @enderror
            </div>
          </div>
          <div class="row mb-2">
            <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-6">
              <input type="password" name="password" class="form-control">
              @error('password') <div style="color: red;">{{ $message }}</div> @enderror
   
            </div>
          </div>
         
           
      <div class="row mb-2">
            <label for="exampleInputEmail1" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-8">
              Not regiser than register here
              <a href="{{url('register')}}">Register</a>
          </div>
          </div>
     
          <!-- Submit -->
          <div class="row">
            <div class="offset-sm-3 col-sm-6">
              <button type="submit" class="btn btn-primary w-50">Login</button>              
            </div>
               
          </div>
          
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>