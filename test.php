<?php

$sections = array(
  'mission' => array(
    '#title' => 'Mission',
    '#headline' => 'Grüner wird´s nicht',
    '#intro' => 'Der Wandel des Strommarktes zu einer wirklich erneuerbaren Versorgung in Deutschland ist möglich. Und Grundgrün treibt ihn aktiv voran – durch die direkte Vermarktung von Strom aus Wind- und Solarenergie.',
  ),
  'menschen' => array(
    '#title' => 'Menschen',
    '#headline' => 'Ein starkes Team für ein großes Ziel',
    '#intro' => 'Vom Gründer über die Mitarbeiter bis hin zu den Anteilseignern: Bei Grundgrün ist jeder Einzelne mit viel Engagement bei der Sache – und mit einer großen Portion Idealismus.',
  ),
  'geschaeftsmodell' => array(
    '#title' => 'Gesch&auml;fts&shy;modell',
    '#headline' => 'Wir machen den Energiewandel einfach',
    '#intro' => 'Grundgrün wird beweisen, dass es möglich ist, Endkunden direkt mit erneuerbarer Energie zu beliefern. Und zwar zu attraktiven Preisen und mit wirtschaftlichem Erfolg.',
    ),
  'direktvermarktung' => array(
    '#title' => 'Direkt&shy;vermarktung ',
    '#headline' => 'Überzeugende Vorteile für Erzeuger',
    '#intro' => 'Wer sich frühzeitig für unsere Direktvermarktung entscheidet, profitiert nachhaltig davon, bleibt konkurrenzfähig und stärkt langfristig seine Position auf dem Energiemarkt.',
  ),
);

function theme_sections($sections) {
  $output = '';
  foreach(element_children($sections) as $key) {
    $output .= '<section id="' . $key . '" style="'; 
    $output .= isset($sections[$key]['#bgcolor']) ? 'background-color:' . $sections[$key]['#bgcolor'] . ';' : '';
    $output .= isset($sections[$key]['#textcolor']) ? 'color:' . $sections[$key]['#textcolor'] . ';' : '';
    $output .= '">';  
    $output .= '<header><h2>' . $sections[$key]['#headline'] . '</h2></header>';
    $output .= '<div class="section-inner">';
    $output .= '<p class="intro"><strong>' . $sections[$key]['#intro'] . '</strong></p>';
    foreach(element_children($sections[$key]) as $key2) {
      $output .= '<div class="col">';
      foreach(element_children($sections[$key][$key2]) as $key3) {
        $attributes = '';        
        $type = isset($sections[$key][$key2][$key3]['#type']) ? $sections[$key][$key2][$key3]['#type'] : 'p';
        
        if($type == 'section') {
          $attributes .= 'class="highlight" style="';
          $attributes .= isset($sections[$key]['#highlightbgcolor']) ? 'background-color:' . $sections[$key]['#highlightbgcolor'] . ';' : '';
          $attributes .= isset($sections[$key]['#highlighttextcolor']) ? 'color:' . $sections[$key]['#highlighttextcolor'] . ';' : '';
          $attributes .= '"';
        }
        
        $output .= "<$type $attributes>" . $sections[$key][$key2][$key3]['text'] . "</$type>";
      }
      $output .= '</div>';    
    }
    $output .= '</div></section>';
  }
  return $output;
}

function element_children(&$elements, $sort = FALSE) {
  // Do not attempt to sort elements which have already been sorted.
  $sort = isset($elements['#sorted']) ? !$elements['#sorted'] : $sort;

  // Filter out properties from the element, leaving only children.
  $children = array();
  $sortable = FALSE;
  foreach ($elements as $key => $value) {
    if ($key === '' || $key[0] !== '#') {
      $children[$key] = $value;
      if (is_array($value) && isset($value['#weight'])) {
        $sortable = TRUE;
      }
    }
  }
  // Sort the children if necessary.
  if ($sort && $sortable) {
    uasort($children, 'element_sort');
    // Put the sorted children back into $elements in the correct order, to
    // preserve sorting if the same element is passed through
    // element_children() twice.
    foreach ($children as $key => $child) {
      unset($elements[$key]);
      $elements[$key] = $child;
    }
    $elements['#sorted'] = TRUE;
  }

  return array_keys($children);
}

?>
<!doctype html>
<!--[if lt IE 8]> <html class="no-js ie oldschool" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie ie8" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Foo</title>
  
  </head>

<body>
  <?php print theme_sections($sections); ?> 
</bod>
</html>