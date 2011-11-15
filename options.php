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
    var $desc = 'Place your description here.';
    var $submit = 'Save Changes';
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
            <?php if ($this->desc) echo '<p>' . $this->desc . '</p>'; ?>
            <form id="optionSubmit" action="options.php" method="post">
                <?php settings_fields($this->slug); ?>
                <?php do_settings_sections('page_config'); ?>

                <p class="submit"><input name="Submit" type="submit" value="<?php esc_attr_e($this->submit); ?>" /></p>
            </form>
        </div>
    <?php }
    
    // Array setup for inputs
    var $input_info = array();
    function input_array() {
        $this->input_info[] = array(
            'id' => 'test',
            'title' => 'Input Section',
            'desc' => 'This is a test input section.',
            
            'inputs' => array(
                array(
                    'id' => 'test',
                    'title' => 'Text Input',
                    'type' => 'text'
                ),
            )
        );
        
        $this->input_info[] = array(
            'id' => 'test',
            'title' => 'Input Section',
            'desc' => 'This is a test input section.',
            
            'inputs' => array(
                array(
                    'id' => 'test',
                    'title' => 'Text Input',
                    'type' => 'text'
                ),
            )
        );
    }
    // Run input setup
    function input_setup() {
        register_setting($this->slug, $this->slug, array($this, 'validate'));
        
        foreach ( $this->input_info as $section ) {
            echo $section['id'];
            foreach ( $section['inputs'] as $input ) {
                echo $input['id'];
            }
        }
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
    function length() {
        
    }
    function text() {
        
    }
    function textarea() {
        
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