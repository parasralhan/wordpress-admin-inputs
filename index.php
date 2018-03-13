<?php
require 'vendor/autoload.php';
use Bonzer\Inputs\config\Configurer;
use Bonzer\IOC_Container\facades\Container;
 Configurer::get_instance([
   'env' => 'development',
   'style' => '3',
 ]);
//Container::make('Bonzer\Inputs\config\Configurer', [[
//  'env' => 'development',
//  'style' => '3',
//]]);
$Input = Container::make('Bonzer\Inputs_WP\factories\Input');

echo $Input->create('upload', [
  'id' => 'my-upload-input'
]);
echo $Input->create( 'multi-upload', [
  'id' => 'my-multi-upload-input'
] );

