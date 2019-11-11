<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Getdark extends CI_Controller {

    public function insertDaily() {
        if(date('D') === "Sat" || date('D') === "Sun") {
            //billinge
            $requesturl='https://api.darksky.net/forecast/'.darksky_api.'/53.493894,-2.7152488?units=si';
            $site = "Billinge";
        } else {
            //work
            $requesturl='https://api.darksky.net/forecast/'.darksky_api.'/53.537135,-2.791862?units=si';
            $site = "Skelmersdale";
        }

        $ch=curl_init($requesturl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $cexecute=curl_exec($ch);
        curl_close($ch);
        $result = json_decode($cexecute,true);
        echo "<br>current weather: ".$result['currently']['temperature']."</br>";
        echo "minutely: ".$result['minutely']['summary']."</br>";
        echo "hourly: ".$result['hourly']['summary']."</br>";
        echo "daily: ".$result['daily']['summary']."</br>";
        echo "<br>";

        $summary_insert = array(
            'current_temp'                      => round($result['currently']['temperature'],0),
            'summary_min'                       => $result['minutely']['summary'],
            'summary_hour'                      => $result['hourly']['summary'],
            'summary_day'                       => $result['daily']['summary'],
            'precipIntensity'                   => round($result['currently']['precipIntensity'],2),
            'precipProbability'                 => $result['currently']['precipProbability'],
            'windGust'                          => $result['currently']['windGust'],
            'apparentTemp'                      => round($result['currently']['apparentTemperature'],0),
            'precipIntensity_coming'            => round($result['hourly']['data'][2]['precipIntensity'],2),
            'precipProbability_coming'          => $result['hourly']['data'][2]['precipProbability'],
            'windGust_coming'                   => $result['hourly']['data'][2]['windGust'],
            'temp_coming'                       => round($result['hourly']['data'][2]['temperature'],0),
            'apparentTemp_coming'               => round($result['hourly']['data'][2]['apparentTemperature'],0),
            'timestamp'                         => $result['currently']['time'],
            'updated'                           => date('Y-m-d H:i:s'),
            'location'                          => $site,
            'icon'                              => $result['minutely']['icon'],
            'icon_coming'                       => $result['hourly']['data'][4]['icon'],
        );
        $this->db->insert('darksky_summary', $summary_insert);

        foreach($result['daily']['data'] as $day) {
            $dateTime = new \DateTime(null, new DateTimeZone('Europe/London'));
            $dateTime->setTimestamp($day['time']);
            echo $dateTime->format('Y-m-d H:i:s')."</br>";
            echo "low: ".$day['temperatureLow']."  | high: ".$day['temperatureHigh']."</br>";

            $daily_insert = array(
                'timestamp'         => $day['time'],
                'updated'           => date('Y-m-d H:i:s'),
                'temperatureMax'    => $day['temperatureMax'],
                'temperatureMin'    => $day['temperatureMin'],
            );
            $this->db->insert('darksky_daily', $daily_insert);
        }
        vdebug($result);
    }
}
