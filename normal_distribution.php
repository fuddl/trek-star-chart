<?php

function array_distribute($mean,$sd,$min,$max){
    $result = array();
    $total_mean = intval($mean*$sd);
    while($sd>1){
        $allowed_max = $total_mean - $sd - $min;
        $allowed_min = intval($total_mean/$sd);
        $random = mt_rand(max($min,$allowed_min),min($max,$allowed_max));
        $result[]=$random;
        $sd--;
        $total_mean-=$random;
    }
    $result[] = $total_mean;
    return $result;
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


header("Content-Type: image/svg+xml");
echo '<?xml version="1.0" encoding="iso-8859-1"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">';
 
echo '<svg xmlns="http://www.w3.org/2000/svg" height="600" width="600" xmlns:xlink="http://www.w3.org/1999/xlink" style="background-color:black" >';


$x = array_distribute(300,1000,100,400);
$y = array_distribute(300,1000,100,400);
$alpha = array_distribute(.5,1000,.1,1);

foreach(element_children($y) as $key) {
  echo '<circle cx="' . $x[$key] . '" cy="' . $y[$key] . '" r="2" fill="white"/>';
}

echo '</svg>';
