<?php
  require_once('simple_html_dom.php');
  // $urlEncoded = $_POST['url'];
  // $urls = json_decode($urlEncoded);

  $var = $argv[1];
  $temp = $argv[2];
  $varDesc = $argv[3];
   // echo $var;

  $desc = unserialize($varDesc);
  $urls = unserialize($var);
  $urlToImgs = unserialize($temp);

  $mng = new MongoDB\Driver\Manager();

  $context = new ZMQContext();
  $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
  $socket->connect("tcp://localhost:5555");

  $itr = 0;
  foreach ($urls as $url) 
  {
      // $url = 'http://www.thehindu.com/opinion/editorial/moonlit-reality/article17378488.ece';
      echo "$url<br/>";
      $html = file_get_html($url);
      libxml_use_internal_errors(TRUE);
      libxml_clear_errors();
      $title = $html->find("h1.title");
      $data = "";
      $headlineData = "";
      foreach($title as $titleitr)
      {
         echo $titleitr;
         $data .= $titleitr->plaintext;
         $headlineData .= $titleitr->plaintext;
      }
      $elems = $html->find("div#content-body-(.*?) p,div#content-body-(.*?) div.live-snippet-border");

      $containers = $html->find("div.ut-container");
      $extraInfoData = "";
      foreach($containers as $container)
      {
         echo "<h3>$container</h3>";
         $extraInfoData .= $container->plaintext;
      }
      $intros = $html->find("h2.intro");
      $contentData = "";
      foreach ($intros as $intro) 
      {
         echo $intro;
         $data .= $intro->plaintext;
         $contentData .= $intro->plaintext;
      }
      $i = 0;
      foreach($elems as $elem)
      {
         $i++;
         if($i == 1) continue;
         if($elem->find("div"))
         {   
            //do nothing
         }
         else if($elem->getAttribute('class') == "author-text hidden-xs")
         {
            //do nothing
         }
         else if($elem->getAttribute('dir') == "ltr")
         {
            //do nothing
         }
         else
         {
            echo $elem->plaintext;
            $data .= $elem->plaintext;
            $contentData .= $elem->plaintext;
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

                 $mng->executeBulkWrite('articles.theHinduData', $bulk);

                 $entryData = array(
                  'url' => $url ,
                  'category' => "theHinduNews" ,
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
        echo "Hello  $desc[$itr]<br/>";
      echo "<h1>-----------------------------------------------------------------------------------------------------------------------</h1><br/>";
      $data = "";
      $itr++;
    }
?>