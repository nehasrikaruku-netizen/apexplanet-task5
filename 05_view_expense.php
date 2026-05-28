<?php

include("01_db.php");

session_start();

if(!isset($_SESSION['username']))
{
    header("Location: /expense_tracker/task5/03_login.php");
    exit();
}

$username = $_SESSION['username'];

// PAGINATION

$limit = 5;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1)
{
    $page = 1;
}

$offset = ($page - 1) * $limit;

// SEARCH

$search = "";

if(isset($_GET['search']))
{
    $search = trim($_GET['search']);
}

// PREPARED STATEMENT
/* FETCH DATA */

if(!empty($search))
{
    $searchTerm = "%$search%";

    $query = "SELECT * FROM expenses
              WHERE username='$username'
              AND (
              title LIKE '$searchTerm'
              OR category LIKE '$searchTerm'
              OR payment_method LIKE '$searchTerm'
              )
              ORDER BY id DESC
              LIMIT $offset, $limit";
}

else
{
    $query = "SELECT * FROM expenses
              WHERE username='$username'
              ORDER BY id DESC
              LIMIT $offset, $limit";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>

<title>View Expenses - SpendWise</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{

    min-height:100vh;

    background:
    linear-gradient(rgba(15,23,42,0.9),
    rgba(16,185,129,0.6)),

    url('https://images.unsplash.com/photo-1554224154-26032ffc0d07?q=80&w=1470&auto=format&fit=crop');

    background-size:cover;
    background-position:center;

    padding:40px;
}

.container{

    width:95%;
    max-width:1200px;

    margin:auto;

    background:rgba(255,255,255,0.12);

    backdrop-filter:blur(12px);

    border-radius:20px;

    padding:30px;

    box-shadow:0 8px 32px rgba(0,0,0,0.2);

    border:1px solid rgba(255,255,255,0.2);
}

h2{

    text-align:center;
    color:white;

    margin-bottom:25px;

    font-size:32px;
}

.top-links{

    text-align:center;

    margin-bottom:25px;
}

.top-links a{

    display:inline-block;

    margin:10px;

    padding:12px 22px;

    background:#10b981;

    color:white;

    text-decoration:none;

    border-radius:10px;

    font-weight:bold;

    transition:0.3s;
}

.top-links a:hover{

    background:#059669;

    transform:translateY(-2px);
}

.search-box{

    text-align:center;

    margin-bottom:25px;
}

.search-box input{

    width:300px;

    padding:12px;

    border:none;

    border-radius:10px;

    outline:none;
}

.search-box button{

    padding:12px 18px;

    border:none;

    border-radius:10px;

    background:#3b82f6;

    color:white;

    cursor:pointer;

    font-weight:bold;
}

table{

    width:100%;

    border-collapse:collapse;

    overflow:hidden;

    border-radius:15px;
}

th{

    background:#10b981;

    color:white;

    padding:15px;
}

td{

    background:rgba(255,255,255,0.15);

    color:white;

    padding:14px;

    text-align:center;

    border-bottom:1px solid rgba(255,255,255,0.1);
}

tr:hover td{

    background:rgba(255,255,255,0.22);
}

.action-btn{

    display:inline-block;

    padding:10px 18px;

    color:white;

    text-decoration:none;

    border-radius:8px;

    font-weight:bold;

    margin:4px;
}

.edit-btn{

    background:#3b82f6;
}

.delete-btn{

    background:#ef4444;
}

.no-data{

    text-align:center;

    color:white;

    margin-top:20px;

    font-size:18px;
}

.pagination{

    text-align:center;

    margin-top:25px;
}

.pagination a{

    display:inline-block;

    padding:10px 15px;

    margin:5px;

    background:#10b981;

    color:white;

    text-decoration:none;

    border-radius:8px;
}

</style>

</head>

<body>

<div class="container">

<h2>All Expenses</h2>

<div class="top-links">

<a href="/expense_tracker/task5/04_add_expense.php">
Add Expense
</a>

<a href="/expense_tracker/task5/09_dashboard.php">
Dashboard
</a>

<a href="/expense_tracker/task5/08_logout.php">
Logout
</a>

</div>

<div class="search-box">

<form method="GET">

<input type="text"
name="search"
placeholder="Search Expenses"
value="<?php echo $search; ?>">

<button type="submit">
Search
</button>

</form>

</div>

<?php

if(mysqli_num_rows($result) > 0)
{

?>

<table>

<tr>

<th>ID</th>
<th>Title</th>
<th>Amount</th>
<th>Category</th>
<th>Payment</th>
<th>Date</th>
<th>Description</th>
<th>Actions</th>

</tr>

<?php

while($row = mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['title']; ?></td>

<td>₹<?php echo $row['amount']; ?></td>

<td><?php echo $row['category']; ?></td>

<td><?php echo $row['payment_method']; ?></td>

<td><?php echo $row['expense_date']; ?></td>

<td><?php echo $row['description']; ?></td>

<td>

<a class="action-btn edit-btn"
href="/expense_tracker/task5/06_edit_expense.php?id=<?php echo $row['id']; ?>">
Edit
</a>

<a class="action-btn delete-btn"
href="/expense_tracker/task5/07_delete_expense.php?id=<?php echo $row['id']; ?>">
Delete
</a>

</td>

</tr>

<?php

}

?>

</table>

<div class="pagination">

<a href="?page=1">1</a>
<a href="?page=2">2</a>
<a href="?page=3">3</a>

</div>

<?php

}

else
{
    echo "<div class='no-data'>No Expenses Found</div>";
}

?>

</div>

</body>

</html>