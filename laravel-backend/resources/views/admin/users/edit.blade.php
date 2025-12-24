
@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h3>Update User</h3>
    
        <form action="{{ route('users.update' , $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
          @method('PUT')
                        <div class="row mb-3">
                            <label for="fullname" class="col-sm-2 col-form-label">First Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="firstname" name="firstname" value="{{ $user->firstname }}">
                                @error('firstname')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="fullname" class="col-sm-2 col-form-label">Last Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control >" id="lastname" name="lastname" value="{{ $user->lastname }}" >
                               @error('lastname')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                         <div class="row mb-3">
                            <label for="fullname" class="col-sm-2 col-form-label">Phone No</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control >" id="phoneno" name="phoneno" value="{{ $user->phoneno }}" >
                               @error('phoneno')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Gender</label>
                            <div class="col-sm-9 d-flex align-items-center">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ $user->gender == 'male' ? 'checked' : '' }} >
                                    <label class="form-check-label" for="male">Male</label>
                                </div>&nbsp;
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ $user->gender == 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="female">Female</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="fullname" class="col-sm-2 col-form-label">Email Id</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control >" id="email" name="email" value="{{ $user->email }}" >
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                       <!-- <div class="row mb-3">
                            <label for="fullname" class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control >" id="password" name="password" value="" >
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
                        </div>-->
                        <div class="row">
                            <div class="offset-sm-2 col-sm-2">
                                <button type="submit" class="btn btn-primary w-100">Update User</button>
                            </div>
                        </div>
                        
                    </form>

</div>
@endsection