<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
   require "./vendor/autoload.php";
   $conn = new MainProgram;
   $items_num = 5;
   $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

   try 
   {
      if ($conn->connect()) 
      {
         
         $method = $_SERVER['REQUEST_METHOD'];
         $conn->handleRequest($method);
      }
   } 
   catch (\Exception $e) 
   {
      $conn->sendResponse(500, ['error' => 'Internal server error']);
      echo "An error occurred: " . $e->getMessage();
   }
?>
</body>
</html>
