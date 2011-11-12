<?php
/*
Title: Simple WordPress Settings API Script
Version: .04
Author: Ash Blue
Author URL: http://twitter.com/#!/AshBlueWD
Repository URL: https://github.com/ashblue/wp-simple-settings
- Needs to all be converted to OOP
*/

/***********************
Options Script
***********************/
class Page {
    var $title = 'Options';
    var $permission = 'create_users';
    var $slug = 'options_config';
    var $details = 'Configure additional settings for your WordPress theme.';
    
    // Runs page setup at a specific time in WordPress
    function setup() {
        add_action('admin_menu', array($this, 'add_page'));
        add_action('admin_init', array($this, 'input_setup'));
    }
    
    // Configuration of page details
    function add_page() {
        add_theme_page($this->title, $this->title, $this->permission, $this->slug, array($this, 'page_details'));
    }
    function page_details() { ?>
        <div>
            <h2><?php echo $this->title; ?></h2>
            <p>Configure various settings for the footer</p>
            <form id="optionSubmit" action="options.php" method="post">
                <?php settings_fields('page_options'); ?>
                <?php do_settings_sections('page_config'); ?>

                <p class="submit"><input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" /></p>
            </form>
        </div>
    <?php }
    
    // Array setup for inputs
    var $input_info = array();
    function input_array() {
        
        $this->input_info[] = array(
            'id' => '',
            'title' => '',
            'desc' => '',
            
            'inputs' => array(
                array(
                    'id' => '',
                    'title' => '',
                    'type' => ''
                ),
            )
        );
        
    }
    // Run input setup
    function input_setup() {
        register_setting('page_options', 'page_options', array($this, 'validate'));
        
        // Settings section
            // id
            // title
            // description that points to a function with an echo
            // page to point to page_config
        // Individual inputs
            // id
            // title
            // callback that points at input
            // page to point at
            // attached section
    }
    // Inputs
    function length_string() {
        
    }
    function input_text() {
        
    }
    function input_textarea() {
        
    }
    
    
    // Validation processing
    function validate() {
        
    }
    // Validation functions
}


/***********************
Your Settings
***********************/
$page = new Page();
$page->setup();

?>