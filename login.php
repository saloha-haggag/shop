<?php
include 'config.php';
session_start();
if(isset($_POST['submit']))
{
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $select = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');
   if(mysqli_num_rows($select) > 0){
      $row = mysqli_fetch_assoc($select);
      $_SESSION['user_id'] = $row['id'];
      header('location:index.php');
   }else
   {
      $message[] = 'Incorrect password or email!';
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <link rel="stylesheet" href="css/style.css">
   <style>
      body {
         font-family: Arial, sans-serif;
         margin: 0;
         padding: 0;
         background: linear-gradient(to right, #6a11cb, #2575fc);
         color: #fff;
         display: flex;
         justify-content: center;
         align-items: center;
         height: 100vh;
      }
      .form-container {
         background: rgba(0, 0, 0, 0.7);
         padding: 20px;
         border-radius: 10px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
         width: 90%;
         max-width: 400px;
      }
      h3 {
         text-align: center;
         margin-bottom: 20px;
         color: #ffcc00;
      }
      input.box {
         width: 100%;
         padding: 10px;
         margin: 10px 0;
         border: 1px solid #fff;
         border-radius: 5px;
         background: #222;
         color: #fff;
      }
      input.btn {
         width: 100%;
         padding: 10px;
         border: none;
         border-radius: 5px;
         background: #ffcc00;
         color: #000;
         font-size: 16px;
         cursor: pointer;
         transition: 0.3s ease;
      }
      input.btn:hover {
         background: #ffd633;
      }
      p {
         text-align: center;
         margin-top: 15px;
      }
      p a {
         color: #ffcc00;
         text-decoration: none;
         font-weight: bold;
      }
      p a:hover {
         text-decoration: underline;
      }
      .message {
         background: #ff4444;
         padding: 10px;
         border-radius: 5px;
         text-align: center;
         margin-bottom: 15px;
         cursor: pointer;
         animation: fadeIn 0.5s ease-in-out;
      }
      @keyframes fadeIn {
         from {
            opacity: 0;
         }
         to {
            opacity: 1;
         }
      }
   </style>
</head>
<body>
<?php
if(isset($message))
{
   foreach($message as $message)
   {
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>
<div class="form-container">
   <form action="" method="post">
      <h3>Login</h3>
      <input type="email" name="email" required placeholder="Email" class="box">
      <input type="password" name="password" required placeholder="Password" class="box">
      <input type="submit" name="submit" class="btn" value="Login">
      <p>Don't have an account? <a href="register.php">Register Now</a></p>
   </form>
</div>
</body>
</html>
