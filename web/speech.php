<?php
	// curl -X POST -u "{username}":"{password}"
	// --header "Content-Type: application/json"
	// --header "Accept: audio/wav"
	// --data "{\"text\":\"Hello world\"}"
	// --output hello_world.wav
	// "https://stream.watsonplatform.net/text-to-speech/api/v1/synthesize?voice=en-US_AllisonVoice"


	$username='ca41e809-cf53-432f-bcd7-0afce2af4732';
	$password='h07WVQxPeMRL';
	$format = 'audio/wav';

	$first = "Hello Pransh How do you do? ";
	$second = "Messi won the match for Barcelona";
	$pause = "<speak version=\"1.0\">
  				<break time=\"1s\"></break>
			  </speak>";

	$data = $first.$pause.$second;

	$jsonData = json_encode(array('text' => $data));
	$URL='https://stream.watsonplatform.net/text-to-speech/api/v1/synthesize?voice=en-US_AllisonVoice';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100); 

	// curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept:'.$format));
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	$result = curl_exec($ch);
	curl_close ($ch);

	// echo $result;
	// echo "<br/><br/><br/><br/>";

	// $array = json_decode($result,true);

	$myfile = fopen("test.wav", "w") or die("Unable to open file!");
	fwrite($myfile, $result);
	fclose($myfile);
	// $filename = $meta_data["uri"];

	// echo $filename;

	// $mp3 = fopen("test.mp3", "w") or die("Unable to open file!");
	// fclose($mp3);
	// $fileoutname = 'tts.mp3';
	// $output;
	// exec('ffmpeg -i test.wav -y test.mp3 2>&1', $output);

	// $arrlength = count($output);
	// for($x = 0; $x < $arrlength; $x++) 
	// {
 //    	echo $output[$x];
 //    	echo "<br>";
	// }
?>
<!DOCTYPE html>
<html>
<head>
	<title>
		
	</title>
</head>
<body>
	<audio controls>
	  <source src="test.wav" type="audio/wav">
	  <!-- <source src="horse.mp3" type="audio/mpeg"> -->
		Your browser does not support the audio element.
	</audio>	
</body>
</html>
