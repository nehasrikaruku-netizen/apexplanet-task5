# 💰 SpendWise - Smart Expense Tracker (Task 5)

## 📌 Project Overview

SpendWise is a Smart Expense Tracker web application developed using **PHP** and **MySQL** as part of the **ApexPlanet Web Development Internship**.

The project helps users:

* Manage daily expenses
* Track spending
* Analyze expense categories
* Set personal expense limits
* Search and manage records efficiently

Task 5 focuses on advanced features such as:

* Expense Analytics
* Expense Limit Alerts
* User-wise Expense Management
* Dashboard Improvements
* Security Enhancements

---

# 🚀 Features Implemented

## ✅ User Authentication

* User Registration
* Secure Login System
* Session Management
* Logout Functionality

### 🔗 Links

* Register Page

```text id="yjlwm3"
http://localhost/expense_tracker/task5/02_register.php
```

* Login Page

```text id="xjlwm7"
http://localhost/expense_tracker/task5/03_login.php
```

* Logout Page

```text id="0jlwmn"
http://localhost/expense_tracker/task5/08_logout.php
```

---

## ✅ Expense Management (CRUD)

### Features

* Add Expense
* View Expenses
* Edit Expense
* Delete Expense

### 🔗 Links

* Add Expense

```text id="hjlwm6"
http://localhost/expense_tracker/task5/04_add_expense.php
```

* View Expenses

```text id="jlwm0m"
http://localhost/expense_tracker/task5/05_view_expense.php
```

* Edit Expense Example

```text id="wjlwm2"
http://localhost/expense_tracker/task5/06_edit_expense.php?id=1
```

* Delete Expense Example

```text id="zjlwm5"
http://localhost/expense_tracker/task5/07_delete_expense.php?id=1
```

---

# 📊 Dashboard & Analytics

## Features

* Total Expense Count
* Total Spending Amount
* Pie Chart Analytics
* Expense Limit Warning
* Real-Time Dashboard

### 🔗 Dashboard Link

```text id="jlwm4k"
http://localhost/expense_tracker/task5/09_dashboard.php
```

---

# 🔎 Search Functionality

Users can search expenses using:

* Expense Title
* Category
* Payment Method

Implemented inside:

```text id="mjlwm1"
05_view_expense.php
```

and

```text id="tjlwm9"
09_dashboard.php
```

---

# 📄 Pagination

Implemented pagination for better user experience:

* 5 expenses per page
* Page navigation support

Implemented in:

```text id="vjlwm7"
05_view_expense.php
```

---

# 🚨 Expense Limit Feature

Users can:

* Set expense limit
* Track total spending
* Receive warning alerts when spending exceeds limit

Implemented in:

```text id="2jlwmq"
09_dashboard.php
```

---

# 🔐 Validation & Security

Implemented:

* Required Field Validation
* Positive Amount Validation
* Session Protection
* SQL Injection Prevention
* User-wise Expense Filtering

---

# 🛠️ Technologies Used

* PHP
* MySQL
* HTML5
* CSS3
* JavaScript
* Chart.js
* XAMPP
* phpMyAdmin
* VS Code
* Git & GitHub

---

# 📂 Project Structure

```bash id="gjlwm6"
task5/
│
├── 01_db.php
├── 02_register.php
├── 03_login.php
├── 04_add_expense.php
├── 05_view_expense.php
├── 06_edit_expense.php
├── 07_delete_expense.php
├── 08_logout.php
├── 09_dashboard.php
└── README.md
```

---

# 🗄️ Database Structure

## users Table

| Column        | Description     |
| ------------- | --------------- |
| id            | User ID         |
| username      | Username        |
| password      | Hashed Password |
| role          | User Role       |
| expense_limit | Expense Limit   |

---

## expenses Table

| Column         | Description         |
| -------------- | ------------------- |
| id             | Expense ID          |
| title          | Expense Title       |
| amount         | Expense Amount      |
| category       | Expense Category    |
| payment_method | Payment Method      |
| expense_date   | Expense Date        |
| description    | Expense Description |
| username       | Expense Owner       |

---

# 📈 Improvements from Task 4 to Task 5

## ✅ Added Features

* Expense Analytics Dashboard
* Pie Chart Visualization
* Expense Limit Alerts
* User-wise Expense Tracking
* Search Optimization
* Better Session Handling
* Responsive Dashboard UI
* Improved Navigation
* Enhanced Security

---

# 🎯 Learning Outcomes

Through this project, I learned:

* PHP CRUD Operations
* MySQL Database Integration
* Session Management
* Prepared Statements
* Dashboard Analytics
* Chart.js Integration
* User Authentication
* Git & GitHub Workflow
* Responsive UI Design

---

# 📸 Output Screens

* Registration Page
* Login Page
* Add Expense Page
* View Expenses Page
* Dashboard Analytics Page
* Expense Limit Warning Alert

---

# 🔗 Local Project Access

Main Folder:

```text id="qjlwm2"
C:\xampp\htdocs\expense_tracker\task5
```

Main Browser Access:

```text id="njlwm5"
http://localhost/expense_tracker/task5/
```

---

# 👩‍💻 Developed By

Neha Sri Karuku
CSE - Artificial Intelligence
Vignan’s Institute of Information Technology

---

# 📌 Internship

ApexPlanet Web Development Internship - Task 5
