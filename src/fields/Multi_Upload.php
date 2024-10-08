<?php
namespace Bonzer\Inputs_WP\fields;

use Bonzer\Inputs\contracts\Input_Abstract;

class Multi_Upload extends Input_Abstract {

  public function __construct( $args ) {

    parent::__construct( $args );
  }
  /**
    * --------------------------------------------------------------------------
    * Build Multi Upload input
    * --------------------------------------------------------------------------
    *
    * @return string
   * */
  protected function _build_input() {

    ob_start();
    ?>
    <div 
      class="bonzer-inputs input-wrapper multi-upload-input-wapper" 
      data-showif='<?php echo $this->_conditional_data(); ?>'
    >

      <?php $this->_label(); ?>

      <div>

        <?php
        $value = ! empty( $this->_value ) ? implode( ',', array_unique( explode( ',', $this->_value ) ) ) : '';
        ?>
        
        <input 
          type="text" 
          class="upload input" 
          id="<?php echo $this->_id; ?>" 
          name="<?php echo $this->_name; ?>" 
          value="<?php echo $value; ?>" 
          placeholder="<?php echo $this->_placeholder; ?>" 
          readonly 
          <?php echo $this->_additional_attrs; ?> 
        />
        
        <button 
          title="<?php echo __('Add Images', 'wordpress-admin-inputs');?>" 
          class="upload button upload-multiple-images" 
          type="button" 
          data-title="Choose" 
          data-update="Insert"
        >
          <i class="fa fa-plus-circle"></i>&nbsp;
          <span class="text">Add</span> 
        </button>
        
        <div class="images-wrapper">

          <?php
          if ( ! empty( $this->_value ) ) {

            $all_images = array_unique( explode( ',', $this->_value ) );

            if ( is_array( $all_images ) && count( $all_images ) > 0 ) {

              $index = 0;

              foreach ( $all_images as $id ) {

                $src = wp_get_attachment_image_src( $id );

                ?>
                <div class="image-wrapper" data-id="<?php echo $id; ?>">
                  <img src="<?php echo $src[0]; ?>" class="img-responsive img-circle">
                  <i class="fa fa-times remove-img" data-image="<?php echo $id ?>" id="image-<?php echo $id; ?>"></i>
                  <i class="fa fa-arrows move-img" data-image="<?php echo $id ?>" id="image-<?php echo $id; ?>"></i>
                </div>

                <?php

                $index++;
              }
            }
          }
          ?>

          <div class="clear"></div>
        </div>   
      </div>
      
    </div>

    <?php
    return ob_get_clean();
  }
}
