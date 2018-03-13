<?php

namespace Bonzer\Inputs_WP;

/**
 * Library Assets Loader
 * 
 * 
 * @package bonzer/inputs    
 * @author  Paras Ralhan <ralhan.paras@gmail.com>
 */
use Bonzer\Inputs\contracts\interfaces\Configurer as Configurer_Interface,
    Bonzer\Events\contracts\interfaces\Event as Event_Interface;

class Assets_Loader extends \Bonzer\Inputs\Assets_Loader implements \Bonzer\Inputs_WP\contracts\interfaces\Assets_Loader {

  /**
   * @var Assets_Loader
   */
  private static $_instance;
  
  protected $_wp_assets_dir;

  /**
   * --------------------------------------------------------------------------
   * Class Constructor
   * --------------------------------------------------------------------------
   * 
   * @param Configurer_Interface $configurer
   * @param Event_Interface $event
   * 
   * @Return Assets_Loader 
   * */
  protected function __construct( Configurer_Interface $configurer = NULL, Event_Interface $event = NULL ) {
    parent::__construct( $configurer, $event );
    $this->_wp_assets_dir = __DIR__ .'/assets';
    if ( $this->_Configurer->get_env() == 'development' ) {  
      $this->_complie_less($this->_wp_assets_dir.'/less/styles.less', $this->_wp_assets_dir.'/css/styles.css');
    }
  }

  /**
   * --------------------------------------------------------------------------
   * Class Constructor
   * --------------------------------------------------------------------------
   * 
   * @param Configurer_Interface $configurer
   * @param Event_Interface $event
   * 
   * @Return Assets_Loader 
   * */
  public static function get_instance( Configurer_Interface $configurer = NULL, Event_Interface $event = NULL ) {
    if ( static::$_instance ) {
      return static::$_instance;
    }
    return static::$_instance = new static( $configurer, $event );
  }

  /**
   * --------------------------------------------------------------------------
   * Head Code Fragment
   * --------------------------------------------------------------------------
   * 
   * @Return Assets_Loader 
   * */
  public function load_head_fragment() {
    $this->_Event->listen( 'inputs_css_end', function () {
      echo file_get_contents( $this->_wp_assets_dir . '/css/styles.css' );
    } );
    return parent::load_head_fragment();
  }

  /**
   * --------------------------------------------------------------------------
   * Code fragment to be inserted just before the body tag closes
   * --------------------------------------------------------------------------
   * 
   * @Return Assets_Loader
   * */
  public function load_before_body_close_fragment() {
    $this->_Event->listen( 'inputs_js_end', function () {
      echo file_get_contents( $this->_wp_assets_dir . '/js/main.js' );
    } );
    return parent::load_before_body_close_fragment();
  }

}
