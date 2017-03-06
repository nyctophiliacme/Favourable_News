<?php
  require_once('simple_html_dom.php');
  $urlEncoded = $_POST['url'];
  $urls = json_decode($urlEncoded);

  $mng = new MongoDB\Driver\Manager();

  foreach($urls as $url)
  {
       
      // $url = 'http://timesofindia.indiatimes.com/auto/reviews/honda-city-2017-fresh-looks-new-features-make-a-resounding-comback/articleshow/57294656.cms';
      echo "<br/>$url<br/>";
      $html = file_get_html($url);
      // libxml_use_internal_errors(TRUE);
      // libxml_clear_errors();
      

      $headlines = $html->find("h1.heading1");
      $headlineData = "";
      foreach($headlines as $headline)
      {
         echo $headline;
         $data .= $headline->plaintext;
         $headlineData .= $headline->plaintext;
      }

      $timeinfos = $html->find("span.time_cptn");
      $extraInfoData = "";
      foreach($timeinfos as $timeinfo)
      {
         echo $timeinfo;
         $extraInfoData .= $timeinfo->plaintext;
      }
      echo "<br/>";

      $elements = $html->find("div.Normal");
      // echo sizeof($bodys);
      $contentData = "";
      foreach($elements as $element)
      {
         // if($body->getAttribute('class')=="key_underline")
         // if($body->getAttribute('tag') == 'a')
         // {
         //    echo "Found!!";
         // }
         // else
            echo $element->plaintext;
            $data .= $element->plaintext;
            $contentData .= $element->plaintext;
      }

      // curl -u "{079d3f3f-1b27-4b7c-87df-b9ca97e02347}":"{CYWxjjImbybr}" "https://gateway.watsonplatform.net/tone-analyzer/api/v3/tone?version=2016-05-19&text=A%20word%20is%20dead%20when%20it%20is%20said,%20some%20say.%20Emily%20Dickinson";

      // echo "<br/><br/>";
      // echo $data;
      $username='079d3f3f-1b27-4b7c-87df-b9ca97e02347';
      $password='CYWxjjImbybr';
      $data = json_encode(array('text' => $data));
      $URL='https://gateway.watsonplatform.net/tone-analyzer/api/v3/tone?version=2016-05-19&sentences=false';

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$URL);
      curl_setopt($ch, CURLOPT_TIMEOUT, 100); //timeout after 30 seconds
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
      curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
      // application/html
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      // curl_setopt($ch, CURLOPT_HTTPHEADER, array('sentences: false'));

      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
      // echo "<br/><br/>";
      // $result = 
      $result = curl_exec($ch);
      curl_close ($ch);

      // echo "<h1>Bassssss</h1>";
      // echo $result;
      echo "<br/><br/><br/><br/>";
      // print_r($result);

      // print_r($result);
      // print_r($result);

      $array = json_decode($result,true);
      // print_r($array);
      // echo "sdfasd";
      // echo $array['document_tone']['tone_categories'][0]['tones'][0]['score'];

      // echo sizeof($array['document_tone']['tone_categories'][0]['tones']);

      // for($i=0;$i<sizeof($array['document_tone']['tone_categories'][0]['tones']);$i++)
      // {
      //    echo "$array['document_tone']['tone_categories'][0]['tones'][i]['tone_name'] &nbsp;&nbsp;&nbsp;";
      //    echo "$array['document_tone']['tone_categories'][0]['tones'][i]['tone_name']<br/>";
      // }
      foreach ($array['document_tone']['tone_categories'][0]['tones'] as $vals) 
      {
         echo $vals['tone_name'];
         echo " ------> ";
         echo $vals['score'];
         echo "<br/>";
         // echo "$vals['tone_name'] ";
         // echo "$vals['score']<br/>";
      }

      $angerVal = $array['document_tone']['tone_categories'][0]['tones'][0]['score'];
      $disgustVal = $array['document_tone']['tone_categories'][0]['tones'][1]['score'];
      $fearVal = $array['document_tone']['tone_categories'][0]['tones'][2]['score'];
      $joyVal = $array['document_tone']['tone_categories'][0]['tones'][3]['score'];
      $sadnessVal = $array['document_tone']['tone_categories'][0]['tones'][4]['score'];

      // echo "$angerVal<br/>$disgustVal<br/>$fearVal<br/>$joyVal<br/>$sadnessVal";

      if($angerVal <= 0.5 && $disgustVal <= 0.5 && $fearVal <= 0.5 && $sadnessVal <= 0.5)
      {
          $bulk = new MongoDB\Driver\BulkWrite;
      
          $doc = ["_id" => new MongoDB\BSON\ObjectID, "headline" => $headlineData, "content" => $contentData, "extraInfo" => $extraInfoData];
          $bulk->insert($doc);

          $mng->executeBulkWrite('articles.timesOfIndiaData', $bulk);
      }
      // var_dump(json_decode($result, true));
      // echo "<h1> $result </h1>";
      // var_dump($result);
      
      echo "<h1>-----------------------------------------------------------------------------------------------------------------------</h1><br/>";
      $data = "";
    }
?>