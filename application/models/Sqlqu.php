<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sqlqu extends CI_Model {

    /**
     * array(
     *          'back_hours_start'
     *          'back_hours_end'
     *          );
     * we create variables from array, as sql query does not like array data in query
     */
    public function getHighLowFromLiveTable($array) {
        $back_hours_start = $array['back_hours_start'];
        $back_hours_end = $array['back_hours_end'];
        $this->db->where("added >= DATE_SUB(NOW(), INTERVAL $back_hours_start HOUR)");
        $this->db->where("added <= DATE_SUB(NOW(), INTERVAL $back_hours_end HOUR)");
        $live_temperatureTable = $this->db->get('live_temperature');
        $array = array(
            'high'  => 0,
            'low'   =>100,
        );
        foreach($live_temperatureTable->result() as $row) {
            if($row->temp_now < $array['low']) $array['low'] = round($row->temp_now,0);
            if($row->temp_now > $array['high']) $array['high'] = round($row->temp_now,0);
        }
        return $array;
    }

    public function getHighLowYesterday(){
        $start_high = date("Y-m-d 10:00:00", strtotime('-1 days')); 
        $end_high = date("Y-m-d 22:00:00", strtotime('-1 days')); 
        $start_low = date("Y-m-d 22:00:00", strtotime('-1 days')); 
        $end_low = date("Y-m-d 06:00:00"); 
        $array = array(
            'high'  => 0,
            'low'   =>100,
        );

        $this->db->where("added > ", $start_high);
        $this->db->where("added < ", $end_high);
        $live_temperatureHighTable = $this->db->get('live_temperature');
        foreach($live_temperatureHighTable->result() as $row) {
            //echo "highs ".$row->added.": ". $row->temp_now."</br>";
            if($row->temp_now > $array['high']) $array['high'] = round($row->temp_now,0);
        }

        $this->db->where("added > ", $start_low);
        $this->db->where("added < ", $end_low);
        $live_temperatureLowTable = $this->db->get('live_temperature');
        foreach($live_temperatureLowTable->result() as $row) {
            //echo "lows ".$row->added.": ". $row->temp_now."</br>";
            if($row->temp_now < $array['low']) $array['low'] = round($row->temp_now,0);
        }
            return $array;
    }

    public function getWunderHighLow() {
        $weekday_short_today = date('D');
        $weekday_short_tomorrow = date('D', strtotime(' +1 day'));
        $weekday_short_yesterday = date('D', strtotime(' -1 day'));
        $this->db->where('weekday_short', $weekday_short_today);
        $this->db->limit(1);
        $this->db->order_by('id', 'DESC');
        $today = $this->db->get('underwea_forc');

        $this->db->where('weekday_short', $weekday_short_yesterday);
        $this->db->where('datetime <=', date('Y-m-d 23:59:59', strtotime(' -1 day')));
        $this->db->limit(1);
        $this->db->order_by('id', 'DESC');
        $yesterday = $this->db->get('underwea_forc');

        $this->db->where('weekday_short', $weekday_short_tomorrow);
        $this->db->limit(1);
        $this->db->order_by('id', 'DESC');
        $tomorrow = $this->db->get('underwea_forc');

        $data_for_return = array(
            'today_weekday_short'           => $today->row('weekday_short'),
            'today_weekday_datetime'        => $today->row('datetime'),
            'today_weekday_cHigh'           => $today->row('cHigh'),
            'today_weekday_cLow'            => $today->row('cLow'),
            'today_weekday_conditions'      => $today->row('conditions'),
            'today_weekday_pop'             => $today->row('pop'),
            'yesterday_weekday_short'       => $yesterday->row('weekday_short'),
            'yesterday_weekday_datetime'    => $yesterday->row('datetime'),
            'yesterday_weekday_cHigh'       => $yesterday->row('cHigh'),
            'yesterday_weekday_cLow'        => $yesterday->row('cLow'),
            'yesterday_weekday_conditions'  => $yesterday->row('conditions'),
            'tomorrow_weekday_short'        => $tomorrow->row('weekday_short'),
            'tomorrow_weekday_datetime'     => $tomorrow->row('datetime'),
            'tomorrow_weekday_cHigh'        => $tomorrow->row('cHigh'),
            'tomorrow_weekday_cLow'         => $tomorrow->row('cLow'),
            'tomorrow_weekday_conditions'   => $tomorrow->row('conditions'),
            'tomorrow_weekday_pop'          => $tomorrow->row('pop'),
            );
        return $data_for_return;
    }

    public function getLowHourly() {
        $this->db->limit(1);
        $this->db->order_by('id', 'DESC');
        $underwea_hourlyTonightTable = $this->db->get('underwea_hourly');

        $this->db->limit(1);
        $this->db->order_by('id', 'ASC');
        $this->db->where('datetime >', date('Y-m-d 03:00:00'));
        $underwea_hourlyYesterdayTable = $this->db->get('underwea_hourly');

        $data_for_return = array (
            'lowTonight'        => $underwea_hourlyTonightTable->row('cLow'),
            'lowYesterday'      => $underwea_hourlyYesterdayTable->row('cLow'),
        );
        return $data_for_return;
    }

    /**
     * the explode and implode to to convert to array, trim last few values then implode back to string
     * the if($x... is because the "high" was often being towards the back end if the week, which was then being
     * stripped off just before the implode to reduce the number of values (length of string for blogview page)
     * meant nothing was being highlighted in red.
     * used strpos to make sure only the first occurance of the high is highlighted in red
     * where date format is without leading zero, which is why day is 'j' rather than 'd'
     */
    public function getWunder10day() {
        $this->db->order_by('id', 'DESC');
        $this->db->limit(10);
        $this->db->where('yday >=', date('z'));
        $this->db->where('year >=', date('Y'));
        $underwea_forc_10dayTable = $this->db->get('underwea_forc_10day');
        //echo $this->db->last_query();
        //die();
        $days_and_temp = "";
        $high = "0";
        $x = 0;
        foreach(array_reverse($underwea_forc_10dayTable->result()) as $row) {
            if($x <= 5) {
                if($row->cHigh > $high) {
                    $high = $row->cHigh;
                }
                $x++;
            } 
            $days_and_temp = $days_and_temp.substr($row->weekday_short,0,1).$row->cHigh.", ";
        }
        $pos = strpos($days_and_temp, $high);
        if ($pos !== false) {
                $days_and_temp = substr_replace($days_and_temp, '<font color="red">'.$high.'</font>', $pos, strlen($high));
        }
        //$days_and_temp = str_replace($high,'<font color="red">'.$high.'</font>',$days_and_temp);
        $days_and_temp = rtrim($days_and_temp, ', ');
        $days_and_temp = explode(', ',$days_and_temp,-4);
        $days_and_temp = implode(', ',$days_and_temp);
        return $days_and_temp;
    }

    public function getDarknetSummary() {
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        return($this->db->get('darksky_summary'));
    }

    public function getDark8Days() {
        $counter = 0;
        $return_array = array();
        $this->db->limit(8);
        $this->db->order_by('id', 'DESC');
        $darksky_dailyTable = $this->db->get('darksky_daily');
        //die($this->last_query());
        foreach($darksky_dailyTable->result() as $row) {
            $return_array[$counter]['max'] = $row->temperatureMax;
            $return_array[$counter]['min'] = $row->temperatureMin;

            $dateTime = new \DateTime(null, new DateTimeZone('Europe/London'));
            $dateTime->setTimestamp($row->timestamp);
            $return_array[$counter]['day'] = $dateTime->format('D');
            $counter++;
        }
        $return_array = array_reverse($return_array);
        return $return_array;
    }

    public function getDarknetYesterday()
    {
        $return_array = array();
        $dateTime = new \DateTime('yesterday', new DateTimeZone('Europe/London'));
        $this->db->where('timestamp', $dateTime->getTimestamp());
        $this->db->order_by('id', "DESC");
        $this->db->limit(1);
        $darksky_dailyTable = $this->db->get('darksky_daily');
        foreach($darksky_dailyTable->result() as $row) {
            $return_array['max'] = $row->temperatureMax;
            $return_array['min'] = $row->temperatureMin;

            $dateTime = new \DateTime(null, new DateTimeZone('Europe/London'));
            $dateTime->setTimestamp($row->timestamp);
            $return_array['day'] = $dateTime->format('D');
        }
        return $return_array;
    }
}
?>
