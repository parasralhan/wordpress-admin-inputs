<?php

namespace Bonzer\Inputs_WP\fields;

use Bonzer\Inputs\contracts\Input_Abstract;

class Editor extends Input_Abstract {
  
  protected $_editor_config;

  public function __construct( $args ) {

    parent::__construct( $args );

    if ( isset( $args['attrs'] ) ) {
      $this->_editor_config = $args['editor_config'];
    } else {
      $this->_editor_config = [];
    }
  }

  /**
    * --------------------------------------------------------------------------
    * Build Editor input
    * --------------------------------------------------------------------------
    *
    * @return string
   * */
  protected function _build_input() {

    ob_start();
    ?>
    <div 
      class="bonzer-inputs input-wrapper editor-input-wrapper" 
      data-showif='<?php echo $this->_conditional_data(); ?>'
    >

      <?php $this->_label(); ?>

      <div>
        <?php

        wp_editor( $this->_value, $this->_id, array_merge( [
          'tinymce' => TRUE,
          'textarea_name' => $this->_name,
          'textarea_rows' => 10,
        ], $this->_editor_config ) );      

        ?>
      </div>

    </div>

    <?php
    return ob_get_clean();
  }

}
