<?php

namespace Bonzer\Inputs_WP\fields;

use Bonzer\Inputs\contracts\Input_Abstract;

class Editor extends Input_Abstract {
  
  protected $_editor_config;

  public function __construct( $args ) {
    parent::__construct( $args );
    if(isset($args['attrs'])){
      $this->_editor_config = $args['editor_config'];
    } else {
      $this->_editor_config = [];
    }
  }

  /**
    | --------------------------------------------------------------------------
    | Build Text input
    | --------------------------------------------------------------------------
    |
    | @Return: html
   * */
  protected function _build_input() {
    ob_start();
    ?>
    <div class="input-wrapper editor-input-wrapper" data-showif='<?php echo $this->_conditional_data(); ?>'>
      <label for="<?php echo $this->_id; ?>"><?php echo $this->_label; ?></label>
      <?php
      wp_editor( $this->_value, $this->_id, array_merge([
        'tinymce' => TRUE,
        'textarea_name' => $this->_name,
        'textarea_rows' => 10,
      ], $this->_editor_config) );
      echo (isset_not_empty( $this->_desc )) ? "<p class='desc'>{$this->_desc}</p>" : '';
      ?>
    </div>
    <?php
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;
  }

}
