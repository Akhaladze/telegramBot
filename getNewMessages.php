<?php
#require('envDev.php');
#include('envDev.php');

class Botpocessor 
{

public function initSearchValues() {
	
/*  Load and prepare search data */
$searchStringParams = json_decode(file_get_contents("values01.json"), FALSE);
$searchArray = [];
$searchStringArray = [];
	
	foreach ($searchStringParams as $key=>$values){
		$i=0;
		
		foreach ($values as $value) {
			$searchString = mb_strtolower($value, 'UTF-8');
			$searchStringArray[$i] = $searchString;
			$i++;
		}
		
		$searchArray[$key] = $searchStringArray;
	}
	return $searchArray;
}


/* Check initialization status */



public function getUpdates() {

/* Load and configure initial params  */
$config = json_decode(file_get_contents("config.json"), TRUE);
$mainURL = $config['BaseURL'] . $config['APIKEY'];

/* Try to load last processed message id if it absent set $last_message = 0 */
$last_message = file_get_contents("last_message.txt");

	/*  Load updates from telegrab bot */
	$updates = json_decode(file_get_contents($mainURL ."/getUpdates"), FALSE);
	
	return $updates;

}


public function getCompare($updatesArray, $searchArray) {
	$ii=0;
	if (isset($updatesArray->ok) && $updatesArray->ok == 1) {

		foreach($updatesArray->result as $job) {
		
		/* 	Leave just chat messages types */
		
			if (!isset($job->message->entities[0]->type )) {
				
				$toCompare = $job->message->text;
				
				foreach ($searchArray as $keyData=>$valuesData) {
					foreach ($valuesData as $val) {
					
						$toCompare = mb_strtolower($toCompare, 'UTF-8');
				
							if ($toCompare == $val) {
					
								$output = shell_exec($keyData);
								$result = "SAME VALUES, Lunux cmd: " .  $keyData . " Output: " . $output;
							}
				
							else {$result = "not same";}
						
						$resArray[$ii] = "SearchValue: "  .  $val . " and User Message"  . $toCompare . " is " .  $result . " <br>";
						$ii++;
			
					}
				}
			}
		}
		
	}
	return $resArray;
}



public function runCMD($cmd) {
	
	/* If messages equvalented - run linux shell command appropriate to loaded values */
	
	/* Mark message as readed changing last message id value (file: last_message.txt) */
	
	return $result;
	
	
}


}


$searchArray = Botpocessor::initSearchValues();
$updatesArray = Botpocessor::getUpdates();
$getCompare = Botpocessor::getCompare($updatesArray, $searchArray);

var_dump($searchValues);
var_dump($updates);
var_dump($getCompare);

?>