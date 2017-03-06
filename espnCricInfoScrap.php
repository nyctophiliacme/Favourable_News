<?php
   require_once('simple_html_dom.php');
   $urlEncoded = $_POST['url'];
   $urls = json_decode($urlEncoded);

   $mng = new MongoDB\Driver\Manager();

   foreach ($urls as $url) 
   {
      // $url = 'http://www.espncricinfo.com/india-v-australia-2016-17/content/story/1083925.html';
      $html = file_get_html($url);
      libxml_use_internal_errors(TRUE);
      libxml_clear_errors();
      echo "$url<br/>";

      $data = "";
      $headlineData = "";

      $headlines = $html->find('h1.col-1-1');
      foreach($headlines as $headline)
      {
      	  echo $headline;
           $data .= $headline->plaintext;
           $headlineData .= $headline->plaintext;
      }

      $extras = $html->find('span.sub-headline');
      $extraInfoData = "";
      foreach ($extras as $extra)
      {
      	  echo "<h3>$extra->plaintext</h3>";
           $extraInfoData .= $extra->plaintext;
      }
      $extras = $html->find('span.col-3-12');
      foreach ($extras as $extra)
      {
           echo "<h3>$extra->plaintext</h3>";
           $extraInfoData .= $extra->plaintext;
      }

      $elements = $html->find('section.main-content p,section.main-content div.end-copyright');
      // echo sizeof($elements);
      $contentData = "";
      foreach($elements as $element)
      {
         if($element->getAttribute('class') == "insert-para")
         {
            //do nothing
         }
      	else if($element->getAttribute('class')=="end-copyright col-9-12")
         {
            // break;
            // echo $element;
            break;
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
         $username='079d3f3f-1b27-4b7c-87df-b9ca97e02347';
         $password='CYWxjjImbybr';
         $data = json_encode(array('text' => $data));
         $URL='https://gateway.watsonplatform.net/tone-analyzer/api/v3/tone?version=2016-05-19&sentences=false';

         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL,$URL);
         curl_setopt($ch, CURLOPT_TIMEOUT, 100); 
         curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
         curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
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

         if($angerVal <= 0.7 && $disgustVal <= 0.7 && $fearVal <= 0.7 && $sadnessVal <= 0.75)
         {
             $bulk = new MongoDB\Driver\BulkWrite;
         
             $doc = ["_id" => new MongoDB\BSON\ObjectID, "headline" => $headlineData, "content" => $contentData, "extraInfo" => $extraInfoData];
             $bulk->insert($doc);

             $mng->executeBulkWrite('articles.espnCricInfoData', $bulk);
         }
      }
      echo "<h1>-----------------------------------------------------------------------------------------------------------------------</h1><br/>";
      $data = "";

   }
 ?>  