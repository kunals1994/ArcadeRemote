<?php
//error_reporting(E_ALL);
//ini_set('display_errors', True);

$ch = curl_init();
$url = "http://ex.fm/api/v3/trending/tag/techno";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$contents = curl_exec($ch);
$array = explode("\n", $contents);
$stream = array();
$count = 0;
for ($i = 0; $i < count($array); $i++) {
  $values = explode("\"", $array[$i]);
  for ($j = 0; $j < count($values); $j++) {
    if ($values[$j] == "url") {
      if (!strstr($values[$j + 2], "api.soundcloud.com")) {
        $stream[$count] = $values[$j + 2];
        $count = $count + 1;
      }
    }
  }
}
for ($i = 0; $i < count($stream); $i++) { 
  $stream[$i] = str_replace(array("\\"), "", $stream[$i]);
}
$audio = $stream[rand(0, count($stream) - 1)];
echo
"
<!DOCTYPE html>
<html>
<head>
<title>Test</title>
<script src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js\"></script>
<script src=\"http://masonry.desandro.com/jquery.masonry.min.js\" type=\"text/javascript\"></script>
<script>
var $container = $('#container');
$container.imagesLoaded(function(){
  $container.masonry({
    itemSelector : '.item',
    columnWidth : 220
  });
});
</script>
<style>
.item {
  max-width: 220px;
  max-height: 220px;
  margin: 10px;
  float: left;
}
.centered_div {
   position: absolute;
   left: 50%;
   top: 50%;
   color:#ccccc0;
   font-size:12em;
   font-face:calibri;
   text-shadow:3px 3px black;
   margin-left:-1em;
   margin-top:-1em;
}
</style>
</head>
<body style=\"background-color: #000\">
<audio controls autoplay loop style=\"display: none;\">
<source src=\"$audio\" type=\"audio/mpeg\">
</audio>
";

$TAG = "arcade%20gaming";
$API_KEY = "A9xpGCjC72RvnsDKCRYAE9p90EGyHMsKWOAaBhkIFXtagNgADv";
$tumblr = "http://api.tumblr.com/v2/tagged?tag=" . $TAG . "&api_key=" . $API_KEY;
curl_setopt($ch, CURLOPT_URL, $tumblr);
$json = curl_exec($ch);
$json_output = json_decode($json);
$pictures = array();
$count = 0;

//print_r($json_output);
echo
"
<div id=\"container\">
<div class=\"centered_div\">
Pong
</div>
";
foreach ($json_output->response as $response) {
  foreach ($response->photos as $photos) {
    if ($count < 20) {
      echo "<div class=\"item\">";
      echo "<img src=\"{$photos->original_size->url}\">";
      echo "</div>";
      $count = $count + 1;
    }
  }
}
echo
"
</div>


<script type="text/javascript">
var num=0;
players=[];
var oldLog = console.log;
var lastLog;
console.log = function () {
    lastLog = arguments;
    oldLog.apply(console, arguments);
};
window.moteio_config = {
  api_version: '0.1',
  app_name: 'Dev Doc',
  blocks: [
    {
      type: 'buttons',
      data: [
        {
          press: function () {
          	if(players[0]==lastLog[0].uuid)
          		{
          			return;
          		}
          	players.push(lastLog[0].uuid);
            num=num+1;
            if(num==2){
            	window.location = "/Users/Kunal/Desktop/javascript-pong-master/index.html?p1="+players[0]+"&p2="+players[1]
            }
          },
          icon: 'off'
        }
      ]
    }
  ]
}
</script>
<script type="text/javascript" src="http://mote.io:80/js/plugin.js"></script>

</body>
</html>
"
?>
