<?php
require 'db_config.php';
require 'firat_announcements.php';

$pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_DATABASE, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->query("SET NAMES utf8");

$results = [];

$channels = array();

$tek_channel['name'] = "Fırat Üniversitesi Teknoloji Fakültesi";
$tek_channel['url'] = "http://tf.firat.edu.tr";
$tek_channel['announcements'] = getTekFiratAnnouncements();
array_push($channels, $tek_channel);

$yaz_tek_channel['name'] = "Fırat Üniversitesi Teknoloji Fakültesi Yazılım Mühendisliği";
$yaz_tek_channel['url'] = "http://yaz.tf.firat.edu.tr";
$yaz_tek_channel['announcements'] = getYazTekFiratAnnouncements();
array_push($channels, $yaz_tek_channel);

for($i=0;$i<count($channels);$i++){

	$data = [
		'name' => $channels[$i]['name'],
		'url' => $channels[$i]['url'],
	];

	$sql = "INSERT INTO channels (name,url) SELECT :name, :url FROM DUAL WHERE NOT EXISTS (SELECT * FROM channels WHERE name=:name AND url=:url LIMIT 1)";

	$stmt = $pdo->prepare($sql)->execute($data);

	if($stmt){
		$channel = $pdo->query("SELECT * FROM channels WHERE name='".$data['name']."' AND url='".$data['url']."' LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
		if(isset($channel[0]) && !empty($channel[0]) && !empty($channel[0]['id'])){
			$channels[$i]['id'] = $channel[0]['id'];


			for($j=0;$j<count($channels[$i]['announcements']);$j++){

				$channel_id = $channels[$i]['id'];
				$title = $channels[$i]['announcements'][$j]['title'];
				$description = $channels[$i]['announcements'][$j]['description'];
				$url = $channels[$i]['announcements'][$j]['url'];
				$date = getDateNow();

				$announcement = $pdo->query("SELECT * FROM announcements WHERE title='".$title."' AND description='".$description."' LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);

				if(isset($announcement[0]) && !empty($announcement[0]) ){
					// Zaten kayıtlı
				}else{

					$sql2 = "INSERT INTO announcements (channel_id,title,description,url,date) VALUES (?,?,?,?,?)";
					$stmt2 = $pdo->prepare($sql2)->execute([$channel_id, $title, $description, $url, $date ]);

					if($stmt2){
						$mail_adress = "alper.ragib@gmail.com";
						$subject = $channels[$i]['name']." Duyuru Yayınladı!";
						$content = '
						<html>
						<head>
						<title>'.$channels[$i]['name'].' Duyuru Yayınladı!</title>
						</head>
						<body>
						<p>Merhaba, '.$channels[$i]['name'].' yeni bir duyuru yayınladı.</p>
						</br>
						<p>Duyuru başlığı: '.$title.'</p>
						<p>Duyuru içeriği: '.$description.'</p>
						<p>Duyuru bağlantısı: '.$url.'</p>
						<p>Duyuru tarihi: '.$date.'</p>
						<p>Duyuru kaynak bağlantısı: '.$channels[$i]['url'].'</p>
						</body>
						</html>
						';
						sendMail($mail_adress,$subject,$content);
					}
				}


			}

		}
	}
}


?>