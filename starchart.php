<?php

// 10y = 10px

include('objects.php');
include('regions.php');

if($_GET['output'] == 'JSON') {
  print json_encode($objects);
  die();
}

/*
function cmp($a, $b) {
  if ($a['z'] == $b['z']) {
    return 0;
  }
  return ($a['z'] < $b['z']) ? -1 : 1;
}

usort($objects, "cmp");
*/

foreach ($objects as $object) {
  if (!isset($lowest_x)) {
    $lowest_x = $object['x'];
  }
  if (!isset($lowest_y)) {
    $lowest_y = $object['y'];
  }
  if (!isset($highest_x)) {
    $highest_x = $object['x'];
  }

  if (!isset($highest_y)) {
    $highest_y = $object['y'];
  }

  if ($object['x'] < $lowest_x) {
    $lowest_x = $object['x'];
  }
  if ($object['y'] < $lowest_y) {
    $lowest_y = $object['y'];
  }
  if ($object['x'] > $highest_x) {
    $highest_x = $object['x'];
  }
  if ($object['y'] > $highest_y) {
    $highest_y = $object['y'];
  }
}
#print $lowest_x . ' ';
#print $lowest_y . ' ';
#print $highest_x . ' ';
#print $highest_y . ' ';

$range_x = $highest_x - $lowest_x;
#print $range_x . ' ';

$range_y = $highest_y - $lowest_y;
#print $range_y . ' ';

$framewidth = 100;

$offset_x = $lowest_x - $framewidth;
$offset_y = $lowest_y - $framewidth;


header("Content-Type: image/svg+xml");
echo '<?xml version="1.0" encoding="iso-8859-1"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">';

echo '<svg xmlns="http://www.w3.org/2000/svg" height="' . ($range_y + (2 * $framewidth)) . '" width="' . ($range_x + (2 * $framewidth)) . '" style="background-color:black" xmlns:xlink="http://www.w3.org/1999/xlink" class="' . $_GET['mode'] . '" >';

echo '<defs>
        <pattern id="grid" patternUnits="userSpaceOnUse" x="-14" y="-22" width="50" height="50">
          <rect width="50" height="50" style="stroke:white; fill:none; opacity:.5"/>
        </pattern>
        <radialGradient id="federation" cx="0.5" cy="0.5" r="0.4">
                <stop stop-color="#00009E" stop-opacity="1" offset="0" />
                <stop stop-color="#00009E" stop-opacity="0" offset="1" />
        </radialGradient>
        <radialGradient id="neutral" cx="0.5" cy="0.5" r="0.4">
                <stop stop-color="#464646" stop-opacity=".7" offset="0" />
                <stop stop-color="#464646" stop-opacity="0" offset="1" />
        </radialGradient>
        <radialGradient id="romulan" cx="0.5" cy="0.5" r="0.4">
                <stop stop-color="green" stop-opacity=".7" offset="0" />
                <stop stop-color="green" stop-opacity="0" offset="1" />
        </radialGradient>
        <radialGradient id="klingon" cx="0.5" cy="0.5" r="0.4">
                <stop stop-color="red" stop-opacity=".7" offset="0" />
                <stop stop-color="red" stop-opacity="0" offset="1" />
        </radialGradient>
        <radialGradient id="gorn" cx="0.5" cy="0.5" r="0.4">
                <stop stop-color="brown" stop-opacity=".7" offset="0" />
                <stop stop-color="brown" stop-opacity="0" offset="1" />
        </radialGradient>
        <radialGradient id="cardassian" cx="0.5" cy="0.5" r="0.4">
                <stop stop-color="orange" stop-opacity=".7" offset="0" />
                <stop stop-color="orange" stop-opacity="0" offset="1" />
        </radialGradient>
        <radialGradient id="white-star-gradient" cx="0.5" cy="0.5" r="0.4">
                <stop stop-color="white" stop-opacity="1" offset="0" />
                <stop stop-color="white" stop-opacity="0" offset="1" />
        </radialGradient>
        <filter id="blur">
          <feGaussianBlur in="SourceGraphic" stdDeviation="1.5"/>
        </filter>
        <style type="text/css">
         <![CDATA[
         .label {font-family: sans-serif;}
         .stsc .sto-only{filter:url(#blur)}
         .sto .stsc-only{filter:url(#blur)}
         .non-canon{filter:url(#blur)}
         .sto .sto-only.non-canon{filter:none}
         .region > .label{fill:white;font-size:18px; text-align:center;text-anchor:middle;opacity:.3; text-transform:uppercase;}
         .object > .label{fill:white;font-size:10px;}
         .object > .label.left-side{text-align:right;text-anchor:end;}
         .object > .label.bottom{text-align:center;text-anchor:middle;}
         .object > .label.top{text-align:center;text-anchor:middle;}
         .region.sector .outline{stroke:#6BF;stroke-width:2; opacity:.3; fill:url(#grid);}
         
         .region.cluster .outline{stroke:none;fill:none}
         .region.cluster .label{font-size:13px;}
         
         .region.sector-block .outline{stroke:#0269B3;stroke-width:1; fill:none}
         .region.nebula .outline{stroke:none;fill:none}
         .region.sector-block > .label{text-align:left;text-anchor:start; font-size:14px; opacity:1; fill:#0269B3 }
         ]]>
       </style>
</defs>';

echo '<rect x="' . $framewidth . '" y="' . $framewidth . '" width="' . $range_x . '" height="' . $range_y . '"  stroke="red" />';


function get_center_of_region($region = array()) {
  $x = array();
  $y = array();
  foreach($region['coordinates'] as $pair) {
    $x[] = $pair['x'];
    $y[] = $pair['y'];
  }
  return array(
      'x' => (max($x) + min($x)) / 2, 
      'y' => (max($y) + min($y)) / 2
      );
}

function get_upper_left_of_region($region = array()) {
  $x = array();
  $y = array();
  foreach($region['coordinates'] as $pair) {
    $x[] = $pair['x'];
    $y[] = $pair['y'];
  }
  return array(
      'x' => min($x), 
      'y' => min($y)
      );
}
/*
echo '<g id="territorys">';
foreach ($objects as $object) {
  if ($object['fraction'] != NULL) {
    echo '<circle cx="' . ($object['x'] - $offset_x) . '" cy="' . ($object['y'] - $offset_y) . '" r="120"  fill="url(#' . $object['fraction'] . ')" />';
  }
}
echo '</g>';
*/

echo '<g id="regions">';
foreach($regions as $region) {
  
  $class = 'region';
  if($region['sto'] && !$region['stsc']) {
    $class .= ' sto-only';
  } elseif (!$region['sto'] && $region['stsc']) {
    $class .= ' stsc-only';
  }
  
  $class .= ' ' . $region['type'];
  
  $output = '<g class="' . $class . '" title="' . $region['name'] . '">';
  $output .= '<polygon class="outline" points="';
  foreach($region['coordinates'] as $coordinate) {
    $output .= ($coordinate['x'] - $offset_x) . ',' . ($coordinate['y'] - $offset_y) . ' ';
  }
  $output .= '"/>';
  switch($region['type']) {
    case 'sector-block':
      $label_position = get_upper_left_of_region($region);
      $label_position['y'] = $label_position['y'] + 16;
      $label_position['x'] = $label_position['X'] + 8;
      break;
    default:
      $label_position = get_center_of_region($region);
  }
  $output .= '<text class="label" x="' . ($label_position['x'] - $offset_x) . '" y="' . ($label_position['y'] - $offset_y) . '" >' . $region['name'] . '</text>';
  $output .= '</g>';

  echo $output;
}
echo '</g>';

for ($i = 1; $i <= 1000; $i++) {
#  echo '<circle cx="' . (rand($offset_x,$range_x) - $offset_x) . '" cy="' . (rand($offset_y,$range_y) - $offset_y) . '" r="1.5" fill="white" opacity=".3" />';
}

foreach ($routes as $route) {
  $output = '<path d="M';
  $i = 1;
  foreach($route['stages'] as $stage) {
    if($i > 1) {
      $output .= 'L';
    }
    $output .= ($objects[$stage]['x'] - $offset_x) . ',' . ($objects[$stage]['y'] - $offset_y) . ' ';
    $i++;
    $last_stage = $stage;
  }
  $output .= '" style="stroke:#0269B3;stroke-width:2; fill:none" />';
  echo $output;
}

echo '<g id="objects">';

foreach ($objects as $object) {
  
  $class = 'object';
  if($object['sto'] && !$object['stsc']) {
    $class .= ' sto-only';
  } elseif (!$object['sto'] && $object['stsc']) {
    $class .= ' stsc-only';
  }
  
  if(isset($object['canon']) && $object['canon'] == FALSE) {
    $class .= ' non-canon';
  } 
  
  echo '<a class="' . $class . '" title="' . $object['name'] . '" xlink:href="' . $object['ma/de'] . '">';
  echo '<circle cx="' . ($object['x'] - $offset_x) . '" cy="' . ($object['y'] - $offset_y) . '" r="2" fill="white" />';

  $text = $object['name'];
  $class = 'label';
  $label_offset_y = 3;
  $label_offset_x = 5;
  if($object['label-left']) {
    $label_offset_x = -5;
    $class .= ' left-side';
  } elseif ($object['label-bottom']) {
    $label_offset_x = 0;
    $class .= ' bottom';
    $label_offset_y = 12;
  } elseif ($object['label-top']) {
    $label_offset_x = 0;
    $class .= ' top';
    $label_offset_y = -6;
  }
  $element = '<text x="' . ($object['x'] - $offset_x + $label_offset_x) . '" y="' . ($object['y'] - $offset_y + $label_offset_y) . '"  class="' . $class . '">' . $text . '</text>';

  echo $element;
  echo '</a>';
}
echo '</g>';
?>

<symbol  id="white-star" viewBox="-15 -15 30 30">
<ellipse cx="0" cy="0" rx="30" ry="1" style="fill:url(#white-star-gradient);stroke:none;"/>
<ellipse cx="0" cy="0" rx="1" ry="30" style="fill:url(#white-star-gradient);stroke:none;"/>
<circle  cx="0" cy="0" r="10" style="fill:url(#white-star-gradient);stroke:none;opacity:.75" />
</symbol>

<?php

$star_cunt = 1000;
for($cunt = 1; $cunt < $star_cunt; $cunt++) {
  #echo '<use xlink:href="#white-star"  width="15" height="15" x="' . rand(0, $range_x + (2 * $framewidth)) . '" y="' . rand(0, $range_y + (2 * $framewidth)) . '" overflow="visible"/>';
}


echo '</svg>';
