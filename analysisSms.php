<?php
//echo "<meta http-equiv='refresh' content='1'>";


$url = "http://localhost/90MinutesCompetition/public/api/analysisSms";
$g = file_get_contents($url);

echo "<br>" . $g . "<br>";
