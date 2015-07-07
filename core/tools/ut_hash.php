<?php

  require_once "hash.php";

  $h = new One_Tools_Hash();
  $h->set('apple','green');
  echo $h->get('apple');

  echo '<hr>';

//  $h = new One_Tools_Hash();
  $h->set('apple.color','green');
  print_r($h);
  $h->set('apple.type','fruit');
  print_r($h);
  echo $h->get('apple');
