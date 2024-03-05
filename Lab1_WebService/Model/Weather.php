<?php
class Weather implements Weather_Interface {

    public function __construct() {
    }

    public function get_cities() 
    {
        $string = file_get_contents(__CITIES_FILE);
        $json_cities = json_decode($string, true);
        return $json_cities;
    }

    public function get_weather($cityId) 
    {
        $api_key = "6bcad0b1fd975687e208536afb55a800";
        $api_url = "http://api.openweathermap.org/data/2.5/weather?q=".$cityId."&APPID=".$api_key;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $api_url);
        $response = curl_exec($ch);

        curl_close($ch);
        if ($response === false) 
        {
            throw new Exception("Error: " . curl_error($ch));
        } else {
            $data = json_decode($response, true);
            return $data;
        }
    }

    public function get_current_time() {
        return $current_time = date("j F Y g:i a");
    }

}
