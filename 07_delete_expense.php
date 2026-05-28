<?php

include("01_db.php");

session_start();

// CHECK LOGIN

if(!isset($_SESSION['username']))
{
    header("Location: /expense_tracker/task5/03_login.php");
    exit();
}

$username = $_SESSION['username'];

// CHECK ID

if(!isset($_GET['id']))
{
    header("Location: /expense_tracker/task5/05_view_expense.php");
    exit();
}

$id = $_GET['id'];

// DELETE ONLY CURRENT USER EXPENSE

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM expenses
     WHERE id=? AND username=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "is",
    $id,
    $username
);

if(mysqli_stmt_execute($stmt))
{
    echo "<script>
            alert('Expense Deleted Successfully');
            window.location.href='05_view_expense.php';
          </script>";
}

else
{
    echo "<script>
            alert('Error Deleting Expense');
            window.location.href='05_view_expense.php';
          </script>";
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Delete Expense</title>

<style>

body{

    font-family:Arial, sans-serif;

    background:#0f172a;

    color:white;

    display:flex;

    justify-content:center;

    align-items:center;

    height:100vh;
}

.container{

    text-align:center;

    background:#1e293b;

    padding:40px;

    border-radius:15px;

    box-shadow:0 4px 20px rgba(0,0,0,0.3);
}

a{

    color:#10b981;

    text-decoration:none;

    font-weight:bold;
}

</style>

</head>

<body>

<div class="container">

<h2>Deleting Expense...</h2>

<p>Please wait...</p>

<a href="/expense_tracker/task5/05_view_expense.php">
Back to Expenses
</a>

</div>

</body>

</html>