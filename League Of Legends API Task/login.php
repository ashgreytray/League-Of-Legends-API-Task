<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      background: url('background.png') no-repeat center center/cover;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
    }

    .login-container {
      background: rgba(219, 202, 202, 0.95);
      padding: 40px;
      border-radius: 15px;
      text-align: center;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      width: 300px;
    }

    .login-container img {
      width: 80px;
      margin-bottom: 20px;
      border-radius: 50%;
    }

    .input-box {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    .login-btn {
      background-color: #003366;
      color: white;
      border: none;
      padding: 10px;
      width: 100%;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 10px;
    }

    .login-btn:hover {
      background-color: #002244;
    }

    .error {
      color: red;
      font-size: 14px;
      margin-top: 5px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <img src="image.png" alt="Coastal School Logo">

    <form onsubmit="return validateForm(event)">
      <label for="username">Username:</label>
      <input type="text" id="username" class="input-box" required>

      <label for="pincode">Pincode:</label>
      <input type="password" id="pincode" class="input-box" required>

      <button type="submit" class="login-btn">Login</button>
      <p id="error-msg" class="error"></p>
    </form>
  </div>

  <script>
    function validateForm(event) {
      event.preventDefault(); // Prevent form submission

      const username = document.getElementById('username').value.trim();
      const pincode = document.getElementById('pincode').value.trim();
      const errorMsg = document.getElementById('error-msg');

      if (!username || !pincode) {
        errorMsg.textContent = 'Please fill in both fields.';
        return false;
      }

      // If fields are filled, redirect to index.html
      window.location.href = 'index.html';
      return false;
    }
  </script>
</body>
</html>
