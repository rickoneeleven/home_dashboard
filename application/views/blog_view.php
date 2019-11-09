<?php


$icon = $darkskySummary->row('icon');
$icon_coming = $darkskySummary->row('icon_coming');
$yesterday_temp_color = "grey";
$today_temp_color = "dodgerblue";
$tonight_temp_color = "dodgerblue";
$tomorrow_temp_color = "white";
$tomorrownight_temp_color = "white";

if($darkskyDaily[0]['max'] === $darkskyYesterday['max']) $today_temp_color = "white";
if($darkskyDaily[0]['min'] === $darkskyYesterday['min']) $tonight_temp_color = "white";
if($darkskyDaily[0]['max'] > $darkskyYesterday['max']) $today_temp_color = "darkred";
if($darkskyDaily[0]['min'] > $darkskyYesterday['min']) $tonight_temp_color = "darkred";

if($darkskyDaily[1]['max'] < $darkskyDaily[0]['max'] && $darkskyDaily[1]['max'] < $darkskyYesterday['max']) $tomorrow_temp_color = "dodgerblue";
if($darkskyDaily[1]['min'] < $darkskyDaily[0]['min'] && $darkskyDaily[1]['min'] < $darkskyYesterday['min']) $tomorrownight_temp_color = "dodgerblue";
if($darkskyDaily[1]['max'] > $darkskyDaily[0]['max'] && $darkskyDaily[1]['max'] > $darkskyYesterday['max']) $tomorrow_temp_color = "darkred";
if($darkskyDaily[1]['min'] > $darkskyDaily[0]['min'] && $darkskyDaily[1]['min'] > $darkskyYesterday['min']) $tomorrownight_temp_color = "darkred";


?>
<div id="container">
    <div id="body">
        <span align="center">
        <?php
        echo "</br>";
        echo "<span style=\"font-size:15px\">$themed_day</span>";
        echo "</br>";
        echo "<span style=\"font-size:40px;\">$days_ago_last_drank</span>";
        echo "</br>";
        echo "</br>";
        echo "<span style=\"color: $yesterday_temp_color; font-size:20px\">".
        $darkskyYesterday['day'].": ".$darkskyYesterday['max']."</span>";
        echo "</br>";
        echo
            "<span style=\"color: $tonight_temp_color; font-size:15px\">(".$darkskyDaily[0]['min'].")</span><span style=\"color: $today_temp_color; font-size:25px\">". $darkskyDaily[0]['day'].": ".$darkskyDaily[0]['max']."</span><span style=\"font-size:25px\"><a href='https://darksky.net/forecast/53.4974,-2.7074/ca12/en'>,</a> </span>
            <span style=\"color: $tomorrownight_temp_color; font-size:15px\">(".$darkskyDaily[1]['min'].")</span><span style=\"color: $tomorrow_temp_color; font-size:25px\">".$darkskyDaily[1]['day'].": ".$darkskyDaily[1]['max']."</span><span style=\"font-size:25px\">, <span style=\"font-size:15px\">(".$darkskyDaily[2]['min'].")</span>".$darkskyDaily[2]['day'].": ".$darkskyDaily[2]['max']."</span><span style=\"font-size:25px\">".
            "</br></span><span style=\"font-size:15px\">(".$darkskyDaily[3]['min'].")</span><span style=\"font-size:20px\">".$darkskyDaily[3]['day'].": ".$darkskyDaily[3]['max'].", </span><span style=\"font-size:15px\">(".$darkskyDaily[4]['min'].")</span><span style=\"font-size:20px\">".$darkskyDaily[4]['day'].": ".$darkskyDaily[4]['max']."</span>";
        echo "</br>";
        echo'       <figure class="icons">
                        <canvas class="'.$icon.'" width="128" height="128"></canvas><span style="font-size:200px" >&#8250;</span>
                        <canvas id="'.$icon_coming.'" width="100" height="100"></canvas>
                    </figure>';
        echo "<span style=\"font-size:15px; color:black; line-height: 5px\">Feels Like: ".$darkskySummary->row('apparentTemp')." &#8250; ".$darkskySummary->row('apparentTemp_coming')."</br></span>";
echo "<span style=\"font-size:15px; color:dimgrey; line-height: 5px\">Temp: ".$darkskySummary->row('current_temp')." &#8250; ".$darkskySummary->row('temp_coming')."</br>
Gusts: ".$darkskySummary->row('windGust')." &#8250; ".$darkskySummary->row('windGust_coming')."</br>
Chance Rain: ".$darkskySummary->row('precipProbability')." &#8250; ".$darkskySummary->row('precipProbability_coming')."</br>";
echo "<span style=\"font-size:15px; color:dimgray; line-height: 5px\">Rain Intensity: ".$darkskySummary->row('precipIntensity')." &#8250; ".$darkskySummary->row('precipIntensity_coming')."</br>
</span>";
        echo "</br>";
        //echo "<span style=\"font-size:20px\">".$darkskySummary->row('summary_hour')."</span>";
        echo "</br>";
        ?>
        </span>
    </div>
</div>
<script src="112/skycons.js"></script>

<script>
    //had to use elements for one set of skycons (https://stackoverflow.com/questions/24572100/skycons-cant-display-the-same-icon-twice), and .. the
    //documented style for the second, otherwise either if the icons where the same, one would not show up. or if i used elements for both, the color
    //was always the same.
    var icons = new Skycons({"color": "orange"}),
        list  = [
            "clear-day", "clear-night", "partly-cloudy-day",
            "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
            "fog"
        ],
        i;
    for(i = list.length; i--; ) {
        var weatherType = list[i],
            elements = document.getElementsByClassName( weatherType );
        for (e = elements.length; e--;){
            icons.set( elements[e], weatherType );
        }
    }
    icons.play();

    var icon_coming = new Skycons({"color": "dimgray"});
    icon_coming.set("<?php echo $darkskySummary->row('icon_coming');?>", "<?php echo $darkskySummary->row('icon_coming');?>");
    icon_coming.play();
</script>