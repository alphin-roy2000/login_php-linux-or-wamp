<?php
// Include config file
require_once "confi.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT * FROM login WHERE name = ?";

        if($stmt = mysqli_prepare($con, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
         
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
        
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        echo "sdsdg";
        // Prepare an insert statement
        echo "$password";
        $sql = "INSERT INTO login values ('$param_username','$password')";
         
        if($stmt = mysqli_prepare($con, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: signin.html");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
<html>
<head>
  <title>Sign Up </title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css01.css">
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <div class="sign-up-form">
    <h1>Sign Up Now</h1>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <!--<p>Enter Name
    <input type="text" name="" placeholder="First Name">
    <input type="text" name="" placeholder="Middle Name">
    <input type="text" name="" placeholder="Last Name">
  </p>
    <p>Enter Username
    <input type="text" name="" placeholder="Username"> </p>
    <p>Enter email-->
    <div class="input-box1">
    <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" placeholder="Your username">
    </div>
    <span class="help-block"><?php echo $username_err; ?></span>
    <div class="input-box2">
    <input type="password"  name="password" class="form-control" value="<?php echo $password; ?>" placeholder="Password" id="myInput"  maxlength="21" minlength="8" required>
    <span class="eye" onclick="myFunction()">
     <i id="hide1" class="fa fa-eye"></i>
     <i id="hide2" class="fa fa-eye-slash"></i>
    </span>
    </div>
    <span class="help-block"><?php echo $password_err; ?></span>
    
    <div class="input-box2">
    <input type="password"  name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" placeholder="Confirm Password" id="myInput"  maxlength="21" minlength="8" required>
    </div>
    <span class="help-block"><?php echo $confirm_password_err; ?></span>
    <p><span><input type="checkbox"></span>I agree to the <a href="#">Terms of services<a></p>
    
    <input type="submit" class="btn btn-primary" value="Submit">
    <p>Do your have an account ? <a href="signin.html">Sign in</a></p>

  </form>
 </div>
 
 <script>
 function myFunction(){
   var x = document.getElementById("myInput");
   var y = document.getElementById("hide1");
   var z = document.getElementById("hide2");
   if(x.type === 'password'){
     x.type="text";
     y.style.display="block";
     z.style.display="none";}
   else{
     x.type="password";
     y.style.display="none";
     z.style.display="block";}}
 </script>

</body>
</html>
