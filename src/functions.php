<?php 
use Bonzer\IOC_Container\facades\Container as IOC_Container;

function load_bonzer_inputs_wp_container_bindings() {

  $bindings = require 'container-bindings.php';

  foreach ( $bindings as $key => $value ) {
    IOC_Container::bind( $key, $value );
  }
}

