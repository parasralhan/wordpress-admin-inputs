<?php

namespace Bonzer\Inputs_WP\fields;

use Bonzer\Inputs\contracts\Input_Abstract;
class Upload extends Input_Abstract {

  public function __construct( $args ) {
    parent::__construct( $args );
  }

  /**
   * --------------------------------------------------------------------------
   * Build Upload input
   * --------------------------------------------------------------------------
   *
   * @Return: html
   * */
  protected function _build_input() {
    ob_start();
    ?>
    <div class="input-wrapper upload-input-wapper" data-showif='<?php echo $this->_conditional_data(); ?>'>
      <label for="<?php echo $this->_id; ?>"><?php echo $this->_label; ?></label>
      <input type="text" class="upload input" id="<?php echo $this->_id; ?>" name="<?php echo $this->_name; ?>" value="<?php echo $this->_value; ?>" placeholder="<?php echo $this->_placeholder; ?>" readonly <?php echo $this->_additional_attrs; ?>>
      <button title="<?php echo __('Upload', 'wordpress-admin-inputs')?>" class="upload button upload_image_button" type="button" data-title="Choose" data-update="Insert"><i class="fa fa-upload"></i> <span class="text">Upload</span></button>

      <!-- Images Wrapper -->
      <div class="images-wrapper">
        <?php if ( isset_not_empty( $this->_value ) ) { ?>
          <div class="image-wrapper">
            <img src="<?php echo SITE_BASE_URL . $this->_value ?>" class="img-responsive img-circle">
            <i class="fa fa-times remove-img" data-image="<?php echo $this->_value; ?>" id="image-1"></i>
          </div>        
        <?php } ?>
        <div class="clear"></div>
      </div>

      <?php
      // description
      echo (isset_not_empty( $this->_desc )) ? "<p class='desc'>{$this->_desc}</p>" : '';
      ?>
    </div>
    <?php
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;
  }

}
