<?php
session_start();
include './functions.php';


if(isset($_SESSION['user'])){
    header("Location: ./index.php");
}

$usernameError = $emailError = $passwordError = "";
$role = "user"; // default


if($_SERVER['REQUEST_METHOD']=='POST'){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(!is_valid_username($username)){
        $usernameError = "* username is not valid!";
    }
    if(is_exists_username($username)){
        $usernameError = "* username is already exists!";
    }
    if(!is_valid_email($email)){
        $emailError = "* email is not valid!";
    }
    if(is_exists_email($email)){
        $emailError = "* email is already exists!";
    }
    if(!is_valid_password($password)){
        $passwordError = "* password is too short!";
    }


    if($usernameError == "" && $emailError=="" && $passwordError==""){
        registerUser($username,$email,$password);

        // echo "<script>alert('Account is created! Please Login');</script>";

        header("Location: ./alert.php");
        exit();
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(4, 5, 6, 5);
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container" style="background-color:#CCFFCC;" >
            <h2 class="text-center mb-4">Signup</h2>
            <form method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
            <div class="mb-3">
                    <label for="username" class="form-label">Username </label> <?php echo "<label for='username' class='text-danger'>$usernameError</label>";?>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Set your username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label> <?php echo "<label for='email' class='text-danger'>$emailError</label>";?>
                    <input type="email" class="form-control" id="email" name="email" placeholder="xyz@gmail.com" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label> <?php echo "<label for='password' class='text-danger'>$passwordError</label>";?>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Set strong password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Signup</button>
                <p class="mt-2">Already have an account? <a href="./login.php">Login</a></p>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
