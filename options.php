<?php
/*
Title: Simple WordPress Settings API Script
Version: Pre-Alpha
Author: Ash Blue
Author URL: http://blueashes.com
Repository URL: https://github.com/ashblue/wp-simple-settings
- Needs to all be converted to OOP
- page_config needs to have a dynamic variable
- add in a css file to partially gray out input descriptions
*/

/***********************
Options Script
***********************/
class Page {
    /**********
    Core Setup
    **********/
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
    // Configuration of page's basic details
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
    
    
    /**********
    Input Handlers
    **********/
    // Array setup for inputs
    var $input_info = array();
    function input_array() {}
    
    // Run input setup
    function input_setup() {
        
        // Run the user's created array so its transfered from a function, into a variable
        $this->input_array();
        
        // Default registration for subsequent settings
        register_setting($this->slug, $this->slug, array($this, 'validate'));
        
        // Counter variables
        $this->counterDesc = 0;
        $this->counterInput = 0;
        
        // Begin looping through all array elements to create inputs
        foreach ( $this->input_info as $section ) {
    
            // Text must be passed here to prevent conflicts with passing the function name as a string
            $this->sec_text[] = $section['desc'];
    
            add_settings_section($section['id'], $section['title'], array($this, 'sec_desc'), 'page_config');
            
            foreach ( $section['inputs'] as $input ) {
                $this->input_min[] = $input['length_min'];
                $this->input_max[] = $input['length_max'];
                $this->input_id[] = $input['id'];
                $this->input_class[] = $input['class'];
                $this->input_desc[] = $input['desc'];
                add_settings_field($input['id'], $input['title'], array($this, $input['type']), 'page_config', $section['id']);
            }
        }
        
    }
    // Handles outputting text, must be seperate because of how the settings API takes function strings for text callbacks
    function sec_desc() {
        // Needs to take an array
        // Each time the function runs it should grab the next array value via counter
            // Reason being this function is run after all the other settings have run
                // Therefore it inherits the last run value if you don't run an array
        echo $this->sec_text[$this->counterDesc];
        $this->counterDesc += 1; 
    }
    
    
    /**********
    Input functions
    **********/
    // Utilities
    function length_string($min,$max) {
        if ($min && $max) {
		$length = ' minlength="' . $min . '" maxlength="' . $max . '"';
	}
	elseif ($max) {
		$length = ' maxlength="' . $max . '"';
	}
	elseif ($min) {
		$length = ' minlength="' . $min . '"';
	}
	return $length;
    }
    
    // Input creation
    function text() {
        // Get necessary options parameters and store them in a  variable
        $options_array = get_option($this->slug);
        
        // To control min and max length, check values here
	if ($this->input_min[$this->counterInput] || $this->input_max[$this->counterInput]) $length = $this->length_string($this->input_min[$this->counterInput], $this->input_max[$this->counterInput]);
        else $length = null;
	
        // Output input
	echo '<input class="' . $this->input_class[$this->counterInput] . '" id="' . $this->input_id[$this->counterInput] . '" ' . $length . ' name="' . $this->slug . '[' . $this->input_id[$this->counterInput] . ']" size="40" type="text" value="' . $options_array[$this->input_id[$this->counterInput]] . '" />';
        if ($this->input_desc[$this->counterInput]) echo '<p class="desc">' . $this->input_desc[$this->counterInput] . '</p>';
        
        $this->counterInput += 1;
    }
    function textarea() {
        
    }
    function radio() {
        
    }
    function checkbox() {
        
    }
    function dropdown() {
        
    }
    
    /**********
    Validation
    **********/
    function validate($input) {
        // input validation goes here
        // Example: $input['email_url'] = validate_url($input,'email_url');
            // Looop through a valid array
            // If not empty for current item
                // Inject key and run validation function
        
        // Send back the modified results
        return $input;
    }
    
    // Validation functions
}


/***********************
Your Settings
***********************/
class My_settings extends Page {
    function input_array() {
        $this->input_info[] = array(
            // Create your settings section
            'id' => 'test',
            'title' => 'Input Section',
            'desc' => 'This is a test input section 1.',
            'inputs' => array(
                // Create as many different inputs here as you want
                array(
                    'id' => 'test',
                    'class' => 'name',
                    'title' => 'Text Input',
                    'type' => 'text',
                    'length_min' => 5,
                    'length_max' => 25,
                    'desc' => 'test'
                ),
            )
        );
        
        $this->input_info[] = array(
            'id' => 'test2',
            'title' => 'Input Section 2',
            'desc' => 'This is a test input section 2.',
            
            'inputs' => array(
                array(
                    'id' => 'test3',
                    'title' => 'Text Input 2',
                    'type' => 'text'
                ),
            )
        );
    }
}
$page = new My_settings();
$page->setup();

?>