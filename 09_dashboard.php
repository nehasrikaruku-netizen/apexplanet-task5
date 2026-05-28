<?php

include("01_db.php");

session_start();

if(!isset($_SESSION['username']))
{
    header("Location: /expense_tracker/task5/03_login.php");
    exit();
}

$username = $_SESSION['username'];

/* SET EXPENSE LIMIT */

if(isset($_POST['set_limit']))
{
    $limit = trim($_POST['expense_limit']);

    if($limit < 0)
    {
        echo "<script>alert('Invalid Limit');</script>";
    }

    else
    {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE users
             SET expense_limit=?
             WHERE username=?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ds",
            $limit,
            $username
        );

        mysqli_stmt_execute($stmt);

        echo "<script>alert('Expense Limit Updated Successfully');</script>";
    }
}

/* FETCH USER LIMIT */

$stmt = mysqli_prepare(
    $conn,
    "SELECT expense_limit
     FROM users
     WHERE username=?"
);

mysqli_stmt_bind_param($stmt, "s", $username);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$userRow = mysqli_fetch_assoc($result);

$expenseLimit = $userRow['expense_limit'];

/* TOTAL EXPENSE */

$stmt = mysqli_prepare(
    $conn,
    "SELECT SUM(amount) AS total
     FROM expenses
     WHERE username=?"
);

mysqli_stmt_bind_param($stmt, "s", $username);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$totalRow = mysqli_fetch_assoc($result);

$totalExpense = $totalRow['total'];

if($totalExpense == NULL)
{
    $totalExpense = 0;
}

/* TOTAL COUNT */

$stmt = mysqli_prepare(
    $conn,
    "SELECT COUNT(*) AS totalExpenses
     FROM expenses
     WHERE username=?"
);

mysqli_stmt_bind_param($stmt, "s", $username);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$countRow = mysqli_fetch_assoc($result);

$totalCount = $countRow['totalExpenses'];

/* CATEGORY CHART DATA */

$stmt = mysqli_prepare(
    $conn,
    "SELECT category,
     SUM(amount) AS total
     FROM expenses
     WHERE username=?
     GROUP BY category"
);

mysqli_stmt_bind_param($stmt, "s", $username);

mysqli_stmt_execute($stmt);

$chartResult = mysqli_stmt_get_result($stmt);

$categories = [];
$amounts = [];

while($chartRow = mysqli_fetch_assoc($chartResult))
{
    $categories[] = $chartRow['category'];
    $amounts[] = $chartRow['total'];
}

/* SEARCH */

$search = "";

if(isset($_GET['search']))
{
    $search = trim($_GET['search']);

    $searchTerm = "%$search%";

    $stmt = mysqli_prepare(
        $conn,
        "SELECT *
         FROM expenses
         WHERE username=?
         AND (
         title LIKE ?
         OR category LIKE ?
         OR payment_method LIKE ?
         )
         ORDER BY id DESC"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $username,
        $searchTerm,
        $searchTerm,
        $searchTerm
    );

    mysqli_stmt_execute($stmt);

    $searchResult = mysqli_stmt_get_result($stmt);
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Dashboard - SpendWise</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
    linear-gradient(rgba(15,23,42,0.88),
    rgba(16,185,129,0.55)),

    url('https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?q=80&w=1470&auto=format&fit=crop');

    background-size:cover;
    background-position:center;

    padding:40px;
}

.container{

    width:95%;
    max-width:1200px;

    margin:auto;
}

.header{

    text-align:center;

    margin-bottom:40px;
}

.header h1{

    color:white;

    font-size:42px;

    margin-bottom:10px;
}

.header p{

    color:#d1fae5;

    font-size:18px;
}

.limit-box{

    background:rgba(255,255,255,0.12);

    backdrop-filter:blur(10px);

    border-radius:20px;

    padding:25px;

    margin-bottom:30px;

    text-align:center;

    border:1px solid rgba(255,255,255,0.2);
}

.limit-box h2{

    color:white;

    margin-bottom:15px;
}

.limit-box input{

    padding:12px;

    width:250px;

    border:none;

    border-radius:10px;

    margin-right:10px;
}

.limit-box button{

    padding:12px 18px;

    border:none;

    border-radius:10px;

    background:#10b981;

    color:white;

    font-weight:bold;

    cursor:pointer;
}

.limit-box button:hover{

    background:#059669;
}

.current-limit{

    color:#d1fae5;

    margin-top:15px;

    font-size:18px;
}

.cards{

    display:grid;

    grid-template-columns:repeat(auto-fit, minmax(250px,1fr));

    gap:25px;

    margin-bottom:40px;
}

.card{

    background:rgba(255,255,255,0.12);

    backdrop-filter:blur(10px);

    border-radius:20px;

    padding:30px;

    text-align:center;

    box-shadow:0 8px 32px rgba(0,0,0,0.2);

    border:1px solid rgba(255,255,255,0.2);

    transition:0.3s;
}

.card:hover{

    transform:translateY(-5px);
}

.card h2{

    color:white;

    margin-bottom:15px;

    font-size:24px;
}

.card p{

    color:#d1fae5;

    font-size:28px;

    font-weight:bold;
}

.chart-container{

    background:rgba(255,255,255,0.12);

    backdrop-filter:blur(10px);

    border-radius:20px;

    padding:30px;

    margin-bottom:40px;

    border:1px solid rgba(255,255,255,0.2);

    text-align:center;
}

.chart-container h2{

    color:white;

    margin-bottom:20px;
}

.chart-box{

    width:400px;

    height:400px;

    margin:auto;
}

.links{

    display:grid;

    grid-template-columns:repeat(auto-fit, minmax(220px,1fr));

    gap:20px;

    margin-top:30px;
}

.links a{

    background:#10b981;

    color:white;

    text-decoration:none;

    padding:18px;

    border-radius:15px;

    text-align:center;

    font-size:18px;

    font-weight:bold;

    transition:0.3s;

    box-shadow:0 4px 15px rgba(0,0,0,0.2);
}

.links a:hover{

    background:#059669;

    transform:translateY(-3px);
}

.search-box{

    text-align:center;

    margin-bottom:30px;
}

.search-box input{

    padding:12px;

    width:250px;

    border:none;

    border-radius:8px;
}

.search-box button{

    padding:12px 15px;

    border:none;

    border-radius:8px;

    background:#10b981;

    color:white;

    cursor:pointer;
}

.search-results{

    background:rgba(255,255,255,0.12);

    padding:25px;

    border-radius:20px;

    margin-bottom:30px;

    backdrop-filter:blur(10px);
}

.search-results h2{

    color:white;

    margin-bottom:20px;

    text-align:center;
}

table{

    width:100%;

    text-align:center;
}

th{

    color:white;

    padding:10px;
}

td{

    color:#d1fae5;

    padding:10px;
}

.footer{

    text-align:center;

    margin-top:50px;

    color:white;

    font-size:15px;
}

</style>

</head>

<body>

<div class="container">

<div class="header">

<h1>SpendWise Dashboard - Task 5</h1>

<p>Welcome, <?php echo $username; ?> 👋</p>

<p id="datetime"
style="
color:white;
margin-top:10px;
font-size:18px;
font-weight:bold;
"></p>

</div>

<div class="limit-box">

<h2>Set Expense Limit</h2>

<form method="POST">

<input type="number"
name="expense_limit"
placeholder="Enter Expense Limit"
required>

<button type="submit" name="set_limit">
Set Limit
</button>

</form>

<div class="current-limit">

Current Limit:
₹ <?php echo $expenseLimit ? $expenseLimit : 0; ?>

</div>

</div>

<?php

if($expenseLimit > 0 && $totalExpense > $expenseLimit)
{
    echo "

    <div style='
    background:#ef4444;
    color:white;
    padding:20px;
    border-radius:15px;
    text-align:center;
    margin-bottom:30px;
    font-size:22px;
    font-weight:bold;
    '>

    ⚠ Warning! Expense Limit Exceeded

    </div>

    ";
}

?>

<div class="cards">

<div class="card">

<h2>Total Expenses</h2>

<p><?php echo $totalCount; ?></p>

</div>

<div class="card">

<h2>Total Spending</h2>

<p>₹ <?php echo $totalExpense; ?></p>

</div>

</div>

<div class="chart-container">

<h2>Expense Category Analytics</h2>

<div class="chart-box">

<canvas id="expenseChart"></canvas>

</div>

</div>

<div class="search-box">

<form method="GET">

<input type="text"
name="search"
placeholder="Search expense"
value="<?php echo $search; ?>">

<button type="submit">
Search
</button>

</form>

</div>

<?php

if(isset($_GET['search']))
{

?>

<div class="search-results">

<h2>Search Results</h2>

<table>

<tr>

<th>ID</th>
<th>Title</th>
<th>Amount</th>
<th>Category</th>
<th>Payment</th>

</tr>

<?php

while($row = mysqli_fetch_assoc($searchResult))
{

?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['title']; ?></td>

<td>₹ <?php echo $row['amount']; ?></td>

<td><?php echo $row['category']; ?></td>

<td><?php echo $row['payment_method']; ?></td>

</tr>

<?php

}

?>

</table>

</div>

<?php

}

?>

<div class="links">

<a href="/expense_tracker/task5/04_add_expense.php">
Add Expense
</a>

<a href="/expense_tracker/task5/05_view_expense.php">
View Expenses
</a>

<a href="/expense_tracker/task5/08_logout.php">
Logout
</a>

</div>

<div class="footer">

<p>Smart Expense Tracker • SpendWise</p>

</div>

</div>

<script>

const ctx = document.getElementById('expenseChart');

new Chart(ctx, {

    type: 'pie',

    data: {

        labels: <?php echo json_encode($categories); ?>,

        datasets: [{

            label: 'Expenses',

            data: <?php echo json_encode($amounts); ?>,

            borderWidth: 1

        }]
    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        plugins: {

            legend: {

                labels: {

                    color: "white",

                    font: {

                        size: 16,

                        weight: "bold"
                    }
                }
            }
        }
    }
});

function updateDateTime()
{
    const now = new Date();

    document.getElementById("datetime").innerHTML =
    now.toLocaleString();
}

setInterval(updateDateTime, 1000);

updateDateTime();

</script>

</body>

</html>