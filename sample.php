<?php

// Connect to the database
// Replace these variables with your own database credentials
$host = 'localhost:3307';
$username = 'root';
$password = '';
$dbname = 'shopping_cart';

// Create a database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch user data from user table
$user_query = "SELECT id, username FROM users";
$user_result = mysqli_query($conn, $user_query);

// Fetch product data from product table
$product_query = "SELECT id, name, price FROM products";
$product_result = mysqli_query($conn, $product_query);

// Fetch cart data from product table
$cart_query = "SELECT user_name,user_id, product_id, name, price, quantity, created_at FROM cart";
$cart_result = mysqli_query($conn, $cart_query);

session_start();
// Check if user is logged in, otherwise redirect to login.php
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
$selected_user_id = $_SESSION['username'];

// Insert data into cart table
if (mysqli_num_rows($user_result) > 0 && mysqli_num_rows($product_result) > 0 && mysqli_num_rows($cart_result) > 0 ) {
    // Fetch selected product id from GET request
    if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
        $selected_product_id = $_GET['product_id'];
        
        // Fetch product details by selected product id
        $selected_product_query = "SELECT id, name, price, quantity FROM products WHERE id = '$selected_product_id'";
        $selected_product_result = mysqli_query($conn, $selected_product_query);
        $selected_product_row = mysqli_fetch_assoc($selected_product_result);
        
        // $selected_user_id = $_SESSION['username'];
        
        // Fetch product details by selected user id
        $selected_user_query = "SELECT id, username FROM users WHERE username = '$selected_user_id'";
        $selected_user_result = mysqli_query($conn, $selected_user_query);
        $selected_user_row = mysqli_fetch_assoc($selected_user_result);


        $selected_cart_query = "SELECT user_name,user_id, product_id, name, price, quantity, created_at FROM cart WHERE user_name = '$selected_user_id' AND  product_id='$selected_product_id'";
        $selected_cart_result = mysqli_query($conn, $selected_cart_query);
        $selected_cart_row = mysqli_fetch_assoc($selected_cart_result);

        // Fetch cart details
        $existing_quantity = $selected_cart_row['quantity'];
        // $cost=0;
        $cart_cost_query = "SELECT user_name,price, quantity, cost FROM cart WHERE user_name = '$selected_user_id'";
        $cart_cost_result = mysqli_query($conn, $cart_cost_query);

        
        if(mysqli_num_rows($cart_cost_result) > 0 ){
            $cost=0;
            while ($row = mysqli_fetch_assoc($cart_cost_result)) {
                echo "<tr><td>".$row['cost']."</td></tr>";
                $cost = $cost+($row['quantity']*$row['price']);
            }
        }
        // echo "Cost of the cart is : $cost.<br>";

        // if($cart_cost_result['cost']!=0){
        //     $cart_cost_query = "UPDATE cart SET cost = '$cost' WHERE user_name = '$user_name'";
        // }
        // else{
        //     $cart_insert_query = "INSERT INTO cart (cost) VALUES (cost)";
        // }






        // Fetch user details
        $user_id = $selected_user_row['id'];
        $user_name = $selected_user_row['username'];
        
        // Insert selected product details into cart table
        $product_id = $selected_product_row['id'];
        $product_name = $selected_product_row['name'];
        $product_price = $selected_product_row['price'];
        // $quantity = $selected_cart_row['quantity']+1; // Generate random quantity
        // $quantity = rand(1, 10); // Generate random quantity

        
        
        // $cart_query = "INSERT INTO cart ( user_name,user_id, product_id, name, price, quantity, created_at) 
        //                 VALUES ('$user_name','$user_id',  '$product_id', '$product_name', '$product_price', '$quantity', NOW())";
        
        // if (mysqli_query($conn, $cart_query)) {
        //     echo "Data inserted into cart table successfully.<br>";
        // } else {
        //     echo "Error inserting data into cart table: " . mysqli_error($conn) . "<br>";
        // }
        // $existing_quantity = $selected_cart_row['quantity'];
            // Update quantity in cart if it exists, otherwise insert new row
        if($existing_quantity<$selected_product_row['quantity']){
            // if($cart_cost_result['cost']!=0){
            //     $cart_cost_query = "UPDATE cart SET cost = '$cost' WHERE user_name = '$user_name'";
            // }
            if ($existing_quantity>=1) {
                $quantity = $existing_quantity + 1;
                $cart_update_query = "UPDATE cart SET quantity = '$quantity' WHERE user_name = '$user_name' AND product_id = '$selected_product_id'";
                // $cart_cost_query = "UPDATE cart SET cost = '$cost' WHERE user_name = '$user_name'";
                if (mysqli_query($conn, $cart_update_query)) {
                    echo "Data updated in cart table successfully.<br>";
                } else {
                    echo "Error updating data in cart table: " . mysqli_error($conn) . "<br>";
                }
            } else {
                $quantity = 1;
                


                // $cost=0;
                // $cart_cost_query = "SELECT quantity FROM cart WHERE user_name = '$selected_user_id'";
                // $cart_cost_result = mysqli_query($conn, $cart_cost_query);
                // while ($row = mysqli_fetch_assoc($cart_cost_result)) {
                //     $cost = $cost+$row['quantity'];
                // }



                $cart_insert_query = "INSERT INTO cart (user_name, user_id, product_id, name, price, quantity, created_at, cost) 
                                VALUES ('$user_name', '$user_id', '$selected_product_id', '$product_name', '$product_price', '$quantity', NOW(), '$cost')";

                if (mysqli_query($conn, $cart_insert_query)) {
                    echo "Data inserted into cart table successfully.<br>";
                } else {
                    echo "Error inserting data into cart table: " . mysqli_error($conn) . "<br>";
                }
            }
        }
        else{
            echo "Product not available" . mysqli_error($conn) . "<br>";
        }
    }
}

// Display data from cart table
$display_query = "SELECT user_id, user_name, product_id, name, price, quantity, cost FROM cart WHERE user_name = '$selected_user_id'";
$display_result = mysqli_query($conn, $display_query);

if (mysqli_num_rows($display_result) > 0) {
    echo "<table border='1'>
            <tr>
                <th>User ID</th>
                <th>User Name</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Cost</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($display_result)) {
        echo "<tr>
                <td>".$row['user_id']."</td>
                <td>".$row['user_name']."</td>
                <td>".$row['product_id']."</td>
                <td>".$row['name']."</td>
                <td>".$row['price']."</td>
                <td>".$row['quantity']."</td>
                <td>".$row['cost']."</td>
            </tr>";
    }
    echo "</table>";



    echo "<table border='1'>
        
            <tr>
                <td><a href='welcome.php'>BUY</a></td>     
            </tr>";
    echo "</table>";




    echo '<a href="logout.php">Logout</a>';
}
