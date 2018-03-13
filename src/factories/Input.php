<?php
namespace Bonzer\Inputs_WP\factories;
/**
 * 
 * Inputs Factory
 * 
 * @package bonzer/wordpress-admin-inputs    
 * @author  Paras Ralhan <ralhan.paras@gmail.com>
 */
use Bonzer\Inputs\contracts\interfaces\Assets_Loader as Assets_Loader_Interface,
    Bonzer\Inputs\contracts\interfaces\Configurer as Configurer_Interface;

use Bonzer\Inputs_WP\Assets_Loader as WP_Assets_Loader;

class Input extends \Bonzer\Inputs\factories\Input {

  /**
   * @var Input
   */
  private static $_instance;

  /**
   * @var array
   */
  protected $_additional_types = [
    'upload',
    'multi-upload',
    'editor',
  ];

  /**
   * --------------------------------------------------------------------------
   * Class Constructor
   * --------------------------------------------------------------------------
   * 
   * @param Assets_Loader_Interface $assets_loader
   * @param Configurer_Interface $configurer
   * 
   * @Return Input 
   * */
  protected function __construct( Assets_Loader_Interface $assets_loader = NULL, Configurer_Interface $configurer = NULL ) {
    $this->_valid_types = array_merge( $this->_valid_types, $this->_additional_types );
    $assets_loader = $assets_loader ?: WP_Assets_Loader::get_instance();
    parent::__construct( $assets_loader, $configurer );
  }

  /**
   * --------------------------------------------------------------------------
   * Class Constructor
   * --------------------------------------------------------------------------
   * 
   * @param Assets_Loader_Interface $assets_loader
   * @param Configurer_Interface $configurer
   * 
   * @Return Input 
   * */
  public static function get_instance( Assets_Loader_Interface $assets_loader = NULL, Configurer_Interface $configurer = NULL ) {
    if ( static::$_instance ) {
      return static::$_instance;
    }
    return static::$_instance = new static( $assets_loader, $configurer );
  }
  
    /**
   * --------------------------------------------------------------------------
   * Input field Namespace
   * --------------------------------------------------------------------------
   * 
   * @param string $type
   * 
   * @Return string 
   * */
  protected function _get_namespace( $type = NULL ) {
    if ( in_array( $type, $this->_additional_types )) {
      return "\\Bonzer\\Inputs_WP\\fields\\";
    }
    return parent::_get_namespace($type);
  }

}
