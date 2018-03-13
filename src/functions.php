<?php 
use Bonzer\IOC_Container\facades\Container as IOC_Container;

if ( !function_exists( 'load_bonzer_inputs_wp_container_bindings' ) ) {
  function load_bonzer_inputs_wp_container_bindings() {
    $bindings = require 'container-bindings.php';
    foreach ( $bindings as $key => $value ) {
      IOC_Container::bind( $key, $value );
    }
  }
}
//load_bonzer_inputs_wp_container_bindings();
//echo '<pre>';
//print_r(IOC_Container::bindings());
//echo '</pre>';