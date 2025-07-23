document.addEventListener("DOMContentLoaded", () => {

  const signupButton = document.querySelector(".signup button");
  const loginButton = document.querySelector(".login button");
  const forgotPasswordLink = document.getElementById("forgotPasswordLink");

  // Function to validate email format
  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  function isValidMobileNumber(number) {
  const indianMobileRegex = /^(?!(\d)\1{9})(?!1234567890)(?!0123456789)[6-9]\d{9}$/;
  return indianMobileRegex.test(number);
}

  // Signup functionality
  if (signupButton) {
    signupButton.addEventListener("click", () => {
      const signupForm = document.querySelector(".signup form");

      if (signupForm) {
        const username = signupForm.querySelector('input[name="username"]');
        const email = signupForm.querySelector('input[name="email"]');
        const mobileNumber = signupForm.querySelector('input[name="mobileNumber"]');
        const password = signupForm.querySelector('input[name="pswd"]');

        if (username.value && email.value && mobileNumber.value && password.value) {
          // Ensure the email format is valid
          if (!isValidEmail(email.value)) {
            alert("Please enter a valid email address.");
          } 
          else if (!isValidMobileNumber(mobileNumber.value)) {
            alert("Please enter a valid mobile number.");
            return false;
          }
          else {
            alert(`Welcome, ${username.value}!`);
          }
        } 
        else {
          alert("Please fill in all fields.");
        }
      }
    });
  }

  // Login functionality
  if (loginButton) {
    loginButton.addEventListener("click", () => {
      const loginForm = document.querySelector(".login form");

      if (loginForm) {
        const email = loginForm.querySelector('input[name="email"]');
        const password = loginForm.querySelector('input[name="pswd"]');

        if (email.value && password.value) {
          // Ensure the email format is valid
          if (!isValidEmail(email.value)) {
            alert("Please enter a valid email address.");
          } else {
            alert("Login successful!");
          }
        } else {
          alert("Please fill in all fields.");
        }
      }
    });
  }

  // Forgot Password functionality
  if (forgotPasswordLink) {
    forgotPasswordLink.addEventListener("click", (event) => {
      event.preventDefault();

      const email = prompt("Please enter your email to reset your password:");
      if (email && isValidEmail(email)) {
        alert(`A reset link has been sent to your email: ${email}`);
      } else {
        alert("Please enter a valid email address.");
      }
    });
  }

});


