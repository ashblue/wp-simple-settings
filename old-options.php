<?php
/*
Title: Simple WordPress Settings API Script
Version: .04
Author: Ash Blue Web Design
Author URL: http://www.ashbluewebdesign.com/
Repository URL: Coming Soon
- Needs to all be converted to OOP
*/

/***********************
Set Page Basics
***********************/
// add the admin option pages
add_action('admin_menu', 'admin_add_page');
function admin_add_page() {
	add_theme_page('Footer Options', 'Footer Options', 'create_users', 'footer_config', 'footer_options_page');
	add_theme_page('Home Options', 'Home Options', 'create_users', 'home_config', 'home_options_page');
}


// display the admin options page
function footer_options_page() { ?>
	<div>
		<h2>Footer Options</h2>
		<p>Configure various settings for the footer</p>
		<form id="optionSubmit" action="options.php" method="post">
			<?php settings_fields('footer_options'); ?>
			<?php do_settings_sections('__FILE__'); ?>

			<p class="submit"><input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" /></p>
		</form>
	</div>
<?php }

function home_options_page() { ?>
	<div>
		<h2>Home Options</h2>
		<p>Custom the home page's information</p>
		<form id="optionSubmit" action="options.php" method="post">
			<?php settings_fields('home_options'); ?>
			<?php do_settings_sections('home_config'); ?>

			<p class="submit"><input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" /></p>
		</form>
	</div>
<?php }



/***********************
Setup inputs
***********************/
// Register settings and boxes
add_action('admin_init', 'admin_settings');
function admin_settings(){
	//Footer options
	register_setting('footer_options', 'footer_options', 'validate_all');
	// Social Media 
	add_settings_section('social_media', 'Social Media Widget', 'social_media_text', '__FILE__');
	// goes id, title, function for input, file slug, section
	add_settings_field('email_url', 'Email URL', 'email_url_string', '__FILE__', 'social_media');
	add_settings_field('facebook_url', 'Facebook URL', 'facebook_url_string', '__FILE__', 'social_media');
	add_settings_field('twitter_url', 'Twitter URL', 'twitter_url_string', '__FILE__', 'social_media');
	add_settings_field('rss_url', 'RSS URL', 'rss_url_string', '__FILE__', 'social_media');
        add_settings_field('linkedin_url', 'LinkedIn URL', 'linkedin_url_string', '__FILE__', 'social_media');
        add_settings_field('yelp_url', 'Yelp URL', 'yelp_url_string', '__FILE__', 'social_media');
        add_settings_field('youtube_url', 'YouTube URL', 'youtube_url_string', '__FILE__', 'social_media');
        add_settings_field('vimeo_url', 'Vimeo URL', 'vimeo_url_string', '__FILE__', 'social_media');
	// Contact
	add_settings_section('contact', 'Contact Widget', 'contact_text', '__FILE__');
	add_settings_field('contact_time', 'Hours of Operation', 'time_string', '__FILE__', 'contact');
	add_settings_field('contact_email', 'Email', 'email_string', '__FILE__', 'contact');
	add_settings_field('contact_phone', 'Phone', 'phone_string', '__FILE__', 'contact');
        add_settings_field('contact_add1', 'Address Line 1', 'add1_string', '__FILE__', 'contact');
	add_settings_field('contact_add2', 'Address Line 2', 'add2_string', '__FILE__', 'contact');
	// About
	add_settings_section('about', 'About Widget', 'about_text', '__FILE__');
	add_settings_field('about_desc', 'Description', 'desc_textarea', '__FILE__', 'about');
	// Hosting
	add_settings_section('hosting', 'Hosting Link', 'hosting_text', '__FILE__');
	add_settings_field('host_url', 'URL', 'host_string', '__FILE__', 'hosting');
	
	// Home options
	register_setting('home_options', 'home_options', 'validate_all');
	add_settings_section('feat', 'Featured Widget', 'feat_text', 'home_config');
        add_settings_field('feat_title', 'Title', 'feat_title_string', 'home_config', 'feat');
        add_settings_field('feat_url', 'URL', 'feat_url_string', 'home_config', 'feat');
        add_settings_field('feat_img', 'Image URL', 'feat_img_string', 'home_config', 'feat');
        add_settings_field('feat_desc', 'Desc', 'feat_desc_textarea', 'home_config', 'feat');
}


// Section text
function social_media_text() {
	echo '<p>Add URLs to activate your social media buttons.</p>';
}
function contact_text() {
	echo '<p>Configure your contact details.</p>';
}
function about_text() {
	echo '<p>SEO opportunity below with the about section.</p>';
}
function hosting_text() {
	echo '<p>Host URL configuration.</p>';
}
function feat_text() {
	echo '<p>Configure the featured box on the home page.</p>';
}


// Inputs and boxes
// Footer
// Social Media
function email_url_string() { input_text('footer_options','email_url','emailUrl','url'); }
function facebook_url_string() { input_text('footer_options','facebook_url','facebookUrl','url'); }
function twitter_url_string() { input_text('footer_options','twitter_url','twitterUrl','url'); }
function linkedin_url_string() { input_text('footer_options','linkedin_url','linkedinUrl','url'); }
function rss_url_string() { input_text('footer_options','rss_url','rssUrl','url'); }
function yelp_url_string() { input_text('footer_options','yelp_url','yelpUrl','url'); }
function youtube_url_string() { input_text('footer_options','youtube_url','youtubeUrl','url'); }
function vimeo_url_string() { input_text('footer_options','vimeo_url','vimeoUrl','url'); }
// Contact
function time_string() { input_text('footer_options','time_contact','timeContact','',30,false,'30 characters or less.'); }
function email_string() { input_text('footer_options','email_contact','emailContact','email',30,false,'30 characters or less.'); }
function phone_string() { input_text('footer_options','phone_contact','phoneContact','phoneUS',false,false,'XXX-XXX-XXXX'); }
function add1_string() { input_text('footer_options','add1_contact','add1Contact','',30,false,'30 characters or less.'); }
function add2_string() { input_text('footer_options','add2_contact','add2Contact','',30,false,'30 characters or less.'); }
// About
function desc_textarea() { input_textarea('footer_options','desc_about','descAbout','',120,false,'120 characters or less.'); }
// Hosting
function host_string() { input_text('footer_options','host_url','hostURL','url'); }

// Home
// Featured box
function feat_title_string() { input_text('home_options','feat_title','featTitle','required',25,false,'25 letters or less'); }
function feat_url_string() { input_text('home_options','feat_url','featUrl','required url'); }
function feat_img_string() { input_text('home_options','feat_img','featImg','required urlImg',false,false,'300 x 169px'); }
function feat_desc_textarea() { input_textarea('home_options','feat_desc','featDesc','required',100,false,'100 characters or less.'); }




/***********************
Inputs / Boxes / Selection
***********************/
function length_string($min,$max) {
	if ($min_length && $max_length) {
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

function input_text($options,$key,$id,$class = '',$max_length = false,$min_length = false,$desc = false) {
	$options_array = get_option($options);
	$length = length_string($min_length,$max_length);
	
	echo '<input class="' . $class . '" id="' . $id . '"' . $length . ' name="' . $options . '[' . $key . ']" size="40" type="text" value="' . $options_array[$key] . '" />';
        if ($desc) echo '<p class="desc">' . $desc . '</p>';
}

function input_textarea($options,$key,$id,$class = '',$max_length = false,$min_length = false,$desc = false) {
	$options_array = get_option($options);
	$length = length_string($min_length,$max_length);
	
	echo '<textarea class="' . $class .'" id="' . $id . '"' . $length . ' name="' . $options . '[' . $key . ']" rows="7" cols="50" type="textarea">' . $options_array[$key] . '</textarea>';
        if ($desc) echo '<p class="desc">' . $desc . '</p>';
}




/***********************
Validation
***********************/
function validate_all($input) {
	$input['email_url'] = validate_url($input,'email_url');
	$input['facebook_url'] = validate_url($input,'facebook_url');
	$input['twitter_url'] = validate_url($input,'twitter_url');
	$input['linkedin_url'] = validate_url($input,'linkedin_url');
	$input['rss_url'] = validate_url($input,'rss_url');
        $input['yelp_url'] = validate_url($input,'yelp_url');
        $input['youtube_url'] = validate_url($input,'youtube_url');
        $input['vimeo_url'] = validate_url($input,'vimeo_url');
	
	$input['time_contact'] = validate_length(30,$input,'time_contact');
	$input['email_contact'] = validate_length(30,$input,'email_contact');
	$input['phone_contact'] = validate_phone($input,'phone_contact');
	$input['add1_contact'] = validate_length(30,$input,'add1_contact');
	$input['add2_contact'] = validate_length(30,$input,'add2_contact');
	
	$input['desc_about'] = validate_length(450,$input,'desc_about');
	
	$input['host_url'] = validate_url($input,'host_url');
	
	$input['feat_url'] = validate_url($input,'feat_url');
        $input['feat_img'] = validate_url_img($input,'feat_img');
	return $input;
}

// URL validation
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

// URL Image Validation
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

// Length validation
function validate_length($length,$options,$key) {
	$text = $options[$key];
	$text = trim($text);
	if ((strlen($text)) > $length) {
		return substr($text,0,$length);
	}
	else {
		return $text;
	}
}

// Email validation
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

// Phone validation
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

?>