<?php

function getYazTekFiratAnnouncements(){
	$results = []; 

	$source = getSourchFromUrl1('http://yaz.tf.firat.edu.tr/tr/announcements-all/1');

	$source = preg_replace('/\n/', '', $source);

	preg_match_all('@<div class="anno-details"(.*?)</div>@', $source, $response_duyuru);

	for($i=0;$i<count($response_duyuru[0]);$i++){

		preg_match_all('@<a href="(.*?)"@', $response_duyuru[0][$i], $response_duyuru_url);
		preg_match_all('@<p class="anno-details-title">(.*?)</p>@', $response_duyuru[0][$i], $response_duyuru_title);
		preg_match_all('@<p class="anno-details-description">(.*?)</p>@', $response_duyuru[0][$i], $response_duyuru_desc);

		$row['title'] = trim($response_duyuru_title[1][0]);
		$row['description'] = trim($response_duyuru_desc[1][0]);
		$row['url'] = trim($response_duyuru_url[1][0]);

		array_push($results, $row);

	}

	return $results;

}

function getTekFiratAnnouncements(){
	$results = []; 

	$source = getSourchFromUrl1('http://tf.firat.edu.tr/tr/announcements-all/1');

	$source = preg_replace('/\n/', '', $source);

	preg_match_all('@<div class="anno-details"(.*?)</div>@', $source, $response_duyuru);

	for($i=0;$i<count($response_duyuru[0]);$i++){

		preg_match_all('@<a href="(.*?)"@', $response_duyuru[0][$i], $response_duyuru_url);
		preg_match_all('@<p class="anno-details-title">(.*?)</p>@', $response_duyuru[0][$i], $response_duyuru_title);
		preg_match_all('@<p class="anno-details-description">(.*?)</p>@', $response_duyuru[0][$i], $response_duyuru_desc);

		$row['title'] = trim($response_duyuru_title[1][0]);
		$row['description'] = trim($response_duyuru_desc[1][0]);
		$row['url'] = trim($response_duyuru_url[1][0]);

		array_push($results, $row);
		
	}

	return $results;

}

function getSourchFromUrl1($url){

	$ip = rand(10,255).'.'.rand(10,255).'.'.rand(10,255).'.'.rand(10,255);

	$referers = ["https://www.google.com/","https://www.yandex.com/","https://www.bing.com/","https://www.facebook.com/","https://www.twitter.com/","https://www.instagram.com/","https://www.yahoo.com/"];

	$user_agents = ["Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36","Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36","Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36"];

	
	shuffle($referers);

	shuffle($user_agents);

	$ch=curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$proxy_array = array("185.208.100.72:4145", "31.223.34.235:4153", "88.255.102.20:1080", "95.70.220.173:4153");

	shuffle($proxy_array);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

	curl_setopt($ch, CURLOPT_MAXREDIRS, 20);

	curl_setopt($ch, CURLOPT_REFERER, $referers[0]);

	curl_setopt($ch, CURLOPT_USERAGENT, $user_agents[0]);

	$header = array();
	$header = ["Accept-Language: tr-TR,tr;q=0.5","REMOTE_ADDR: ".$ip,"HTTP_X_FORWARDED_FOR: ".$ip]; 

	curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 

	$sourche = curl_exec($ch);

	curl_close($ch);

	return $sourche;
}

?>