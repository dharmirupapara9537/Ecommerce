import React, { useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";

export default function Register() {
  const [firstname, setFirstname] = useState("");
  const [lastname, setLastname] = useState("");
  const [phoneno, setPhoneno] = useState("");
  const [gender, setGender] = useState("Male");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [errors, setErrors] = useState({}); // ✅ object for Laravel errors

  const navigate = useNavigate();

  const handleRegister = async (e) => {
    e.preventDefault();


    const data = {
      firstname,
      lastname,
      phoneno,
      gender,
      email,
      password,
      password_confirmation: confirmPassword, // required for Laravel 'confirmed' rule
    };

    try {
      await axios.post("http://localhost:8000/api/auth/register", data);

      alert("✅ Registered successfully!");
      setErrors({});
      navigate("/admin");
    }catch (error) {
  if (error.response) {
    // Server responded with a status outside 2xx
    if (error.response.status === 422) {
      // Laravel validation errors
      setErrors(error.response.data.errors);
    } else {
      // Other server errors
      alert(`Server Error: ${error.response.status} - ${error.response.data.message || 'Unknown error'}`);
    }
  } else if (error.request) {
    // Request was made but no response received
    alert("No response from server. Please check your connection.");
  } else {
    // Something else happened
    alert(`Error: ${error.message}`);
  }
}

  };

  return (
    <div className="container mt-5" style={{ maxWidth: "500px" }}>
      <div className="card shadow">
        <div className="card-body">
          <h3 className="text-center mb-4">Register</h3>

          <form onSubmit={handleRegister}>
            {/* First Name */}
            <div className="mb-3">
              <label className="form-label">First Name</label>
              <input
                type="text"
                className="form-control"
                placeholder="Enter your firstname"
                value={firstname}
                onChange={(e) => setFirstname(e.target.value)}
                required
              />
              {errors.firstname &&
                errors.firstname.map((err, idx) => (
                  <p key={idx} style={{ color: "red" }}>
                    {err}
                  </p>
                ))}
            </div>

            {/* Last Name */}
            <div className="mb-3">
              <label className="form-label">Last Name</label>
              <input
                type="text"
                className="form-control"
                placeholder="Enter your lastname"
                value={lastname}
                onChange={(e) => setLastname(e.target.value)}
                required
              />
              {errors.lastname &&
                errors.lastname.map((err, idx) => (
                  <p key={idx} style={{ color: "red" }}>
                    {err}
                  </p>
                ))}
            </div>

            {/* Phone */}
            <div className="mb-3">
              <label className="form-label">Phone No</label>
              <input
                type="text"
                className="form-control"
                placeholder="Enter your phone number"
                value={phoneno}
                onChange={(e) => setPhoneno(e.target.value)}
                required
              />
              {errors.phoneno &&
                errors.phoneno.map((err, idx) => (
                  <p key={idx} style={{ color: "red" }}>
                    {err}
                  </p>
                ))}
            </div>

            {/* Gender */}
            <div className="mb-3">
              <label className="form-label">Gender</label>
              <div>
                <input
                  type="radio"
                  name="gender"
                  value="Male"
                  checked={gender === "Male"}
                  onChange={(e) => setGender(e.target.value)}
                />{" "}
                Male &nbsp;&nbsp;
                <input
                  type="radio"
                  name="gender"
                  value="Female"
                  checked={gender === "Female"}
                  onChange={(e) => setGender(e.target.value)}
                />{" "}
                Female
              </div>
              {errors.gender &&
                errors.gender.map((err, idx) => (
                  <p key={idx} style={{ color: "red" }}>
                    {err}
                  </p>
                ))}
            </div>

            {/* Email */}
            <div className="mb-3">
              <label className="form-label">Email</label>
              <input
                type="email"
                className="form-control"
                placeholder="Enter your email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
              />
              {errors.email &&
                errors.email.map((err, idx) => (
                  <p key={idx} style={{ color: "red" }}>
                    {err}
                  </p>
                ))}
            </div>

            {/* Password */}
            <div className="mb-3">
              <label className="form-label">Password</label>
              <input
                type="password"
                className="form-control"
                placeholder="Enter password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
              />
              {errors.password &&
                errors.password.map((err, idx) => (
                  <p key={idx} style={{ color: "red" }}>
                    {err}
                  </p>
                ))}
            </div>

            {/* Confirm Password */}
            <div className="mb-3">
              <label className="form-label">Confirm Password</label>
              <input
                type="password"
                className="form-control"
                placeholder="Confirm password"
                value={confirmPassword}
                onChange={(e) => setConfirmPassword(e.target.value)}
                required
              />
              {errors.confirmPassword &&
                errors.confirmPassword.map((err, idx) => (
                  <p key={idx} style={{ color: "red" }}>
                    {err}
                  </p>
                ))}
            </div>

            <button type="submit" className="btn btn-primary w-100">
              Register
            </button>
          </form>

          <p className="text-center mt-3">
            Already have an account? <a href="/">Login</a>
          </p>
        </div>
      </div>
    </div>
  );
}
