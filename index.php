<?php
// Replace these variables with your own database credentials
$host = 'localhost:3307';
$username = 'root';
$password = '';
$dbname = 'shopping_cart';

// Create a database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// session_start(); // Start the session

// // Check if the user ID is stored in the session variable
// if(isset($_SESSION['user_id'])) {
//     // User ID is set in the session variable
//     $user_id = $_SESSION['user_id'];
//     // You can use $user_id for further processing or display
// } else {
//     // User ID is not set in the session variable
//     // You can handle this case as needed, e.g., redirect to login page
//     header('Location: login.php'); // Example: redirect to login page
//     exit; // Terminate script execution
// }


// Fetch all products from the products table
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>



<!DOCTYPE html>
<html>
<head>
  <title>Available Products</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    h1 {
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
    }
    tr:hover {
      background-color: #f9f9f9;
    }
    a {
      text-decoration: none;
      color: #007bff;
    }
  </style>
</head>
<body>
  <h1>Available Products</h1>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Action</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['price']; ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td><a href="sample.php?product_id=<?php echo $row['id']; ?>">Add to Cart</a></td>
      </tr>
    <?php } ?>
  </table>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
