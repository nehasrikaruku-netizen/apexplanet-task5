<?php

include("01_db.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validation
    if(empty($username) || empty($password))
    {
        echo "<script>alert('All Fields Required');</script>";
    }

    else
    {
        // Check Existing User
        $checkUser = $conn->prepare("SELECT id FROM users WHERE username=?");

        $checkUser->bind_param("s", $username);

        $checkUser->execute();

        $result = $checkUser->get_result();

        if($result->num_rows > 0)
        {
            echo "<script>alert('Username Already Exists');</script>";
        }

        else
        {
            // Password Hashing
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert User
            $stmt = $conn->prepare("INSERT INTO users(username, password, role)
                                    VALUES(?, ?, 'user')");

            $stmt->bind_param("ss", $username, $hashedPassword);

            if($stmt->execute())
            {
                $_SESSION['username'] = $username;

                echo "<script>
                        alert('Registration Successful');
                        window.location.href='/expense_tracker/task5/03_login.php';
                      </script>";
            }

            else
            {
                echo "<script>alert('Registration Failed');</script>";
            }
        }
    }
}


?>

<!DOCTYPE html>
<html>

<head>

    <title>SpendWise - Register</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }

        body{

            height:100vh;

            display:flex;
            justify-content:center;
            align-items:center;

            background:
            linear-gradient(rgba(15,23,42,0.85),
            rgba(16,185,129,0.6)),

            url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?q=80&w=1470&auto=format&fit=crop');

            background-size:cover;
            background-position:center;
        }

        .container{

            width:380px;

            padding:35px;

            background:rgba(255,255,255,0.15);

            border-radius:20px;

            backdrop-filter:blur(12px);

            box-shadow:0 8px 32px rgba(0,0,0,0.2);

            border:1px solid rgba(255,255,255,0.2);
        }

        h2{

            text-align:center;
            color:white;
            margin-bottom:10px;

            font-size:30px;
        }

        p{

            text-align:center;
            color:#e5e7eb;

            margin-bottom:25px;

            font-size:14px;
        }

        input{

            width:100%;

            padding:14px;

            margin-bottom:18px;

            border:none;

            border-radius:12px;

            background:rgba(255,255,255,0.2);

            color:white;

            font-size:15px;
        }

        input::placeholder{

            color:#f3f4f6;
        }

        input:focus{

            outline:none;

            background:rgba(255,255,255,0.3);
        }

        button{

            width:100%;

            padding:14px;

            background:#10b981;

            border:none;

            border-radius:12px;

            color:white;

            font-size:16px;

            font-weight:bold;

            cursor:pointer;

            transition:0.3s;
        }

        button:hover{

            background:#059669;

            transform:translateY(-2px);
        }

        .login-link{

            text-align:center;

            margin-top:18px;

            color:white;
        }

        .login-link a{

            color:#d1fae5;

            text-decoration:none;

            font-weight:bold;
        }

        .login-link a:hover{

            text-decoration:underline;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>SpendWise</h2>

    <p>Create your smart expense tracker account</p>

    <form method="POST">

        <input type="text"
               name="username"
               placeholder="Enter Username"
               required>

        <input type="password"
               name="password"
               placeholder="Enter Password"
               required>

        <button type="submit">
            Register
        </button>

    </form>

    <div class="login-link">

        Already have an account?
        <a href="/expense_tracker/task5/03_login.php">
            Login
        </a>

    </div>

</div>

</body>

</html>