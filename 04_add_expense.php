<?php

include("01_db.php");

session_start();

// CHECK LOGIN

if(!isset($_SESSION['username']))
{
    header("Location: 03_login.php");
    exit();
}

$username = $_SESSION['username'];

if(isset($_POST['submit']))
{
    $title = trim($_POST['title']);
    $amount = trim($_POST['amount']);
    $category = trim($_POST['category']);
    $payment = trim($_POST['payment']);
    $expense_date = trim($_POST['expense_date']);
    $description = trim($_POST['description']);

    // VALIDATION

    if(empty($title) || empty($amount) || empty($category) || empty($payment) || empty($expense_date))
    {
        echo "<script>alert('Please Fill All Required Fields');</script>";
    }

    elseif(!is_numeric($amount))
    {
        echo "<script>alert('Amount Must Be Numeric');</script>";
    }

    elseif($amount <= 0)
    {
        echo "<script>alert('Amount Must Be Greater Than 0');</script>";
    }

    else
    {
        // FETCH USER EXPENSE LIMIT

        $limit_query = "SELECT expense_limit
                        FROM users
                        WHERE username='$username'";

        $limit_result = mysqli_query($conn, $limit_query);

        $limit_row = mysqli_fetch_assoc($limit_result);

        $expense_limit = $limit_row['expense_limit'];

        // FETCH CURRENT USER TOTAL EXPENSE

        $total_query = "SELECT SUM(amount) AS total_expense
                        FROM expenses
                        WHERE username='$username'";

        $total_result = mysqli_query($conn, $total_query);

        $total_row = mysqli_fetch_assoc($total_result);

        $current_expense = $total_row['total_expense'];

        // HANDLE NULL VALUE

        if($current_expense == NULL)
        {
            $current_expense = 0;
        }

        $new_total = $current_expense + $amount;

        // CHECK LIMIT

        if($expense_limit > 0 && $new_total > $expense_limit)
        {
            echo "<script>
                    alert('Expense Limit Exceeded! Cannot Add Expense');
                  </script>";
        }

        else
        {
            // INSERT EXPENSE USING PREPARED STATEMENT

            $stmt = mysqli_prepare($conn,
            "INSERT INTO expenses
            (title, amount, category, payment_method, expense_date, description, username)
            VALUES (?, ?, ?, ?, ?, ?, ?)");

            mysqli_stmt_bind_param(
                $stmt,
                "sdsssss",
                $title,
                $amount,
                $category,
                $payment,
                $expense_date,
                $description,
                $username
            );

            if(mysqli_stmt_execute($stmt))
            {
                echo "<script>
                        alert('Expense Added Successfully');
                        window.location.href='05_view_expense.php';
                      </script>";
            }

            else
            {
                echo "<script>alert('Error Adding Expense');</script>";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Add Expense - SpendWise</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{

    min-height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;

    background:
    linear-gradient(rgba(15,23,42,0.85),
    rgba(16,185,129,0.6)),

    url('https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?q=80&w=1470&auto=format&fit=crop');

    background-size:cover;
    background-position:center;

    padding:40px;
}

.container{

    width:450px;

    background:rgba(255,255,255,0.15);

    backdrop-filter:blur(12px);

    border-radius:20px;

    padding:35px;

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
}

input,
select,
textarea{

    width:100%;

    padding:14px;
    margin-bottom:18px;

    border:none;
    border-radius:12px;

    background:rgba(255,255,255,0.2);

    color:white;
    font-size:15px;
}

input::placeholder,
textarea::placeholder{
    color:#f3f4f6;
}

select option{
    color:black;
}

input:focus,
textarea:focus,
select:focus{

    outline:none;
    background:rgba(255,255,255,0.3);
}

textarea{
    height:100px;
    resize:none;
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

.nav-links{

    text-align:center;
    margin-top:20px;
}

.nav-links a{

    color:white;
    text-decoration:none;
    margin:0 10px;
    font-weight:bold;
}

.nav-links a:hover{
    text-decoration:underline;
}

</style>

</head>

<body>

<div class="container">

<h2>Add Expense</h2>

<p>Track your spending smartly</p>

<form method="POST">

<input type="text"
       name="title"
       placeholder="Expense Title"
       required>

<input type="number"
       name="amount"
       placeholder="Enter Amount"
       required>

<select name="category" required>

<option value="">Select Category</option>

<option>Food</option>
<option>Travel</option>
<option>Shopping</option>
<option>Bills</option>
<option>Education</option>
<option>Entertainment</option>

</select>

<select name="payment" required>

<option value="">Payment Method</option>

<option>Cash</option>
<option>UPI</option>
<option>Card</option>

</select>

<input type="date"
       name="expense_date"
       required>

<textarea name="description"
          placeholder="Expense Description"></textarea>

<button type="submit" name="submit">
    Add Expense
</button>

</form>

<div class="nav-links">

<a href="/expense_tracker/task5/05_view_expense.php">
View Expenses
</a>

<a href="/expense_tracker/task5/09_dashboard.php">
Dashboard
</a>

<a href="/expense_tracker/task5/08_logout.php">
Logout
</a>

</div>

</div>

</body>

</html>