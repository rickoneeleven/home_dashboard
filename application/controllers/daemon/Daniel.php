<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daniel extends CI_Controller {

    public function save12HourTemp(){
        $this->load->model('Sqlqu');
        $this->load->model('Openweather');
        $insert_data = array(
            'low'   => $this->Openweather->lowestTemp(),
            'high'  => $this->Openweather->highestTemp(),
        );
        $this->Sqlqu->insertHighLow12Hour($insert_data);

    }

    public function saveLiveTemp() {
        $this->load->model('Sqlqu');
        $this->load->model('Openweather');
        $weather = $this->Openweather->getWeather();
        $this->Sqlqu->insertLive($weather->temperature->getValue());
    }

    public function themedDay() {
        $this->db->where('name', 'themed_day');
        $otherTable = $this->db->get('other');
        echo $otherTable->num_rows();
        if($otherTable->row('value') == 0) $update['value'] = 0;
        if($otherTable->row('value') == 1) $update['value'] = 1;
        if($otherTable->row('value') == 2) $update['value'] = 2;
        $update['datetime'] = date('Y-m-d H:i:s');

        $this->db->where('id', 1);
        $this->db->update('other', $update);
    }

    public function magicHappeningGenerator() {
        $this->db->where('id', 3);
        $otherTable = $this->db->get('other');
        if($otherTable->row('value') == 1) {
            echo "deploying manual process";
            $reset_manual = array(
                'value'         => 0,
                'datetime'      => date('Y-m-d H:i:s'),
            );
            $this->db->where('id', 3);
            $this->db->update('other', $reset_manual);

            $magic_happens = array(
                'value'         => 1,
                'datetime'      => date('Y-m-d H:i:s'),
            );
            $this->db->where('id', 2);
            $this->db->update('other', $magic_happens);
        } else {
            $our_survey_says = rand(1,11);
            $magic_johnson = 0;
            if($our_survey_says === 11) $magic_johnson = 1;
            if($our_survey_says === 10) $magic_johnson = 2;
            $update['value'] = $magic_johnson;
            $update['datetime'] = date('Y-m-d H:i:s');

            $this->db->where('id', 2);
            $this->db->update('other', $update);
            echo $update['value']." &nbsp&nbsp&nbsp ".'<a href="./magicHappeningGenerator">'.date("H:i:s")."</a>";
        }
    }

    /**
     * we don't want to set Chigh after 2pm, as the high for today will start to be logged as the high from this
     * point of the day forward. say at 6pm the high for from 6pm to 23 is going to be lower than the actual
     * hught for the day. [update: don't think this is needed now, the high from the api seems to give the high
     * of the whole day, maybe the fix i put in place here was to addres another bug i have solved. i had  bug
     * where the days beforetemprature was reporting wrong because it was getting the data from the same day as
     * yesterday, i.e. "Tue" but for the following week. that bug is fixed now
     */
    public function saveUnderForecast() {
        $this->load->model('Wunderground');
        $three_day_forecast = $this->Wunderground->getForecast();
        //vdebug($three_day_forecast);
        foreach($three_day_forecast as $item) {
            if($item['Chigh']) {
                //vdebug($item);
                $data_for_insert = array(
                    'Chigh'             => $item['Chigh'],
                    'Clow'              => $item['Clow'],
                    'weekday_short'     => $item['weekday_short'],
                    'datetime'          => date('Y-m-d H:i:s'),
                    'conditions'        => $item['conditions'],
                    'pop'               => $item['pop'],
                    'date'              => $item['date'],
                );
                //if(date('H') > 14) {
                    //$this->db->where('weekday_short', date('D'));
                    //$this->db->order_by('id', 'DESC');
                    //$this->db->limit(1);
                    //$underwea_forcTable = $this->db->get('underwea_forc');
                    //echo $underwea_forcTable->row('datetime');
                    //die();
                //}
                //vdebug($data_for_insert);
                $this->db->insert('underwea_forc', $data_for_insert);
            }
        }
    }

    public function saveUnderForecast10day() {
        $this->load->model('Wunderground');
        $ten_day_forecast = $this->Wunderground->getForecast10day();
        //vdebug($ten_day_forecast);
        foreach($ten_day_forecast as $item) {
            if($item['Chigh']) {
                //vdebug($item);
                $data_for_insert = array(
                    'Chigh'             => $item['Chigh'],
                    'weekday_short'     => $item['weekday_short'],
                    'datetime'          => date('Y-m-d H:i:s'),
                    'pretty_date'       => $item['pretty'],
                    'yday'              => $item['yday'],
                    'year'              => $item['year'],
                );
                $this->db->insert('underwea_forc_10day', $data_for_insert);
            }
        }
    }

    public function truncateTable() {
        $days_ago =  "datetime < (NOW() - INTERVAL 7 DAY)";
        $this->db->where($days_ago);
        $this->db->delete('underwea_forc');

        $this->db->where($days_ago);
        $this->db->delete('underwea_forc_10day');
    }

    /**
     * run for a max of 24 cycles in case something goes wrong. not sure exactly how it would, i think you're
     * just been lazy not removing the code. Grab the low until 7am and then slam into the db
     */ 
    public function saveWunderLow() {
        $this->load->model('Wunderground');
        $getHourly = $this->Wunderground->getHourly(); 
        $count = 0;
        $low = 100;
        while($count < 24) {
            if($getHourly->hourly_forecast[$count]->temp->metric < $low) {
                $low = $getHourly->hourly_forecast[$count]->temp->metric;
                $datetime_of_low = $getHourly->hourly_forecast[$count]->FCTTIME->pretty;
            }
            $count++;
            if($getHourly->hourly_forecast[$count]->FCTTIME->hour == 07) break;
        }
        if($low) {
            $data_for_insert = array(
                'cHigh'             => 0,
                'cLow'              => $low,
                'datetime'          => date('Y-m-d H:i:s'),
                'date'            => $datetime_of_low,
            );
            $this->db->insert('underwea_hourly', $data_for_insert);
            unset($data_for_insert);
        }
    }
}
