<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
require "./vendor/autoload.php";
$conn = new MainProgram;

try 
{
    if ($conn->connect()) 
    {
        $item = $conn->getRecordById($_GET["id"], "id");
        /********************************************* Draw item card *******************************************/
        if ($item) {
        ?>
            <h2>Product Card</h2>

            <div class="card">
                <img src="images/<?php echo $item->Photo; ?>" style="width:100%">
                <h1>Type: <?php echo $item->product_name; ?></h1>
                <p class="price">Price: <?php echo $item->list_price; ?></p>
                <div class="item-details">
                    <h6>Code: <?php echo $item->PRODUCT_code; ?></h6> 
                    <h6>Item ID: <?php echo $item->id; ?></h6>
                    <h6>Rating: <?php echo $item->Rating; ?></h6> 
                </div>
            </div>
        <?php
        } else {
            echo "<p>No item found with the provided ID.</p>";
        }
        
        /****************************************************************************************************** */
    }
} 
catch (\Exception $e) 
{
    echo "An error occurred: " . $e->getMessage();
}
?>
</body>
</html>
