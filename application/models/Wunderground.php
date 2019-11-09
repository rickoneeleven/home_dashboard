<?php
/**
 * php class for getting wunderground api data
 * 
 *
 * @package    wunderground weather
 * @author     David Cool <david@davidcool.com>
 * @url        http://davidcool.com
 * @version    0.5
 */

class Wunderground {

    /**
     * Api key from wunderground.com
     * 
     * @var string
     */
    private $apiKey = '2bacbed8e7e38929';
    private $get = 'conditions';

    /**
     * Your lang
     *
     * @var string
     */
    private $lang = 'EN';

    /**
     * Initialize country or state
     *
     * @var string
     */
    private $location = 'UK';

    /**
     * Initialize city
     *
     * @var string
     */
    private $city = 'Billinge';
    
    
    //private $json_geo;
    private $json;

    public function __construct() {
        $this->getAllWeather();
    }
    
    /**
     * Get weather from api ,decode and save in $json
     */
    private function getAllWeather() {
        $apiUrl = 'http://api.wunderground.com/api/' . $this->apiKey . '/' . $this->get . '/lang:' . $this->lang . '/q/' . $this->location . '/' . $this->city . '.json';
        $this->json = json_decode(file_get_contents($apiUrl));
        //vdebug($this->json);
    }

    /**
     * Get weather description
     * 
     * @param bool $c
     * @return string
     */
    public function getWeatherDesc() {
        return $this->json->current_observation->weather;
    }

    /**
     * Get wind information
     * 
     * @return string
     */
    public function getWindDesc() {
        return $this->json->current_observation->wind_string;
    }    

    /**
     * Get humidity information
     * 
     * @return string
     */
    public function getHumidity() {
        return $this->json->current_observation->relative_humidity;
    } 

    /**
     * Get Precipitation information
     * 
     * @param string $format
     * @return string
     */
    public function getPrecip($format = "CF") {
        if ($format == "C") {
            $out = $this->json->current_observation->precip_today_metric." mm";
        } elseif ($format == "F") {
            $out = $this->json->current_observation->precip_today_in." in";
        } else {
            $out = $this->json->current_observation->precip_today_string;
        }

        return $out;
        return $this->json->current_observation->precip_today_string;
    }

    /**
     * Get weather icon with html tag if $imgTag is true
     * 
     * @param bool $imgTag add tag if true
     * @return string
     */
    public function getWeatherIcon($imgTag = false) {
        $img = $this->json->current_observation->icon_url;
        if ($imgTag) {
            $img = "<img src='" . $img . "'/>";
        }
        return $img;
    }

    /**
     * Get temperature celcius if $c is true
     * 
     * @param string $format
     * @return string
     */
    public function getTemperature($format = "CF") {
        if ($format == "C") {
            $out = round($this->json->current_observation->temp_c);
        } elseif ($format == "F") {
            $out = round($this->json->current_observation->temp_f);
        } else {
            $out = $this->json->current_observation->temperature_string;
        }

        return $out;
    }

    /**
     * Get feels like
     * 
     * @param string $format
     * @return string
     */
    public function getFeelsLike($format = "CF") {
        if ($format == "C") {
            $out = round($this->json->current_observation->feelslike_c)."&degC";
        } elseif ($format == "F") {
            $out = round($this->json->current_observation->feelslike_f)."&degF";
        } else {
            $out = $this->json->current_observation->feelslike_string;
        }

        return $out;
    }    

    /**
     * Get astronomy/moon information
     * 
     * @param string $format
     * @return string
     */
    public function getAstronomy($format = "") {
        $astronomy = json_decode(file_get_contents('http://api.wunderground.com/api/' . $this->apiKey . '/astronomy/q/' . $this->location . '/' . $this->city . '.json'));
        switch($format){
            case "sunrise":
                $out = $astronomy->moon_phase->sunrise->hour.":".$astronomy->moon_phase->sunrise->minute." AM";
                break;
            case "sunset":
                $out = ($astronomy->moon_phase->sunset->hour - 12).":".$astronomy->moon_phase->sunset->minute." PM";
                break;          
        }
        
        return $out;
    }

    public function getHourly() {
        $hourly = json_decode(file_get_contents('http://api.wunderground.com/api/' . $this->apiKey . '/hourly/q/' . $this->location . '/' . $this->city . '.json'));
        return $hourly;
    }

    /**
     * Get hourly 10 day forecast information
     * 
     * @param 
     * @return string
     */
    public function getHourly10day() {
        $Hourly10day = json_decode(file_get_contents('http://api.wunderground.com/api/' . $this->apiKey . '/hourly10day/q/' . $this->location . '/' . $this->city . '.json'));
        $currentDay = $Hourly10day->hourly_forecast[0]->FCTTIME->yday;
        $currentYear = $Hourly10day->hourly_forecast[0]->FCTTIME->year;
        $daysInYear = date("z", mktime(0,0,0,12,31,$currentYear)) + 1;
        
        foreach($Hourly10day as $a) {
            $hours = $a;
        }

        $count = 1;
        $dayCount = 0;
        $hourSkip = 0;
        $x = 0;
        $searchDay = $currentDay;

        while($x < 10) {
            if($x == 0) {
                while($dayCount < 10) {
                        $out[$x][$dayCount][yday].= $hours[$count+$dayCount]->FCTTIME->yday;
                        $out[$x][$dayCount][hour].= $hours[$count+$dayCount]->FCTTIME->hour;
                        $out[$x][$dayCount][ampm].= $hours[$count+$dayCount]->FCTTIME->ampm;
                        $out[$x][$dayCount][url].= $hours[$count+$dayCount]->icon_url;
                        $out[$x][$dayCount][temp].= $hours[$count+$dayCount]->temp->english;
                        $dayCount++;
                } // end while
                $dayCount = 0;
                $searchDay++;
            } else {
                while($count < count($hours)) {
                    if ($hours[$count]->FCTTIME->yday == $searchDay) {
                        while($dayCount < 10) {
                            $out[$x][$dayCount][yday].= $hours[$count+$hourSkip]->FCTTIME->yday;
                            $out[$x][$dayCount][hour].= $hours[$count+$hourSkip]->FCTTIME->hour;
                            $out[$x][$dayCount][ampm].= $hours[$count+$hourSkip]->FCTTIME->ampm;
                            $out[$x][$dayCount][url].= $hours[$count+$hourSkip]->icon_url;
                            $out[$x][$dayCount][temp].= $hours[$count+$hourSkip]->temp->english;
                            $dayCount++;
                            $hourSkip = $hourSkip + 3;
                        } // end while
                    $dayCount = 0;
                    $hourSkip = 0;
                    if($searchDay == $daysInYear) {
                        $searchDay = 1;
                    } else {
                        $searchDay++;
                    }
                    break;
                    } // end if
                $count++;   
                } // end while
            } // end else
        $x++;
        } // end day while
        
        return $out;
        
    }

    public function getForecast() {
        $Forecast = json_decode(file_get_contents('http://api.wunderground.com/api/' . $this->apiKey . '/forecast/q/' . $this->location . '/' . $this->city . '.json'));
        //vdebug($Forecast->forecast->simpleforecast);
        for ($i = 0; $i <= 2; $i++) {
            $out[$i]['Chigh'] = $Forecast->forecast->simpleforecast->forecastday[$i]->high->celsius;
            $out[$i]['Clow'] = $Forecast->forecast->simpleforecast->forecastday[$i]->low->celsius;
            $out[$i]['weekday_short'] = $Forecast->forecast->simpleforecast->forecastday[$i]->date->weekday_short;
            $out[$i]['conditions'] = $Forecast->forecast->simpleforecast->forecastday[$i]->conditions;
            $out[$i]['pop'] = $Forecast->forecast->simpleforecast->forecastday[$i]->pop;
            $out[$i]['date'] = $Forecast->forecast->simpleforecast->forecastday[$i]->date->pretty;
        }
        return $out;
            //http://api.wunderground.com/api/2bacbed8e7e38929/forecast/q/UK/Billinge.json
    }

    /**
     * Get 10 day forecast information
     * 
     * @param 
     * @return string
     */
    public function getForecast10day() {
        $Forecast10day = json_decode(file_get_contents('http://api.wunderground.com/api/' . $this->apiKey . '/forecast10day/q/' . $this->location . '/' . $this->city . '.json'));
        //vdebug($Forecast10day);
        //die();
        for ($i = 0; $i <= 9; $i++) {
            $out[$i]['Chigh'] = $Forecast10day->forecast->simpleforecast->forecastday[$i]->high->celsius;
            $out[$i]['Clow'] = $Forecast10day->forecast->simpleforecast->forecastday[$i]->low->celsius;
            $out[$i]['weekday_short'] = $Forecast10day->forecast->simpleforecast->forecastday[$i]->date->weekday_short;
            $out[$i]['pop'] = $Forecast10day->forecast->simpleforecast->forecastday[$i]->pop;
            $out[$i]['pretty'] = $Forecast10day->forecast->simpleforecast->forecastday[$i]->date->pretty;
            $out[$i]['yday'] = $Forecast10day->forecast->simpleforecast->forecastday[$i]->date->yday;
            $out[$i]['year'] = $Forecast10day->forecast->simpleforecast->forecastday[$i]->date->year;
        }
        
        return $out;
    }

    /**
     * Get satelite .gif
     * 
     * @param bool $imgTag
     * @return string
     */
    public function getSateliteImage() {
        $img = file_get_contents('http://api.wunderground.com/api/' . $this->apiKey . '/animatedsatellite/q/' . $this->location . '/' . $this->city . '.gif?key=sat_ir4&basemap=1&timelabel=1&timelabel.y=10&num=8&delay=40&width=1000&height=1000');
        $img = '<img src="data:image/gif;base64,' . base64_encode($img) . '" style="width: 450px; height: 450px;" />';

        return $img;
    }

    /**
     * Get radar .gif
     * 
     * @param bool $imgTag
     * @return string
     */
    public function getRadarImage() {
        $img = file_get_contents('http://api.wunderground.com/api/' . $this->apiKey . '/animatedradar/q/' . $this->location . '/' . $this->city . '.gif?key=sat_ir4&basemap=1&timelabel=1&timelabel.y=10&num=8&delay=40');
        $img = '<img src="data:image/gif;base64,' . base64_encode($img) . '" style="width: 100px; height: 100px;" />';

        return $img;
    }

    /**
     * Set Language, http://www.wunderground.com/weather/api/d/docs?d=language-support
     * 
     * @param string $lang
     */
    public function setLang($lang) {
        $this->lang = $lang;
    }

    /**
     * 
     * @param string $location
     * @param string $city
     */
    public function setLocation($location, $city) {
        $this->location = $location;
        $this->city = $city;
    }

    /**
     * Get city name and country/state
     * 
     * @return string
     */
    public function getLocation() {
        return $this->json->current_observation->display_location->full;
    }

    /**
     * 
     * @param string $apiKey
     */
    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
    }

}
?>
