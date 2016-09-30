<?php
	echo "Enter search parameter : ";
	$name = trim(fgets(STDIN));
	$name = preg_replace('/\s+/', '+', $name);

	echo "Keyword entered is : ".$name."\n";
	$checker = explode('+',$name);
	echo <<<_END
*************Checkers*************
-Movies
	1.720p
	2.1080p
	3.CAM

-Music
	1.320kbps
	2.160kbps

***********************************

Enter checkers (y/n): 
_END;
	
	fscanf(STDIN,"%c\n",$check);

	if($check == 'y') {
		$extra = '';
		$extra = trim(fgets(STDIN));
		$extra = explode(" ",$extra);

		foreach($extra as $extra_item) {
			array_push($checker, $extra_item);
		}
	}	
	print_r($checker);

	echo "Search parameter : ".$name."<br><br>";
	$search_url = sprintf("http://www.1377x.to/srch?search=%s",$name);
	$doc = new DOMDocument();
	$html = file_get_contents($search_url);
	$doc->loadHTML($html);
	$html_save = $doc->saveHTML();
	$detail_link = '';


	foreach($doc->getElementsByTagName('a') as $link) {
		$str_link = $link->getAttribute('href');
		$check = 1;
		foreach($checker as $check) {
			if(strpos(strtolower($str_link),strtolower($check)) !== false) {
				$check = 1;
			}
			else {
				$check = 0;
				break;
			}
		}
		if($check == 1) {
			echo $str_link;
			$detail_link = $str_link;
			break;
		}
	}
	if(strlen($detail_link) == 0) {
		exit("Found no torrent link");
	}


	echo "\n\nFound a torrent link : ".$detail_link;
	$link = sprintf("http://www.1377x.to%s",$detail_link);
	
	echo "\n\n Generating SHA Checksum";
	$html = file_get_contents($link);
	$doc->loadHTML($html);
	$html_save = $doc->saveHTML();

	echo "\n\nGetting Magnet Link :\n";
	$magnet_link = $doc->getElementById('magnetdl')->getAttribute('href');
	echo $magnet_link;
	chdir('/home/jeet');

	echo "\n\nStarting transmission-cli and adding torrent";
	$cmd = sprintf('nohup transmission-cli -w /home/jeet/Downloads/ -f /home/jeet/Documents/php_work/Vorrent/Vorrent_close.sh %s &', $magnet_link);
	echo "\n\nDownloading....Please Wait Till It Finishes\n";
	echo shell_exec($cmd)."\n\n";
	
	echo "\n\nDownloaded to /home/jeet/Downloads";
?>	