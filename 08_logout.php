<?php

session_start();

// REMOVE ALL SESSION VARIABLES

session_unset();

// DESTROY SESSION

session_destroy();

// REDIRECT TO LOGIN PAGE

echo "<script>

alert('Logout Successful');

window.location.href='/expense_tracker/task5/03_login.php';

</script>";

?>

<!DOCTYPE html>
<html>

<head>

<title>Logout - SpendWise</title>

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
    linear-gradient(rgba(15,23,42,0.9),
    rgba(16,185,129,0.6));

    color:white;
}

.container{

    text-align:center;

    background:rgba(255,255,255,0.1);

    padding:40px;

    border-radius:20px;

    backdrop-filter:blur(10px);

    box-shadow:0 8px 32px rgba(0,0,0,0.2);

    width:350px;
}

h2{

    margin-bottom:15px;

    font-size:28px;
}

p{

    color:#d1fae5;

    font-size:16px;
}

</style>

</head>

<body>

<div class="container">

<h2>Logging Out...</h2>

<p>Please wait...</p>

</div>

</body>

</html>