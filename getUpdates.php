<?php
/*	Set debug env option for developer */
	if ($_SERVER['REMOTE_ADDR'] == '95.158.43.189') require('envDev.php');

/**
 * @author G.Akhaladze <4468618@gmail.com>
 * Simple script which performs execution shell script depends customer keywords in telegram bot:
 *  	- scripts running via crontab service with defined interval and collect users message updates
 *  	- scripn includes two external configuration files: with secured params and bussness logic 
 *  	- script save latest processed message id and can avoid de duplicates shell commands executions 
 *      - script have simlple integface which can be used for initial diagnostic and maintenance 
 *  	- script writen by clear PHP and not needed any critical depends 
 */

class Botpocessor 
{


/*	@params - no input params needed, aka construct
	@return - (Array) with key=>value pairs from configuration with user keywords and shell commands
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



/* 	Upload actual updates from telegram bot, check and avoid already processed messages and update last_message_id value */
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
			if ($config['env'] == 'Debug') echo "Message (update_id:" . $val_bot . ") actions not needed <br>";
		} 
		
		else if ((int)$val_last >= (int)$val_bot) {	
			if ($config['env'] == 'Debug') echo "Message (update_id:" . $val_bot . ") updates has been processed already <br>" ;
			unset ($updates->result[$key]);
		}
	}
	
	return (!empty($updates)) ? $resArray = $updates : $resArray = [];

}

/*	Provide last message id value updates	*/
public function updateLatestMessageId($last_message_id) {
	
	$last_message_id = $last_message_id . "\n";
	$last_message_filename = fopen("/var/www.api/telegramBot/last_message.txt", "w");
	fwrite($last_message_filename, $last_message_id);
	fclose($last_message_filename);
		
	return 0;
}

/*	Provides user message compare with pre-configured dictionary. Perform appropriate shell commands and manage last procesed message id updating */
public function getCompare($updatesArray, $searchArray) {
	$ii=0;
	if (isset($updatesArray->ok) && $updatesArray->ok == 1) {

		foreach($updatesArray->result as $job) {
		
/* 	Leave just chat messages types not associated with commands (/start, /help ect.) */
		
			if (!isset($job->message->entities[0]->type )) {
				
				$toCompare = $job->message->text;

				foreach ($searchArray as $keyData => $valuesData) {
					foreach ($valuesData as $val) {
					
						$toCompare = mb_strtolower($toCompare, 'UTF-8');
				
							if ($toCompare == $val) {
								// Shell execution, when it needed
								// $output = shell_exec($keyData);
								$output = 'Shell command';
								$result = "same value, Lunux cmd: " .  $keyData . " Output: " . $output;
							}

							else {$result = "not same value";}
						
						$resArray[$ii] = "SearchValue: "  .  $val . " and User Message "  . $toCompare . " is " .  $result . " <br>";
						$ii++;
					}
				}
			}
			
			self::updateLatestMessageId($job->update_id);
		}
		
	}
	
	return (!empty($resArray)) ?  $resArray : $resArray = [];
}



}

$bp = new Botpocessor();

$searchArray = $bp->initSearchValues();
$updatesArray = $bp->getUpdates();
$getCompare = $bp->getCompare($updatesArray, $searchArray);



echo "<h2>Stats</h2>";
echo "<h3>Search values</h3>";
if ($searchArray) var_dump($searchArray);
echo "<h3>Updates values from Telegram Bot</h3>";
if ($updatesArray) var_dump($updatesArray);
echo "<h3>Message updates processing details....</h3>"; 
if ($getCompare) {var_dump($getCompare);} 
else { echo "No actual messages to updates";}

?>