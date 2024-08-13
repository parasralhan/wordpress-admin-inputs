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
   * @return string
   * */
  protected function _build_input() {

    ob_start();
    ?>
    <div class="bonzer-inputs input-wrapper upload-input-wapper" data-showif='<?php echo $this->_conditional_data(); ?>'>

      <?php $this->_label(); ?>

      <div>
        <input 
          type="text" class="upload input" 
          id="<?php echo $this->_id; ?>" 
          name="<?php echo $this->_name; ?>" 
          value="<?php echo $this->_value; ?>" 
          placeholder="<?php echo $this->_placeholder; ?>" 
          readonly 
          <?php echo $this->_additional_attrs; ?> 
        />

        <button 
          title="<?php echo __('Upload', 'wordpress-admin-inputs')?>" 
          class="upload button upload_image_button" 
          type="button" 
          data-title="Choose" 
          data-update="Insert"
        >
          <i class="fa fa-upload"></i>&nbsp;
          <span class="text">Upload</span>
        </button>
        
        <div class="images-wrapper">

          <?php if ( ! empty( $this->_value ) ) { ?>

            <div class="image-wrapper">
              <img src="<?php echo home_url() . $this->_value ?>" class="img-responsive img-circle">
              <i class="fa fa-times remove-img" data-image="<?php echo $this->_value; ?>" id="image-1"></i>
            </div>    

          <?php } ?>

          <div class="clear"></div>
        </div>
      </div>

    </div>

    <?php
    return ob_get_clean();
  }

}
