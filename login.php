<?php
session_start();
include './functions.php';

if(isset($_SESSION['user'])){
    header("Location: ./index.php");
}


$emailError = $authenticationFailed = "";


if($_SERVER['REQUEST_METHOD']=='POST'){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(!is_valid_email($email)){
        $emailError="* Your mail is not valid!";
    }

    $user = authenticateUser($email, $password);
    if($user){
        $_SESSION['user'] = $user;
        header("Location: ./index.php");
    } else {
        $authenticationFailed = "Invalid email or password";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

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
    <div class="container" >
        <div class="login-container" style="background-color:#CCFFFF;" >
            <h2 class="text-center mb-4">Login</h2>
            <form method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label> <?php echo "<label for='email' class='text-danger'>$emailError</label>";?>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="mb-3">
                    <label class="text-danger"><?php echo $authenticationFailed;?></label>
                </div>
                <div class="mb-2" >
                    <?php     
                    echo "Email = mfaridhossain24@gmail.com "."<br>";
                    echo "Password = farid12"."<br>";
                    echo "Role = Admin ";
                    
                    ?>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>

                <p class="mt-2">Don't have any account? <a href="./registration.php">Signup</a></p>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
