<?php

require 'firat_announcements.php';

header('Content-Type: application/json; charset=utf-8');

$results = [];

$yaz_tek_announcements = getYazTekFiratAnnouncements();

$tek_announcements = getTekFiratAnnouncements();

$results['yaz.tek.firat.edu.tr'] = $yaz_tek_announcements;
$results['tek.firat.edu.tr'] = $tek_announcements;

echo json_encode($results);



?>