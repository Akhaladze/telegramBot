<?php
/*	Set debug env option for developer */
	if ($_SERVER['REMOTE_ADDR'] != '95.158.43.189') include('envDev.php');

class Botpocessor 
{


/*



*/


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


/* 	Check initialization status 

	

*/

public function getUpdates() {

/* 	Load and configure initial params  */
	$config = json_decode(file_get_contents("config.json"), TRUE);
	$mainURL = $config['BaseURL'] . $config['APIKEY'];

/* 	Try to load last processed updates identificator (if it absent set $last_message_filename = 0) */
	$last_message_filename = $config['last_message_filename'];
	$last_message_id = file_get_contents($last_message_filename);
	

/*  Load updates from telegrab bot */
	$updates = json_decode(file_get_contents($mainURL ."/getUpdates"), FALSE);
	
/*  Select just not processed updetes (messages) */	
	foreach ($updates->result as $key=>$value) {
		(int) $val_bot = $value->update_id;
		(int) $val_last = $last_message_id;
		if ((int)$val_last < (int)$val_bot) {
			echo "Going Forward";
		} 
		
		else if ((int)$val_last >= (int)$val_bot) {	
			echo "This message updates has been processed before " . $key;
			unset ($updates->result[$key]);
		}
	}
	
	return $updates;

}


private function updateLatestMessageId($last_message_id) {
	
	$last_message_id = $last_message_id . "\n";
	$last_message_filename = fopen("/var/www.api/telegramBot/last_message.txt", "w");
	fwrite($last_message_filename, $last_message_id);
	fclose($last_message_filename);
		
	return 0;
}

public function getCompare($updatesArray, $searchArray) {
	$ii=0;
	if (isset($updatesArray->ok) && $updatesArray->ok == 1) {

		foreach($updatesArray->result as $job) {
		
		/* 	Leave just chat messages types */
		
			if (!isset($job->message->entities[0]->type )) {
				
				$toCompare = $job->message->text;

				foreach ($searchArray as $keyData => $valuesData) {
					foreach ($valuesData as $val) {
					
						$toCompare = mb_strtolower($toCompare, 'UTF-8');
				
							if ($toCompare == $val) {
					
								//$output = shell_exec($keyData);
								$output = 'TTT';
								$result = "SAME VALUES, Lunux cmd: " .  $keyData . " Output: " . $output;
							}

							else {$result = "not same";}
						
						$resArray[$ii] = "SearchValue: "  .  $val . " and User Message "  . $toCompare . " is " .  $result . " <br>";
						$ii++;
					}
				}
			}
			
			self::updateLatestMessageId($job->update_id);
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
echo "<h2>Results</h2>";
echo "<h3>Search Array</h3>";
var_dump($searchArray);
echo "<h3>Updates Array</h3>";
var_dump($updatesArray);
echo "<h3>Compare Result</h3>";
var_dump($getCompare);

?>