<?php
require("vendor/autoload.php");
ini_set('memory_limit', '-1');
$weather = new Weather();
$egy_cities = $weather -> get_cities();
$data = [];

if (isset($_POST["submit"])) 
{
    $cityName = $_POST["city"];
    $data = $weather->get_weather($cityName);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Weather Forcase</h1>
    <form method="post">
        <select name="city" class="custom-select">
            <?php
               for ($i = 0; $i < count($egy_cities); $i++)
               {
                    if($egy_cities[$i]['country'] === "EG")
                    echo "<option value='" . $egy_cities[$i]['name'] . "'>" .$egy_cities[$i]['country']." >>> ". $egy_cities[$i]['name'] . "</option>";
               }
            ?>
        </select>
        <input type="submit" name="submit" value="Submit">
    </form>

    <?php if (!empty($data)) { ?>
        <table>
            <tr>
                <th>Parameter</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>City Name</td>
                <td><?php echo $data['name']; ?></td>
            </tr>
            <tr>
                <td>Date && Time</td>
                <td><?php echo $current_time = $weather->get_current_time(); ?></td>
            </tr>
            <tr>
                <td>weather</td>
                <td><?php echo $data['weather'][0]['description'];?><img src="http://openweathermap.org/img/w/<?php echo $data['weather'][0]['icon']; ?>.png" alt="Weather Icon"></td>
            </tr>
            <tr>
                <td>Temperature</td>
                <td><?php echo $data['main']['temp_min'];?> &deg;C <?php echo $data['main']['temp_max'];?> &deg;C</td>
                
            </tr>
            <tr>
                <td>Humidity</td>
                <td><?php echo $data['main']['humidity']; ?> %</td>
            </tr>
            <tr>
                <td>Wind</td>
                <td><?php echo $data['wind']['speed']; ?> km/h</td>
            </tr>
        </table>
    <?php } ?>


</body>
</html>
