( function ($) {
  "use strcit";
  var Inputs = {
    upload_input_init: function ( $wrapper ) {
      function _toggle_has_images( $wrapper ) {
        if ($wrapper.find( '.image-wrapper' ).length) {
          $wrapper.removeClass( 'no-images' ).addClass( 'has-images' );
        } else {
          $wrapper.removeClass( 'has-images' ).addClass( 'no-images' );
        }
      }
      if ($wrapper) {
        _toggle_has_images( $wrapper );
      } else {
        $( '.upload-input-wapper, .multi-upload-input-wapper' ).each( function () {
          var $this = $( this );
          _toggle_has_images( $this );
        } );
      }
    },
    /*==========================================================
     * Handle Images Removal | Multi-Upload & Upload Input Fields
     *==========================================================*/
    handle_images_removal: function ( ) {
      $( document.body ).on( "click", ".images-wrapper i.fa.remove-img", function ( e ) {
        var $this = $( this ),
                src = $this.data( "image" ),
                value, value_array, image_index,
                image_id = $this.attr( 'id' ),
                $input_wrapper = $this.parents( ".input-wrapper" ),
                image_numeric_id = $this.parents('.image-wrapper').attr( 'data-id' ),
                $input = $input_wrapper.find( ".input" );

        (function change_input_value() {
          value = $input.val();
          if ((typeof value) === 'string') {
            value_array = value.split( "," );
          } else {
            value_array = value;
          }
          image_index = value_array.indexOf( image_numeric_id );
          value_array.splice( image_index, 1 );
          $input.val( value_array.toString() ).change();
        }());

        // Shortcodes Hack
        if ($this.parents( "form.shortcode-form" ).length) {
          var target = $this.parents( "form.shortcode-form" ).data( "shortcode" );
          $( ".shortcode-forms" ).find( "li." + target ).find( "form" ).find( ".input-wrapper" ).find( "i#" + image_id ).parent( ".image-wrapper" ).remove();
        } else {
          $this.parents( ".input-wrapper" ).find( "i#" + image_id ).parent( ".image-wrapper" ).remove()
        }
        // Remove Image Wrapper
        $this.parent( ".image-wrapper" ).remove();
        e.preventDefault();
        e.stopPropagation();
        
        setTimeout( function () {
          $.event.trigger( {
            type: 'bonzer_inputs.upload.image_removed',
            $wrapper: $input_wrapper
          } );
        }, 10 );
      } );
    },
    handle_images_movement: (function () {
      var move_images = function () {
        $( ".images-wrapper" ).sortable( {
          handle: ".move-img",
          containment: 'parent',
          placeholder: "sortable-placeholder",
          stop: function ( event, ui ) {
            var sorted_ids = [];
            $( ui.item ).parents( '.images-wrapper' ).find( '.image-wrapper' ).each( function () {
              sorted_ids.push( $( this ).attr( 'data-id' ) );
            } );
            $( ui.item ).parents( '.input-wrapper.multi-upload-input-wapper' ).find( '.input' ).val( sorted_ids ).change();
          }
        } );
      };
      return function () {
        $( document.body ).on( 'mouseenter', '.images-wrapper', function ( e ) {
          move_images();
          e.stopPropagation();
        } );
      }
    }()),
    /*==========================================================
     * MULTIPLE MEDIA UPLOADER
     *==========================================================*/
    multiple_images_uploader: (function ( $ ) {
      var upload_multiple_images = function () {
        // Instantiates the variable that holds the media library frame.
        var meta_image_frame;

        $( document.body ).on( "click", '.bonzer-inputs.multi-upload-input-wapper .upload-multiple-images', function ( e ) {
          var $this = $( e.currentTarget ),
                  $wrapper = $this.parents( '.multi-upload-input-wapper' ),
                  $images_wrapper = $wrapper.find( ".images-wrapper" ),
                  where_to_paste_ids = $wrapper.find( '.input' );

          e.preventDefault();
          e.stopPropagation();
          // Sets up the media library frame
          meta_image_frame = wp.media.frames.meta_image_frame = wp.media( {
            title: $this.data( 'title' ),
            button: {
              text: $this.data( 'update' )
            },
            library: {
              type: 'image'
            },
            multiple: true,
          } );
          // Runs when an image is selected.
          meta_image_frame.on( 'select', function () {
            // Grabs the attachment selection and creates a JSON representation of the model.
            var media_attachment = meta_image_frame.state().get( 'selection' ).first().toJSON(),
                    files = meta_image_frame.state().get( 'selection' ).toJSON(),
                    length = meta_image_frame.state().get( "selection" ).length,
                    images_ids = where_to_paste_ids.val() ? where_to_paste_ids.val().split( ',' ) : [],
                    $img, $image_wrapper, $images_wrapper, $cross, $move,
                    $wrapper = $this.parents( ".multi-upload-input-wapper" ),
                    target_form = $this.parents( "form.shortcode-form" ).attr( "id" ),
                    target_input = $wrapper.find( ".input" ).attr( "id" );
            $images_wrapper = $wrapper.find( ".images-wrapper" );
            if ($this.parents( "form.shortcode-form" ).length) {
              $wrapper = $( "." + target_form ).find( ".input#" + target_input ).parents( ".multi-upload-input-wapper" );
              $images_wrapper = $wrapper.find( ".images-wrapper" );
            }
            $.each( files, function ( i ) {
              if ($.inArray( "" + this.id + "", images_ids ) > -1) {
                return;
              }
              images_ids.unshift( this.id );
              $image_wrapper = $( "<div></div>", {
                'class': 'image-wrapper',
                'data-id': this.id,
              } ).prependTo( $images_wrapper );
              $img = $( "<img>", {
                'src': this.url,
                'class': 'img-responsive img-circle',
              } ).appendTo( $image_wrapper );
              $cross = $( "<i></i>", {
                'class': 'fa fa-times remove-img',
                'data-image': this.url,
                'id': "image-" + this.id,
              } ).appendTo( $image_wrapper );
              $move = $( "<i></i>", {
                'class': 'fa fa-arrows move-img',
                'data-image': this.url,
              } ).appendTo( $image_wrapper );
            } );
            // Sends the attachment id to our custom image input field.
            where_to_paste_ids.val( images_ids ).change();  
            setTimeout( function () {
              $.event.trigger( {
                type: 'bonzer_inputs.upload.image_added',
                $wrapper: $wrapper
              } );
            }, 10 );
          } );
          // Opens the media library frame.
          meta_image_frame.open();
        } );

      };
      return function () {
        upload_multiple_images();
      }
    }( jQuery )),
    /*==========================================================
     * MEDIA UPLOADER -- Single Image
     *==========================================================*/
    media_uploader: (function ( $ ) {
      var upload_single_image = function () {
        // Instantiates the variable that holds the media library frame.
        var meta_image_frame;

        $( document ).on( "click", '.upload_image_button', function ( e ) {
          var $this = $( e.currentTarget ),
              where_to_paste_url = $this.parents( '.upload-input-wapper' ).find( '.input' );

          e.preventDefault();
          e.stopPropagation();

          // Sets up the media library frame
          meta_image_frame = wp.media.frames.meta_image_frame = wp.media( {
            title: $this.data( 'title' ),
            button: {
              text: $this.data( 'update' )
            },
            library: {
              type: 'image'
            }
          } );

          // Runs when an image is selected.
          meta_image_frame.on( 'select', function () {
            var $images_wrapper, $img, $image_wrapper, $cross;
            if ($this.parents( "form.shortcode-form" ).length) {
              var target_form = $this.parents( "form" ).attr( "id" );
              var target_input = $this.parents( ".upload-input-wapper" ).find( ".input" ).attr( "id" );
              $images_wrapper = $( "." + target_form ).find( ".input#" + target_input ).parents( ".upload-input-wapper" ).find( ".images-wrapper" );
            } else {
              $images_wrapper = $this.parents( ".upload-input-wapper" ).find( ".images-wrapper" );
            }
            if ($images_wrapper.find( '.image-wrapper' ).length) {
              $images_wrapper.find( '.image-wrapper' ).remove();
            }
            $image_wrapper = $( "<div></div>", {
              'class': 'image-wrapper'
            } ).prependTo( $images_wrapper );
            // Grabs the attachment selection and creates a JSON representation of the model.
            var media_attachment = meta_image_frame.state().get( 'selection' ).first().toJSON();
            $img = $( "<img>", {
              'src': media_attachment.url,
              'class': 'img-responsive img-circle',
            } ).appendTo( $image_wrapper );
            $cross = $( "<i></i>", {
              'class': 'fa fa-times remove-img',
              'data-image': media_attachment.url,
              'id': "image-1"
            } ).appendTo( $image_wrapper );
            // Sends the attachment URL to our custom image input field.
            if (media_attachment.url) {
              where_to_paste_url.val( media_attachment.url.replace(bonzer_inputs.base_url, '') ).change();
            }            
            setTimeout(function(){
              $.event.trigger( {
                type: 'bonzer_inputs.upload.image_added',
                $wrapper: $images_wrapper.parents( '.upload-input-wapper' )
              } );
            }, 10 );
          } );
          // Opens the media library frame.
          meta_image_frame.open();
          
        } );
      };

      return function () {
        upload_single_image();
      }
    }( jQuery )),
  };

  Inputs.upload_input_init();
  Inputs.handle_images_removal();
  Inputs.handle_images_movement();
  Inputs.media_uploader();
  Inputs.multiple_images_uploader();

  (function handle_images_events() {
    ['bonzer_inputs.upload.image_added', 'bonzer_inputs.upload.image_removed'].forEach( function ( event_name ) {
      $( document ).on( event_name, function ( e ) {
        Inputs.upload_input_init( e.$wrapper );
      } );
    } );
  }());

  $.extend(bonzer_inputs, Inputs);

}(jQuery) );


