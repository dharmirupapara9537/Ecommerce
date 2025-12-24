import React, { useState } from 'react';
import axios from 'axios';
import { useNavigate , Link } from 'react-router-dom';

function Login() {
  const navigate = useNavigate();
const [error, setError] = useState("");
  const handleLogin = async (e) => {
    e.preventDefault();
    

    const data = {
      email: e.target.email.value,
      password: e.target.password.value
    };

    try {
      const response = await axios.post('http://localhost:8000/api/auth/login', data);
 console.log('Login response:', response.data);

       // console.log(response.data.message);
        //setError('');
      // Save token in localStorage
      localStorage.setItem('admin_token', response.data.access_token);
     localStorage.setItem("role", response.data.user.roles[0]);
setError('');
      // Redirect to dashboard
      navigate('/admin/dashboard');

    }  catch (err) {
      if (err.response) {
        setError(err.response.data.message || 'Invalid credentials');
      } else if (err.request) {
        setError('Server not responding. Check backend.');
      } else {
        setError('An error occurred. Please try again.');
      }
      console.error('Login error:', err);
    }
  };

  return (
       <div className="container mt-5" style={{ maxWidth: "450px" }}>
      <div className="card shadow">
        <div className="card-body">
          
          <h3 className="text-center mb-4">Login</h3>
          {error && <p style={{ color: 'red' }}>{error}</p>}
           <div class="row mb-3"></div>
    <form onSubmit={handleLogin}>
      
        <div className="mb-3">
             <label className="form-label">Email</label> 
      <input type="email" name="email"  className="form-control" placeholder="Email" required />
      </div>
                  <div class="mb-3">
              <label className="form-label">Password</label>
              
      <input type="password" name="password"  className="form-control" placeholder="Password" required />
      </div>
        <button type="submit" className="btn btn-primary w-100">
              Login
            </button>
    </form>
    <p className="text-center mt-2  text-muted">
          Don't have an account? <Link to="/register">Register here</Link>
        </p>
    </div>
    </div>
    </div>
  );
}

export default Login;
