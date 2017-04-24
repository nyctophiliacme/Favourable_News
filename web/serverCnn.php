<?php
   require_once('simple_html_dom.php');
  // $urlEncoded = $_POST['url'];
  // $urls = json_decode($urlEncoded);

  $var = $argv[1];
  $temp = $argv[2];
  $varDesc = $argv[3];
   // echo $var;
  $urls = unserialize($var);
  $urlToImgs = unserialize($temp);
  $desc = unserialize($varDesc);

  $mng = new MongoDB\Driver\Manager();

  $context = new ZMQContext();
  $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
  $socket->connect("tcp://localhost:5555");

  $itr = 0;
   foreach ($urls as $url) 
   {
      
      // $url = 'http://us.cnn.com/2017/02/22/us/chicago-public-schools-immigrant-students/index.html';
      echo $url;
      $html = file_get_html($url);

      $headlines = $html->find('h1.pg-headline');
      $headlineData = "";
      $data = "";
      foreach($headlines as $headline)
      {
      	  echo $headline;
           $data .= $headline->plaintext;
           $headlineData .= $headline->plaintext;
      }
      $extras = $html->find('p.update-time');

      $extraInfoData = "";
      foreach ($extras as $extra)
      {
      	  echo "<h3>$extra->plaintext</h3>";
           $extraInfoData .= $extra->plaintext;
      }

      $extra2s = $html->find('p.metadata__byline');
      foreach ($extra2s as $extra2)
      {
           echo "<h3>$extra2->plaintext</h3>";
           $extraInfoData .= $extra2->plaintext;
      }   
      // echo "<br/>";
      $elements = $html->find('div.l-container div.zn-body__paragraph,div.l-container p.zn-body__paragraph');
      // echo sizeof($elements);
      $contentData = "";
      foreach($elements as $element)
      {
         if($element->find("h3"))
         {   
           //do nothing
           //echo "h3<br/><br/><br/>";
         }
      	else
         {
            echo $element->plaintext;
            $data .= $element->plaintext;
            $contentData .= $element->plaintext;
         }
      }

      if($headlineData!="" && $contentData!="")
      {
         $username='86d9b891-54ad-4ca6-869c-314b267011b0';
         $password='bFikwVZYP5HA';
         $jsonData = json_encode(array('text' => $headlineData));
         $URL='https://gateway.watsonplatform.net/tone-analyzer/api/v3/tone?version=2016-05-19&sentences=false';

         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL,$URL);
         curl_setopt($ch, CURLOPT_TIMEOUT, 100); 
         curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
         curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
         $result = curl_exec($ch);
         curl_close ($ch);

         echo "<br/><br/><br/><br/>";

         $array = json_decode($result,true);
         
         foreach ($array['document_tone']['tone_categories'][0]['tones'] as $vals) 
         {
            echo $vals['tone_name'];
            echo " ------> ";
            echo $vals['score'];
            echo "<br/>";
         }

         $angerVal = $array['document_tone']['tone_categories'][0]['tones'][0]['score'];
         $disgustVal = $array['document_tone']['tone_categories'][0]['tones'][1]['score'];
         $fearVal = $array['document_tone']['tone_categories'][0]['tones'][2]['score'];
         $joyVal = $array['document_tone']['tone_categories'][0]['tones'][3]['score'];
         $sadnessVal = $array['document_tone']['tone_categories'][0]['tones'][4]['score'];

         if($angerVal <= 0.6 && $disgustVal <= 0.6 && $fearVal <= 0.6 && $sadnessVal <= 0.6)
         {
             $jsonData = json_encode(array('text' => $data));
             $URL='https://gateway.watsonplatform.net/tone-analyzer/api/v3/tone?version=2016-05-19&sentences=false';

             $ch = curl_init();
             curl_setopt($ch, CURLOPT_URL,$URL);
             curl_setopt($ch, CURLOPT_TIMEOUT, 100); 
             curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
             curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
             curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
             curl_setopt($ch, CURLOPT_POST, true);
             curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
             $result = curl_exec($ch);
             curl_close ($ch);

             echo "<br/><br/><br/><br/>";

             $array = json_decode($result,true);
             
             foreach ($array['document_tone']['tone_categories'][0]['tones'] as $vals) 
             {
                echo $vals['tone_name'];
                echo " ------> ";
                echo $vals['score'];
                echo "<br/>";
             }

             $angerVal = $array['document_tone']['tone_categories'][0]['tones'][0]['score'];
             $disgustVal = $array['document_tone']['tone_categories'][0]['tones'][1]['score'];
             $fearVal = $array['document_tone']['tone_categories'][0]['tones'][2]['score'];
             $joyVal = $array['document_tone']['tone_categories'][0]['tones'][3]['score'];
             $sadnessVal = $array['document_tone']['tone_categories'][0]['tones'][4]['score'];

             if($angerVal <= 0.6 && $disgustVal <= 0.6 && $fearVal <= 0.6 && $sadnessVal <= 0.6)
             {
                 $bulk = new MongoDB\Driver\BulkWrite;
             
                 $doc = ["_id" => new MongoDB\BSON\ObjectID, "headline" => $headlineData, "content" => $contentData, "extraInfo" => $extraInfoData];
                 $bulk->insert($doc);

                 $mng->executeBulkWrite('articles.cnnData', $bulk);

                 $entryData = array(
                  'url' => $url ,
                  'category' => "cnnNews" ,
                  'headlines' => $headlineData ,
                  'content' => $contentData ,
                  'extra' => $extraInfoData ,
                  'urlToImg' => $urlToImgs[$itr] ,
                  'desc' => $desc[$itr]
                  );
                 $socket->send(json_encode($entryData));
             }
          }
        }  
      echo "<h1>-----------------------------------------------------------------------------------------------------------------------</h1><br/>";
      $data = "";
      $itr++;
   }      
 ?>  