 <!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wather application</title>
</head>

<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>




    <form method="post" id="adres" onsubmit="return weather.submitf()">
       <label>Type your country, region or city:<br></label>
        <input type="text" name="adres" id="adresInput">
        
        <label><br>Chose temperature unit:<br></label>
          <select name="temperatureUnits" id="temperatureUnits" >
            <option value="K">K</option>
            <option value="F">F&deg;</option>
            <option value="C">C&deg;</option>
          </select>
        <label><br>Chose wind speed unit:<br></label>
          <select name="windUnits" id="windUnits">
            <option value="meters per second">meters per second</option>
            <option value="kilometers per hour">kilometers per hour</option>
            <option value="miles per hour">miles per hour</option>
          </select>
      <br>
       <input type="submit" id="submit" value="See the weather">
       
       <div id="forecast_list_ul"></div>
    </form>


 <script>
     var json;
     
     
     var temperatureUnits = $("#temperatureUnits").val();
     var windUnits = $("#windUnits").val();

     

<?php

if(isset($_POST['adres'])){
    
  $adres=$_POST['adres'];

  if($adres)
{

  $adres=str_replace(" ","+",$adres);
  $geo_query='https://maps.googleapis.com/maps/api/geocode/json?address='.$adres.'&key=AIzaSyBcMDp_7YQr1A0jjUYaAFTwmXO9zclHaYA';
  $adres=str_replace("+","",$adres);
  $geo_content= file_get_contents($geo_query);
  $geo_content=json_decode($geo_content);
  $lat= $geo_content->results[0]->geometry->location->lat;
  $lon= $geo_content->results[0]->geometry->location->lng;
  $weather_query = 'http://api.openweathermap.org/data/2.5/weather?lat='.$lat.'&lon='.$lon.'&APPID=7f89efd0f965cdfe9f74e932bbfe4ff2';
  $weather_content = file_get_contents($weather_query);
  $weather_content=json_encode($weather_content);
  echo 'json = ' . $weather_content . ';';
  echo "localStorage.setItem('".$adres."', json);";
  }
  }

    /* if(isset($_POST['windUnits'])){
$windUnits=$_POST['windUnits'];
echo 'windUnits = "' .$windUnits.'";';

} 
    if(isset($_POST['temperatureUnits'])){
$temperatureUnits=$_POST['temperatureUnits'];
echo 'temperatureUnits = "'.$temperatureUnits.'"';
    } */

?>

var weather = {
getSearchData: function(JSONobject=json, windUnits, temperatureUnits) {
  //JSONobject = ParseJson(JSONtext);
 JSONobject=JSON.parse(JSONobject);
  var html = '';




    var name = JSONobject.name +', '+JSONobject.sys.country;
    var temp = Math.round(10*(JSONobject.main.temp -273.15))/10 ;
    
   if(temperatureUnits=='K'){
   temp=temp + 273;
   temp=temp+'K';
   }else if(temperatureUnits=='F'){
     temp=temp*9/5+32;
     temp=temp.toFixed(2);
     temp=temp+'F&deg;';
   }


    var text = JSONobject.weather[0].description;
    var img =  "http://openweathermap.org/img/w/" +JSONobject.weather[0].icon + ".png";
    var flag = "http://openweathermap.org/images/flags/" +JSONobject.sys.country.toLowerCase()  + ".png";
    var gust = JSONobject.wind.speed;

if(windUnits=='kilometers per hour'){
   gust=gust*1000/3600;
   gust=gust.toFixed(2);
   gust=gust+' km/h';
   }else if(windUnits=='miles per hour'){
     gust=gust*1609.3472/3600;
     gust=gust.toFixed(2);
     gust=gust+' mph';
     
   }else{
    gust=gust+' m/s';

   }

    var pressure = JSONobject.main.pressure ;
    var cloud=JSONobject.clouds.all ; 

    
    var row = '<tr><td><img src="' + img + '"></td><td><b><a href="/city/' + JSONobject.id + '"> ' + name + '</a></b> <img src="' + flag + '" ><b><i> ' + text + '</i></b><p><span class="badge badge-info">temperature: ' + temp + ' </span>wind: ' + gust+ ' . clouds: ' + cloud + ' %, pressure: ' + pressure + ' hPa</p><p>Geo coords <a href="/weathermap?zoom=12&lat=' + JSONobject.coord.lat + '&lon=' + JSONobject.coord.lon + '">[' + JSONobject.coord.lat + ', ' + JSONobject.coord.lon + ']</a></p></td></tr>';
            
    
   /* var column = `<td><img src="${img}"></td><td><b><a href="/city/${JSONobject.list[i].id}"> ${name}</a></b> <img src="${flag}"><b><i> ${text}</i></b><p><span class="badge badge-info">${temp} °С </span> temperature from ${tmin} to ${tmax}°С, wind ${gust} m/s. clouds ${cloud}%, ${pressure} hpa</p><p>Geo coords <a href="/weathermap?zoom=12&lat=${JSONobject.list[i].coord.lat}&lon=${JSONobject.list[i].coord.lon}">[${JSONobject.list[i].coord.lat}, ${JSONobject.list[i].coord.lon}]</a></p></td>`; 
    var row = `<tr>${column}</tr>`;
    */
    
    html = html + row;    

  

   /* html=`<table class="table">${html}</table>`; */
   html='<table class="table">' + html + '</table>';

  $("#forecast_list_ul").html(html);
  //ShowInfoMess(html);

},
     submitf: function(){
temperatureUnits = $("#temperatureUnits").val();
windUnits = $("#windUnits").val();
var adres = $("#adresInput").val();
json = localStorage.getItem(adres);
this.getSearchData(json, windUnits, temperatureUnits);


if(json == null){
 return true;
}else{
    return false;
}
}
     }
weather.getSearchData(json, windUnits, temperatureUnits);

     
</script> 


</body>

</html>