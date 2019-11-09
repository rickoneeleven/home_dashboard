<?php
$apiKey = '3dd9e0ce2eb1452d8f7164307173001';
$freeHttpsUrl = 'https://api.worldweatheronline.com/free/v2/weather.ashx';

$queryUrl = "$freeHttpsUrl?q=new+york&num_of_days=5&key=$apiKey&tp=24&format=json";

/**
 * CURLOPT_RETURNTRANSFER - Return the response as a string instead of outputting it to the screen
 * CURLOPT_CONNECTTIMEOUT - Number of seconds to spend attempting to connect
 * CURLOPT_TIMEOUT - Number of seconds to allow cURL to execute
 * CURLOPT_USERAGENT - Useragent string to use for request
 * CURLOPT_URL - URL to send request to
 * CURLOPT_POST - Send request as POST
 * CURLOPT_POSTFIELDS - Array of data to POST in request
 */
$options = array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array('Content-type: application/json'),
    CURLOPT_URL => $queryUrl
);

// Setting curl options
$curl = curl_init();
curl_setopt_array( $curl, $options );

if (!$result = curl_exec($curl)){
    die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
}
curl_close($curl);

$response = json_decode($result, true);

echo '<table style="font-size: 10px; font-family: Arial, Helvetica, sans-serif">';

for ($i=0; $i < 3; $i++) {
    echo "<tr>";

    foreach ($response['data']['weather'] as $weather) {
        echo "<td>";

        switch ($i) {
            case 0:
                echo date('D', strtotime($weather['date']));
                break;
            case 1:
                echo $weather['hourly'][0]['weatherDesc'][0]['value'];
                break;
            case 2:
                $imgSrc = $weather['hourly'][0]['weatherIconUrl'][0]['value'];
                echo "<img src='$imgSrc' />";
                break;
        }
        echo "</td>";
    }
    echo "</tr>";
}

echo '</table>';
