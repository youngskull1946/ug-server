<?php

date_default_timezone_set('America/Mexico_City');

$lastUserTimestamp = $_GET['timestamp'] ;	

$url = 'http://reina.southcentralus.cloudapp.azure.com/OfertaCultural/folder' ;
// Get files
$path = '/var/www/html/OfertaCultural/folder' ;
//Filter images
$files = preg_grep('~\.(gif|jpg|jpeg|png|bmp|tif)$~', scandir($path));

// Get information
$size = sizeof($files) ;
$data = [] ;
foreach ($files as $name) { 
	try {
		list($title, $tags, $dateEvent, $dateModified) = explode(":", $name) ;
		///convert dates
		$dateTimeEvent = DateTime::createFromFormat('d.m.y,H.i', $dateEvent);
		if ($dateTimeEvent == false) {
			throw new Exception("File doesn't have correct format");
		}
		$timestampEvent = $dateTimeEvent->getTimestamp() ;

		$dateFormatModified = pathinfo($dateModified)['filename'] ;
		$dateTimeModified = DateTime::createFromFormat('d.m.y,H.i', $dateFormatModified) ;
		if($dateTimeModified == false) {
			throw new Exception("File doesn't have correct format");
		}
		$timestampModified = $dateTimeModified->getTimestamp() ;
		// save only necessary data
		if ( ($lastUserTimestamp < $timestampEvent) && ($lastUserTimestamp < $timestampModified) ) {
			$data[] = ['title' => $title, 'tags' => $tags, 'eventdate' => $dateEvent, 'url' => $url . "/" . $name] ;
		}
	} catch (Exception $e) {
		
	}	
}
//print_r($data);
echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ;

?>

