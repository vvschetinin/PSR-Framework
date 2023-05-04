<?php 

function print_arr($arr){ 
  echo '<pre>' . print_r($arr, true) . '</pre>';
}

function damp_arr($arr){
  echo  '<pre>';
  var_dump($arr);
  echo  '</pre>';
}

?>