<?php

namespace Bonzer\Inputs_WP;

use Bonzer\Inputs\contracts\interfaces\Configurer as Configurer_Interface;
use Bonzer\Events\contracts\interfaces\Event as Event_Interface;

/**
 * Library Assets Loader
 * 
 * @package bonzer/inputs    
 * @author  Paras Ralhan <ralhan.paras@gmail.com>
 */
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
   * @return Assets_Loader 
   */
  protected function __construct( Configurer_Interface $configurer = null, Event_Interface $event = null ) {

    parent::__construct( $configurer, $event );
    
    $this->_wp_assets_dir = __DIR__ . '/assets';

    if ( $this->_Configurer->get_env() == 'development' ) {  

      $this->_complie_less( 
        $this->_wp_assets_dir . '/less/styles.less', 
        $this->_wp_assets_dir . '/css/styles.css' 
      );

    }
  }

  /**
   * --------------------------------------------------------------------------
   * Get Instance
   * --------------------------------------------------------------------------
   * 
   * @param Configurer_Interface $configurer
   * @param Event_Interface $event
   * 
   * @return Assets_Loader 
   */
  public static function get_instance( Configurer_Interface $configurer = null, Event_Interface $event = null ) {

    if ( static::$_instance ) {
      return static::$_instance;
    }

    return static::$_instance = new static( $configurer, $event );
  }

  /**
   * --------------------------------------------------------------------------
   * Load Head Code Fragment
   * --------------------------------------------------------------------------
   * 
   * @param array $replacements
   * 
   * @return Assets_Loader 
   */
  public function load_head_fragment( array $replacements = null ) {

    wp_enqueue_media();

    $this->_Event->listen( 'inputs_css_end', function () {
      echo file_get_contents( $this->_wp_assets_dir . '/css/styles.css' );
    });

    return parent::load_head_fragment( $replacements );
  }

  /**
   * --------------------------------------------------------------------------
   * Load Code Fragment Before Body Close
   * --------------------------------------------------------------------------
   * 
   * @param array $replacements
   * 
   * @return Assets_Loader
   */
  public function load_before_body_close_fragment( array $replacements = null ) {    
    
    $this->_Event->listen( 'inputs_js_end', function () {      
      echo file_get_contents( $this->_wp_assets_dir . '/js/main.js' );
    });

    return parent::load_before_body_close_fragment( $replacements );
  }
}
