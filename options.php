<?php
/*
Title: Simple WordPress Settings API Script
Version: Pre-Alpha
Author: Ash Blue
Author URL: http://blueashes.com
Repository URL: https://github.com/ashblue/wp-simple-settings
- page_config needs to have a dynamic variable
- add in a css file to partially gray out input descriptions
- needs some good default settings for inputs
- seperate file for creating your objects (should have a built in tutorial)
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
    var $details = 'Configure additional settings for your WordPress theme.';
    
    // Runs page setup at a specific time in WordPress
    function setup() {
        $this->slug_setup();
        add_action('admin_menu', array($this, 'add_page'));
        add_action('admin_init', array($this, 'input_setup'));
    }
    // Setup slug based upon title
    function slug_setup() {
        $this->slug = strtolower($this->title);
        $this->slug = str_replace(' ', '_', $this->slug);
        $this->slug = $this->slug . '_config';
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
                $this->input_valid[] = $input['validate'];
                $this->input_list[] = $input['list'];
                $this->input_place[] = $input['placeholder'];
                add_settings_field($input['id'], $input['title'], array($this, $input['type']), 'page_config', $section['id']);
            }
        }
        
    }
    // Handles outputting text, must be seperate because of how the settings API takes function strings for text callbacks
    function sec_desc() {
        // Needs to take an array
        // Each time the function runs it should grab the next array value via counter
            // Reason being this function is run after all the other setup has run
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
    function input_desc($desc) {
        if ($desc):
            echo '<p class="desc">' . $desc . '</p>';
        endif;
    }
    
    // Input creation
    function text() {
        // Get necessary options parameters and store them in a  variable
        $options_array = get_option($this->slug);
        
        // To control min and max length, check values here
        $length = $this->length_string($this->input_min[$this->counterInput], $this->input_max[$this->counterInput]);
	
        // Display input
        echo '<input class="' . $this->input_class[$this->counterInput] . '" id="' . $this->input_id[$this->counterInput] . '" ' . $length . ' name="' . $this->slug . '[' . $this->input_id[$this->counterInput] . ']" size="40" type="text" placeholder="' . $this->input_place[$this->counterInput] . '" value="' . $options_array[$this->input_id[$this->counterInput]] . '" />';
        input_desc($this->input_desc[$this->counterInput]);
        
        // Increment mandatory to prevent loop from reusing previous values
        $this->counterInput += 1;
    }
    function textarea() {
        $options_array = get_option($this->slug);
        $length = $this->length_string($this->input_min[$this->counterInput], $this->input_max[$this->counterInput]);	

        echo '<textarea class="' . $this->input_class[$this->counterInput] . '" id="' . $this->input_id[$this->counterInput] . '" ' . $length . ' name="' . $this->slug . '[' . $this->input_id[$this->counterInput] . ']" rows="7" cols="50" placeholder="' . $this->input_place[$this->counterInput] . '" type="textarea">' . $options_array[$this->input_id[$this->counterInput]] . '</textarea>';
        input_desc($this->input_desc[$this->counterInput]);

        $this->counterInput += 1;
    }
    function radio() {
        $options_array = get_option($this->slug);
        
        // <input type="radio" name="post_format" class="post-format" id="post-format-aside" value="aside" checked="checked"> <label for="post-format-aside">Aside</label>
        // <br>
        // <input class="radio' . $field_class . '" type="radio" name="mytheme_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';

        // Output header of list
        echo '<div class="' . $this->input_class[$this->counterInput] . '" id="' . $this->input_id[$this->counterInput] . '">';
        
            // Explode input_list into an array
            $list = explode(', ', $this->input_list[$this->counterInput]);
            $count = 0;
            // Pump out list items
            foreach($list as $choice):
                echo '<input type="radio" name="' . $this->slug . '[' . $this->input_id[$this->counterInput] . ']" class="' . $this->input_class[$this->counterInput] . $count . '" id="' . $this->input_id[$this->counterInput] . $count . '" value="#" checked=""' . '>';
                echo '<label for="' . $this->input_id[$this->counterInput] . $count . '">' . $choice . '</label>';
                echo '<br/>';
                $count++;
            endforeach;
            
        // Output end of list
        echo '</div>';
        
        input_desc($this->input_desc[$this->counterInput]);

        $this->counterInput += 1;
    }
    function checkbox() {
        
    }
    function dropdown() {
        
    }
    
    /**********
    Validation
    **********/
    function validate($input) {
        
        // Loop through all inputs and check for validation functions
        // You can easily add your own validation functions in custom objects
        $counter = 0;
        foreach ( $this->input_valid[$counter] as $valid ) {
            if ($valid):
                // http://stackoverflow.com/questions/1005857/how-to-call-php-function-from-string-stored-in-a-variable
                $input[$this->input_id[$counter]] = $valid($input, $this->input_id[$counter]);
            endif;
        }
        
        // Send back the modified results
        return $input;
    
    }
    
    // Validation functions
    function validate_url($options,$key) {
        $url = $options[$key];
        $url = trim($url);
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }
        else {
            return '';
        }
    }
    function validate_url_img($options,$key) {
        $url = $options[$key];
        $url = trim($url);
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            if (preg_match ('/(?i)\.(jpg|png|gif)$/',$url)) {
                return $url;
            }
            else {
                return '';
            }
        }
        else {
            return '';
        }
    }
    function validate_email($options,$key) {
        $email = $options[$key];
        $email = trim($email);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }
        else {
            return '';
        }
    }
    function validate_phone($options,$key) {
        $phone = $options[$key];
        $phone = trim($phone);
        if (preg_match ('/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/',$phone)) {
            return $phone;
        }
        else {
            return '';
        }
    }
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
                    'desc' => 'test',
                    'validate' =>
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