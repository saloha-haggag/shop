<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($user_id))
{
   header('location:login.php');
};
if(isset($_GET['logout']))
{
   unset($user_id);
   session_destroy();
   header('location:login.php');
};
if(isset($_POST['add_to_cart']))
{
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];
   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
   if(mysqli_num_rows($select_cart) > 0)
   {
      $message[] = 'The product has already been added to your cart!';
   }else
   {
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, image, quantity) VALUES('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
      $message[] = 'The product is already added to the shopping cart!';
   }
};
if(isset($_POST['update_cart']))
{
   $update_quantity = $_POST['cart_quantity'];
   $update_id = $_POST['cart_id'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
   $message[] = 'Your cart quantity has been updated successfully!';
}
if(isset($_GET['remove']))
{
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
   header('location:index.php');
}
if(isset($_GET['delete_all']))
{
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>
   <link rel="stylesheet" href="css/style.css">
   <style>
      body {
         font-family: Arial, sans-serif;
         background-color: #eaf0f6;
         margin: 0;
         padding: 0;
      }
      .container {
         width: 85%;
         margin: 20px auto;
         background-color: #fff;
         padding: 25px;
         box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
         border-radius: 10px;
         background: linear-gradient(135deg, #f7b7d5, #9cc9f7);
      }
      .user-profile {
         background-color: #ffffff;
         padding: 15px;
         border-radius: 10px;
         margin-bottom: 25px;
         display: flex;
         justify-content: space-between;
         align-items: center;
         box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      }
      .user-profile p {
         font-size: 18px;
         font-weight: bold;
         color: #333;
      }
      .delete-btn {
         background-color: #ff6b81;
         color: #fff;
         padding: 12px 24px;
         border: none;
         cursor: pointer;
         border-radius: 8px;
         text-decoration: none;
      }
      .delete-btn:hover {
         background-color: #e84d67;
      }
      .products .box-container {
         display: flex;
         flex-wrap: wrap;
         gap: 20px;
         justify-content: space-around;
      }
      .box {
         background-color: #ffffff;
         padding: 20px;
         text-align: center;
         border-radius: 10px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
         transition: transform 0.3s ease;
         width: 230px;
         background: #f3f4f6;
         border: 1px solid #ddd;
      }
      .box:hover {
         transform: scale(1.05);
         box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
      }
      .box img {
         width: 100%;
         border-radius: 10px;
      }
      .box .name {
         font-size: 17px;
         font-weight: bold;
         margin: 10px 0;
         color: #333;
      }
      .box .price {
         font-size: 18px;
         color: #e74c3c;
         margin-bottom: 10px;
      }
      .box input[type="number"] {
         width: 60px;
         padding: 6px;
         border-radius: 6px;
         border: 1px solid #ccc;
         margin-bottom: 10px;
      }
      .btn {
         background-color: #3498db;
         color: #fff;
         padding: 12px 24px;
         border: none;
         cursor: pointer;
         border-radius: 8px;
         width: 100%;
         margin-top: 10px;
         font-size: 16px;
      }
      .btn:hover {
         background-color: #2980b9;
      }
      .shopping-cart table {
         width: 100%;
         margin-top: 30px;
         border-collapse: collapse;
      }
      .shopping-cart th, .shopping-cart td {
         padding: 18px;
         text-align: center;
         border: 1px solid #ddd;
      }
      .shopping-cart th {
         background-color: #3498db;
         color: #fff;
      }
      .table-bottom {
         background-color: #f7f7f7;
         font-weight: bold;
      }
      .delete-btn {
         background-color: #e74c3c;
         color: #fff;
         text-decoration: none;
         padding: 8px 18px;
         border-radius: 8px;
      }
      .disabled {
         background-color: #ddd;
         cursor: not-allowed;
      }
      .disabled:hover {
         background-color: #ddd;
      }
   </style>
</head>
<body>
<?php
if(isset($message))
{
   foreach($message as $message)
   {
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>

<div class="container">
   <div class="user-profile">
      <?php
         $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($select_user) > 0)
         {
            $fetch_user = mysqli_fetch_assoc($select_user);
         };
      ?>
      <p>Current User: <span><?php echo $fetch_user['name']; ?></span> </p>
      <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Are you sure you want to log out?');" class="delete-btn">Sign out</a>
   </div>

   <div class="products">
      <h1 class="heading">Latest Products</h1>
      <div class="box-container">
      <?php
         $result = mysqli_query($conn, "SELECT * FROM products");      
         while($row = mysqli_fetch_array($result)){
      ?>
         <form method="post" class="box" action="">
            <img src="admin/<?php echo $row['image']; ?>"  width="200">
            <div class="name"><?php echo $row['name']; ?></div>
            <div class="price"><?php echo $row['price']; ?>$</div>
            <input type="number" min="1" name="product_quantity" value="1">
            <input type="hidden" name="product_image" value="<?php echo $row['image']; ?>">
            <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
            <input type="submit" value="add to cart" name="add_to_cart" class="btn">
         </form>
      <?php
         };
      ?>
      </div>
   </div>

   <div class="shopping-cart">
      <h1 class="heading">Shopping Cart</h1>
      <table>
         <thead>
            <tr>
               <th>Image</th>
               <th>Product</th>
               <th>Price</th>
               <th>Quantity</th>
               <th>Total</th>
               <th>Remove</th>
            </tr>
         </thead>
         <tbody>
            <?php
               $grand_total = 0;
               $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
               if(mysqli_num_rows($select_cart) > 0)
               {
                  while($fetch_cart = mysqli_fetch_assoc($select_cart))
                  {
            ?>
            <tr>
               <td><img src="admin/<?php echo $fetch_cart['image']; ?>" height="50" width="50"></td>
               <td><?php echo $fetch_cart['name']; ?></td>
               <td><?php echo $fetch_cart['price']; ?>$</td>
               <td>
                  <form action="" method="POST">
                     <input type="number" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                     <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                     <input type="submit" name="update_cart" value="update" class="btn">
                  </form>
               </td>
               <td><?php echo $fetch_cart['price'] * $fetch_cart['quantity']; ?>$</td>
               <td><a href="index.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to remove this product?');">remove</a></td>
            </tr>
         <?php
               $grand_total += $fetch_cart['price'] * $fetch_cart['quantity'];
               };
            } else {
               echo '<tr><td colspan="6">Your cart is empty</td></tr>';
            };
         ?>
         </tbody>
         <tfoot>
            <tr class="table-bottom">
               <td colspan="4">Grand Total</td>
               <td><?php echo $grand_total; ?>$</td>
               <td><a href="index.php?delete_all=<?php echo $user_id; ?>" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>" onclick="return confirm('Are you sure you want to delete all?');">Delete All</a></td>
            </tr>
         </tfoot>
      </table>
   </div>
</div>
</body>
</html>

