<!DOCTYPE html>
<html>
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="jquery.js"></script>
  <title> Registration Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <h4 class="mb-4 text-center">Registration Form</h4>
                
                    <form action="{{ route('register') }}" method="POST">
                    @csrf
                        <div class="row mb-3">
                            <label for="fullname" class="col-sm-3 col-form-label">First Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control >" id="firstname" name="firstname" value="{{ old('firstname') }}" placeholder="Enter your first name">
                                @error('firstname')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="fullname" class="col-sm-3 col-form-label">Last Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control >" id="lastname" name="lastname" value="{{ old('lastname') }}" placeholder="Enter your last name">
                               @error('lastname')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                         <div class="row mb-3">
                            <label for="fullname" class="col-sm-3 col-form-label">Phone No</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control >" id="phoneno" name="phoneno" value="{{ old('phoneno') }}" placeholder="Enter your phone no">
                               @error('phoneno')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Gender</label>
                            <div class="col-sm-9 d-flex align-items-center">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} checked="checked">
                                    <label class="form-check-label" for="male">Male</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="female">Female</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="fullname" class="col-sm-3 col-form-label">Email Id</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control >" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email id">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="fullname" class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control >" id="password" name="password" value="" placeholder="Enter your password">
                               @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="fullname" class="col-sm-3 col-form-label">Confirm Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control >" id="password_confirmation" name="password_confirmation" value="" placeholder="Enter your confirm password">
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="offset-sm-3 col-sm-9">
                                <button type="submit" class="btn btn-primary w-100">Register</button>
                            </div>
                        </div>
                        
                    </form>
            </div>
        </div>   
    </div>
</body>
</html>
