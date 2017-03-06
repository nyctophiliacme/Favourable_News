<?php
  require_once('simple_html_dom.php');
  $url = 'http://www.thehindu.com/opinion/editorial/moonlit-reality/article17378488.ece';

  $html = file_get_html($url);
  libxml_use_internal_errors(TRUE);
  libxml_clear_errors();
  // echo $html;
  // $elements = $html->find('div');
  
  //Code working fine...Twitter tweet getiing displayed :(
  

  // $elements = $html->getElementById("content-body-14269002-17334506");
  // echo $elements;
  //........................................................................
  // $elems = array();
  // $elems = $html->find("div#content-body-14269002-17334506 p");
  // echo $elems->plainText;

  //..............................................................

  $title = $html->find("h1.title");
  foreach($title as $titleitr)
  {
     echo $titleitr;
  }
 $elems = $html->find("div#content-body-(.*?) p,div#content-body-(.*?) div.live-snippet-border");

  $containers = $html->find("div.ut-container");
  foreach($containers as $container)
  {
     echo $container;
  }
 // echo $elems[0];
  $intros = $html->find("h2.intro");
  foreach ($intros as $intro) 
  {
     echo $intro;
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
     }
  }
     
     // foreach($elem->find("div") as $bad)
     // {
     //    echo $bad;
     // }
     // echo $elem;
     // echo $elem->getAttribute('class');
  // echo $elems;
  /*
  try
  {
    echo $elems;
  }
  catch(Exception $e)
  {
     echo 'Caught exception: ',  $e->getMessage(), "\n";
  }

  //...............................................................  
//   */
// for($i=0; $i<sizeof($elems);$i++) {
//   echo $elems[$i];
// }




//........................................................
// $dom = new DOMDocument;
// $dom->loadHTML($html);
// $xp = new DOMXPath($dom);

// $interest = $xp->query('//div[@id="content-body-14266949-17338994"]');
// //img/@src

// // echo $interest;
// foreach($interest as $a)
// { 
//     echo $a->nodeValue;
//     // print $a->nodeValue." - ".$a->getAttribute("href")."<br/>";
// }

//...............................................................





// foreach ($elems as $elem) {
//   echo $elem->find()->src;
// }
  /*
   foreach($elems as $elem)
  {
      // if($elem->class=="_hoverrDone") break;
      // if($elem->class == "live-snippet-border") break;
      // if($elem->tag=="p") break;
      // echo $elem->plaintext;
      // foreach($elem->find('a') as $achorTagElem) 
      //    echo "<h1>$anchorTagElem->href</h1>";
     echo $elem;
  }*/

?>