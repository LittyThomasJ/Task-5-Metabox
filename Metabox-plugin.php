<?php
  /**
  * Plugin Name:       My Basics Plugin for adding Topic
  * Plugin URI:        https://topics/
  * Description:       Handle the basics with this plugin for topics.
  * Version:           1.10.3
  * Requires at least: 5.2
  * Requires PHP:      7.2
  * Author:            Litty Thomas
  * Author URI:        https://topics.com/
  * Text Domain:       my-basics-plugin-topics
  * Domain Path:       /languages
  */
  // Class created
  class Custom_meta{
    // Constructor created
    public function __construct(){
      // For calling function custom metabox
      add_action("add_meta_boxes", array($this,"add_custom_meta_box"));
      // For calling save_custom_meta_box
      add_action("save_post", array($this,"save_custom_meta_box"));
      // For calling displayfield
      add_filter('the_content',array($this,'display_front_end'));
    }
    // Function for metabox creation
    function add_custom_meta_box(){
      // Adding metabox
        add_meta_box("demo-meta-box", "Add the Topic", array($this,"custom_meta_box_markup"), "post", "side", "high", null);
    }
    function custom_meta_box_markup($object){
      // Creating wp_nonce_field to validate that the contents of the form
      wp_nonce_field(basename(__FILE__), "meta-box-nonce");
      ?>
      <!-- The contents within Custom metabox -->
      <div>
        <label for="meta-box-text">Topic Name</label>
        <!-- value of the input is fetched using get_post_meta -->
        <input name="meta-box-text" type="text" value="<?php echo esc_html(get_post_meta($object->ID, "_meta-box-text", true)); ?>">
        <br>
        <!-- For check box -->
        <?php
        // Fetching values to a variable checkbox_value using get_post_meta
        $checkbox_value = get_post_meta($object->ID, "_meta-box-checkbox", true);
        // Condition checked for checkbox, whether checked or not,for display of tick
        if($checkbox_value == ""){
          // if not checked
          ?>
          <input name="meta-box-checkbox" type="checkbox" value="true">
          <?php
        }
        else if($checkbox_value == "true"){
          // if checked
          ?>
          <input name="meta-box-checkbox" type="checkbox" value="true" checked>
          <?php
        }
        ?>
        <label for="meta-box-checkbox">Save</label>
      </div>
      <?php
    }
    // for saving contents of metabox
    function save_custom_meta_box($post_id){
      //write_log('stringfff');
      // For verifying using wp_verify_nonce, Verifies that a correct security nonce was used with time limit.
      if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;
      // To check the user have the capability to edit
      if(!current_user_can("edit_post", $post_id))
        return $post_id;
      // aborting the logic that is to follow beneath the condition, if doing autosave = true
      if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;
      $meta_box_text_value = "";
      $meta_box_checkbox_value = "";
      // checking for the condition if meta-box-text is posted
      if(isset($_POST["meta-box-text"])){
        //Sanitized data is fetched to a variable which is posted
        $meta_box_text_value = sanitize_text_field($_POST["meta-box-text"]);
      }
      // Updates a post meta field based on the given post ID.
      update_post_meta($post_id, "_meta-box-text", $meta_box_text_value);
      // checking for the condition if meta-box-checkbox is posted
      if(isset($_POST["meta-box-checkbox"])){
          //Sanitized data is fetched to a variable which is posted
          $meta_box_checkbox_value = sanitize_text_field($_POST["meta-box-checkbox"]);
      }
      // Updates a post meta field based on the given post ID.
      update_post_meta($post_id, "_meta-box-checkbox", $meta_box_checkbox_value);
    }
    // Function for displaying Fields in front end
    function display_front_end($val){
      global $post;
      $test="";
      //write_log('df');
      // Retrieves a post meta field for the given post ID.
      $checkbox_val = get_post_meta($post->ID, "_meta-box-checkbox", true);
      // check whether checkbox is checked or not
      if($checkbox_val == "true"){
        // Retrieves a post meta field for the given post ID.
        $cont = get_post_meta($post->ID, '_meta-box-text', true);
        // Content which is displayed
        $test = "<div>Topic : $cont </div>";
      }
      // value returned for displaying
      return $val . $test;
    }
  }
  // creating an instance of class
  new Custom_meta();
  
?>
