<?php
  // Class created
  class Custom_meta{
    // Constructor created
    public function __construct(){
      // For calling function custom metabox
      add_action("add_meta_boxes",[$this,"cd_meta_box_add"]);
      //add_action("save_post",[$this,"save_detail"]);
    }
    // Function for metabox creation
    function cd_meta_box_add(){
      add_meta_box( 'my-meta-box-id', 'My First Meta Box', [$this,'cd_meta_box_cb'], 'post', 'normal', 'high' );
    }
    // fuction for the contnets in metabox
    function cd_meta_box_cb()
    {
        // $post is already set, and contains an object: the WordPress post
        global $post;
        $values = get_post_custom( $post->ID );
        $text = isset( $values['my_meta_box_text'] ) ? $values['my_meta_box_text'] : '';
        $check = isset( $values['my_meta_box_check'] ) ? esc_attr( $values['my_meta_box_check'] ) : '';

        // We'll use this nonce field later on when saving.
        wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
        ?>
        <p>
            <label for="my_meta_box_text">Text Label</label>
            <input type="text" name="my_meta_box_text" id="my_meta_box_text" value="<?php echo $text; ?>" />
        </p>

        <p>
            <input type="checkbox" id="my_meta_box_check" name="my_meta_box_check" <?php checked( $check, 'on' ); ?> />
            <label for="my_meta_box_check">Do not check this</label>
        </p>
        <?php
    }
    //Function for saving details

  }
  new Custom_meta();
?>
