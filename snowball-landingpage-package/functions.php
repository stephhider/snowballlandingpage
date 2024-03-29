<?php
function reverie_setup() {
	// Add language supports. Please note that Reverie Framework does not include language files.
	load_theme_textdomain('reverie', get_template_directory() . '/lang');
	
	// Add post thumbnail supports. http://codex.wordpress.org/Post_Thumbnails
	add_theme_support('post-thumbnails');
	// set_post_thumbnail_size(150, 150, false);
	
	// Add post formarts supports. http://codex.wordpress.org/Post_Formats
	add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));
	
	// Add menu supports. http://codex.wordpress.org/Function_Reference/register_nav_menus
	add_theme_support('menus');
	register_nav_menus(array(
		'primary_navigation' => __('Primary Navigation', 'reverie'),
		'utility_navigation' => __('Utility Navigation', 'reverie')
	));	
}


add_action('after_setup_theme', 'reverie_setup');

// Enqueue for header and footer, thanks to flickapix on Github.
// Enqueue css files
function reverie_css() {
  if ( !is_admin() ) {
  
     wp_register_style( 'foundation',get_template_directory_uri() . '/css/foundation.css', false );
     wp_enqueue_style( 'foundation' );
    
     wp_register_style( 'app',get_template_directory_uri() . '/css/app.css', false );
     wp_enqueue_style( 'app' );
     
     // Load style.css to allow contents overwrite foundation & app css
     wp_register_style( 'style',get_template_directory_uri() . '/style.css', false );
     wp_enqueue_style( 'style' );
     
     wp_register_style( 'google_font',"http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,300", false );
     wp_enqueue_style( 'google_font' );
     
  }
}  
add_action( 'init', 'reverie_css' );

function reverie_ie_css () {
    echo '<!--[if lt IE 9]>';
    echo '<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie.css">';
    echo '<![endif]-->';
}
add_action( 'wp_head', 'reverie_ie_css' );

// Enqueue js files
function reverie_scripts() {

global $is_IE;

  if ( !is_admin() ) {
  
  // Enqueue to header
     wp_deregister_script( 'jquery' );
     wp_register_script( 'jquery', get_template_directory_uri() . '/js/jquery.min.js' );
     wp_enqueue_script( 'jquery' );
     
     wp_register_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.foundation.js', array( 'jquery' ) );
     wp_enqueue_script( 'modernizr' );
 
  // Enqueue to footer
     wp_register_script( 'reveal', get_template_directory_uri() . '/js/jquery.reveal.js', array( 'jquery' ), false, true );
     wp_enqueue_script( 'reveal' );
     
     wp_register_script( 'orbit', get_template_directory_uri() . '/js/jquery.orbit-1.4.0.js', array( 'jquery' ), false, true );
     wp_enqueue_script( 'orbit' );
     
     wp_register_script( 'custom_forms', get_template_directory_uri() . '/js/jquery.customforms.js', array( 'jquery' ), false, true );
     wp_enqueue_script( 'custom_forms' );
     
     wp_register_script( 'placeholder', get_template_directory_uri() . '/js/jquery.placeholder.min.js', array( 'jquery' ), false, true );
     wp_enqueue_script( 'placeholder' );
     
     wp_register_script( 'tooltips', get_template_directory_uri() . '/js/jquery.tooltips.js', array( 'jquery' ), false, true );
     wp_enqueue_script( 'tooltips' );
     
     wp_register_script( 'app', get_template_directory_uri() . '/js/app.js', array( 'jquery' ), false, true );
     wp_enqueue_script( 'app' );
     
    
     if ($is_IE) {
        wp_register_script ( 'html5shiv', "http://html5shiv.googlecode.com/svn/trunk/html5.js" , false, true);
        wp_enqueue_script ( 'html5shiv' );
     } 
     
     // Enable threaded comments 
     if ( (!is_admin()) && is_singular() && comments_open() && get_option('thread_comments') )
		wp_enqueue_script('comment-reply');
  }
}
add_action( 'init', 'reverie_scripts' );

// create widget areas: sidebar, footer
$sidebars = array('Sidebar');
foreach ($sidebars as $sidebar) {
	register_sidebar(array('name'=> $sidebar,
		'before_widget' => '<article id="%1$s" class="row widget %2$s"><div class="sidebar-section twelve columns">',
		'after_widget' => '</div></article>',
		'before_title' => '<h6><strong>',
		'after_title' => '</strong></h6>'
	));
}
$sidebars = array('Footer');
foreach ($sidebars as $sidebar) {
	register_sidebar(array('name'=> $sidebar,
		'before_widget' => '<article id="%1$s" class="four columns widget %2$s"><div class="footer-section">',
		'after_widget' => '</div></article>',
		'before_title' => '<h6><strong>',
		'after_title' => '</strong></h6>'
	));
}

// return entry meta information for posts, used by multiple loops.
function reverie_entry_meta() {
	echo '<time class="updated" datetime="'. get_the_time('c') .'" pubdate>'. sprintf(__('Posted on %s at %s.', 'reverie'), get_the_time('l, F jS, Y'), get_the_time()) .'</time>';
	echo '<p class="byline author vcard">'. __('Written by', 'reverie') .' <a href="'. get_author_posts_url(get_the_author_meta('ID')) .'" rel="author" class="fn">'. get_the_author() .'</a></p>';
}

/* Customized the output of caption, you can remove the filter to restore back to the WP default output. Courtesy of DevPress. http://devpress.com/blog/captions-in-wordpress/ */
add_filter( 'img_caption_shortcode', 'cleaner_caption', 10, 3 );

function cleaner_caption( $output, $attr, $content ) {

	/* We're not worried abut captions in feeds, so just return the output here. */
	if ( is_feed() )
		return $output;

	/* Set up the default arguments. */
	$defaults = array(
		'id' => '',
		'align' => 'alignnone',
		'width' => '',
		'caption' => ''
	);

	/* Merge the defaults with user input. */
	$attr = shortcode_atts( $defaults, $attr );

	/* If the width is less than 1 or there is no caption, return the content wrapped between the [caption]< tags. */
	if ( 1 > $attr['width'] || empty( $attr['caption'] ) )
		return $content;

	/* Set up the attributes for the caption <div>. */
	$attributes = ' class="figure ' . esc_attr( $attr['align'] ) . '"';

	/* Open the caption <div>. */
	$output = '<figure' . $attributes .'>';

	/* Allow shortcodes for the content the caption was created for. */
	$output .= do_shortcode( $content );

	/* Append the caption text. */
	$output .= '<figcaption>' . $attr['caption'] . '</figcaption>';

	/* Close the caption </div>. */
	$output .= '</figure>';

	/* Return the formatted, clean caption. */
	return $output;
}

// Clean the output of attributes of images in editor. Courtesy of SitePoint. http://www.sitepoint.com/wordpress-change-img-tag-html/
function image_tag_class($class, $id, $align, $size) {
	$align = 'align' . esc_attr($align);
	return $align;
}
add_filter('get_image_tag_class', 'image_tag_class', 0, 4);
function image_tag($html, $id, $alt, $title) {
	return preg_replace(array(
			'/\s+width="\d+"/i',
			'/\s+height="\d+"/i',
			'/alt=""/i'
		),
		array(
			'',
			'',
			'',
			'alt="' . $title . '"'
		),
		$html);
}
add_filter('get_image_tag', 'image_tag', 0, 4);

// Customize output for menu
class reverie_walker extends Walker_Nav_Menu {
  function start_lvl(&$output, $depth) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<a href=\"#\" class=\"flyout-toggle\"><span> </span></a><ul class=\"flyout\">\n";
  }
}

// Add Foundation 'active' class for the current menu item 
function reverie_active_nav_class( $classes, $item )
{
    if($item->current == 1)
    {
        $classes[] = 'active';
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'reverie_active_nav_class', 10, 2 );

// img unautop, Courtesy of Interconnectit http://interconnectit.com/2175/how-to-remove-p-tags-from-images-in-wordpress/
function img_unautop($pee) {
    $pee = preg_replace('/<p>\\s*?(<a .*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s', '<figure>$1</figure>', $pee);
    return $pee;
}
add_filter( 'the_content', 'img_unautop', 30 );

// Pagination
function reverie_pagination() {
	global $wp_query;
 
	$big = 999999999; // This needs to be an unlikely integer
 
	// For more options and info view the docs for paginate_links()
	// http://codex.wordpress.org/Function_Reference/paginate_links
	$paginate_links = paginate_links( array(
		'base' => str_replace( $big, '%#%', get_pagenum_link($big) ),
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages,
		'mid_size' => 5,
		'prev_next' => True,
	    'prev_text' => __('&laquo;'),
	    'next_text' => __('&raquo;'),
		'type' => 'list'
	) );
 
	// Display the pagination if more than one page is found
	if ( $paginate_links ) {
		echo '<div class="reverie-pagination">';
		echo $paginate_links;
		echo '</div><!--// end .pagination -->';
	}
}

// Presstrends
function presstrends() {

// Add your PressTrends and Theme API Keys
$api_key = 'xc11x4vpf17icuwver0bhgbzz4uewlu5ql38';
$auth = 'kw1f8yr8eo1op9c859qcqkm2jjseuj7zp';

// NO NEED TO EDIT BELOW
$data = get_transient( 'presstrends_data' );
if (!$data || $data == ''){
$api_base = 'http://api.presstrends.io/index.php/api/sites/add/auth/';
$url = $api_base . $auth . '/api/' . $api_key . '/';
$data = array();
$count_posts = wp_count_posts();
$count_pages = wp_count_posts('page');
$comments_count = wp_count_comments();
$theme_data = wp_get_theme();
$plugin_count = count(get_option('active_plugins'));
$all_plugins = get_plugins();
foreach($all_plugins as $plugin_file => $plugin_data) {
$plugin_name .= $plugin_data['Name'];
$plugin_name .= '&';
}
$data['url'] = stripslashes(str_replace(array('http://', '/', ':' ), '', site_url()));
$data['posts'] = $count_posts->publish;
$data['pages'] = $count_pages->publish;
$data['comments'] = $comments_count->total_comments;
$data['approved'] = $comments_count->approved;
$data['spam'] = $comments_count->spam;
$data['theme_version'] = $theme_data['Version'];
$data['theme_name'] = urlencode($theme_data['Name']);
$data['site_name'] = str_replace( ' ', '', get_bloginfo( 'name' ));
$data['plugins'] = $plugin_count;
$data['plugin'] = urlencode($plugin_name);
$data['wpversion'] = get_bloginfo('version');
foreach ( $data as $k => $v ) {
$url .= $k . '/' . $v . '/';
}
$response = wp_remote_get( $url );
set_transient('presstrends_data', $data, 60*60*24);
}}
add_action('admin_init', 'presstrends');







/*
*  Advanced Custom Fields Lite
*
*  @description: this is an example of a functions.php file and demonstrates how to use ACF Lite in your theme
*  @Author: Elliot Condon
*  @Author URI: http://www.elliotcondon.com/
*  @Copyright: Elliot Condon
*/


/*
*  1. Copy the "acf" folder to your theme and include it
*/ 

require_once('acf/acf-lite.php');


/*
*  2. Use the new "acf_settings" hook to setup your ACF settings
*  http://www.advancedcustomfields.com/docs/filters/acf_settings/
*/

function my_acf_settings( $options )
{
    // activate add-ons
    $options['activation_codes']['repeater'] = 'QJF7-L4IX-UCNP-RF2W';
    $options['activation_codes']['options_page'] = 'OPN8-FA4J-Y2LW-81LS';
 
    
    // set options page structure
    $options['options_page']['title'] = 'Snowball Theme Options';
    $options['options_page']['pages'] = array('Header', 'Footer', 'Body', 'Global');
    
        
    return $options;
    
}
add_filter('acf_settings', 'my_acf_settings');


/*
*  3. Register any custom fields
*     http://www.advancedcustomfields.com/docs/tutorials/creating-and-registering-your-own-field/
*/

//register_field('my_custom_field', dirname(__File__) . '/fields/my-custom-field.php');



/*
*  4. Register your field groups
*     Field groups can be exported to PHP from the WP "Advanced Custom Fields" plugin.
*/


if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => '50d17292b3aaa',
		'title' => 'Feature Items',
		'fields' => 
		array (
			0 => 
			array (
				'key' => 'field_18',
				'label' => 'Features Top Three',
				'name' => 'features',
				'type' => 'repeater',
				'order_no' => 0,
				'instructions' => 'Featured items on the home page. Just add 3 rows.',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
						),
					),
					'allorany' => 'all',
				),
				'sub_fields' => 
				array (
					'field_19' => 
					array (
						'label' => 'Feature Icon',
						'name' => 'feature_icon',
						'type' => 'image',
						'instructions' => '',
						'column_width' => '',
						'save_format' => 'url',
						'preview_size' => 'thumbnail',
						'order_no' => 0,
						'key' => 'field_19',
					),
					'field_20' => 
					array (
						'label' => 'Feature Headline',
						'name' => 'feature_headline',
						'type' => 'text',
						'instructions' => '',
						'column_width' => '',
						'default_value' => '',
						'formatting' => 'html',
						'order_no' => 1,
						'key' => 'field_20',
					),
					'field_21' => 
					array (
						'label' => 'Feature Text',
						'name' => 'feature_text',
						'type' => 'textarea',
						'instructions' => '',
						'column_width' => '',
						'default_value' => '',
						'formatting' => 'br',
						'order_no' => 2,
						'key' => 'field_21',
					),
				),
				'row_min' => '3',
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add Row',
			),
			1 => 
			array (
				'key' => 'field_38',
				'label' => 'Features Bottom Three',
				'name' => 'features_bottom_row',
				'type' => 'repeater',
				'order_no' => 1,
				'instructions' => 'Features on the homepage 2nd row. Just add 3 Rows.',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
						),
					),
					'allorany' => 'all',
				),
				'sub_fields' => 
				array (
					'field_39' => 
					array (
						'label' => 'Feature	Icon Row 2',
						'name' => 'feature__icon_row_2',
						'type' => 'image',
						'instructions' => '',
						'column_width' => '',
						'save_format' => 'url',
						'preview_size' => 'thumbnail',
						'order_no' => 0,
						'key' => 'field_39',
					),
					'field_40' => 
					array (
						'label' => 'Feature Headline Row 2',
						'name' => 'feature_headline_row_2',
						'type' => 'text',
						'instructions' => '',
						'column_width' => '',
						'default_value' => '',
						'formatting' => 'none',
						'order_no' => 1,
						'key' => 'field_40',
					),
					'field_41' => 
					array (
						'label' => 'Feature Text Row 2',
						'name' => 'feature_text_row_2',
						'type' => 'textarea',
						'instructions' => '',
						'column_width' => '',
						'default_value' => '',
						'formatting' => 'br',
						'order_no' => 2,
						'key' => 'field_41',
					),
					
				),
				'row_min' => '0',
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add Row',
			),
		),
		'location' => 
		array (
			'rules' => 
			array (
				0 => 
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'acf-options-body',
					'order_no' => 0,
				),
			),
			'allorany' => 'all',
		),
		'options' => 
		array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => 
			array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => '50d17292b48c4',
		'title' => 'Footer Options',
		'fields' => 
		array (
			0 => 
			array (
				'key' => 'field_9',
				'label' => 'Twitter Url',
				'name' => 'twitter_url',
				'type' => 'text',
				'order_no' => 0,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
			),
			1 => 
			array (
				'key' => 'field_10',
				'label' => 'Facebook Url',
				'name' => 'facebook_url',
				'type' => 'text',
				'order_no' => 1,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
			),
			2 => 
			array (
				'key' => 'field_11',
				'label' => 'RSS Feed URL',
				'name' => 'rss_feed_url',
				'type' => 'text',
				'order_no' => 2,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
			),
			3 => 
			array (
				'key' => 'field_12',
				'label' => 'Email',
				'name' => 'email',
				'type' => 'text',
				'order_no' => 3,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
			),
			4 => 
			array (
				'key' => 'field_13',
				'label' => 'Linkedin',
				'name' => 'linkedin',
				'type' => 'text',
				'order_no' => 4,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
			),
			5 => 
			array (
				'key' => 'field_14',
				'label' => 'GooglePlus Url',
				'name' => 'googleplus_url',
				'type' => 'text',
				'order_no' => 5,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
			),
			
			6 => 
			array (
				'key' => 'field_15',
				'label' => 'Pinterest Url',
				'name' => 'pinterest_url',
				'type' => 'text',
				'order_no' => 5,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
			),
			
		
			
		),
		'location' => 
		array (
			'rules' => 
			array (
				0 => 
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'acf-options-footer',
					'order_no' => 0,
				),
			),
			'allorany' => 'all',
		),
		'options' => 
		array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => 
			array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => '50d17292b6464',
		'title' => 'Global Options',
		'fields' => 
		array (
			0 => 
			array (
				'key' => 'field_31',
				'label' => 'Logo',
				'name' => 'thelogo',
				'type' => 'image',
				'order_no' => 0,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'save_format' => 'url',
				'preview_size' => 'thumbnail',
			),
			1 => 
			array (
				'key' => 'field_37',
				'label' => 'Background Image',
				'name' => 'background_image',
				'type' => 'image',
				'order_no' => 1,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'field_36',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'save_format' => 'url',
				'preview_size' => 'thumbnail',
			),
			2 => 
			array (
				'key' => 'field_32',
				'label' => 'Background color',
				'name' => 'background_color',
				'type' => 'color_picker',
				'order_no' => 2,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '#c2ece8',
			),
			3 => 
			array (
				'key' => 'field_33',
				'label' => 'Headline Color',
				'name' => 'headline_color',
				'type' => 'color_picker',
				'order_no' => 3,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '#da2a44',
			),
			4 => 
			array (
				'key' => 'field_34',
				'label' => 'Body Color',
				'name' => 'body_color',
				'type' => 'color_picker',
				'order_no' => 4,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '#353535',
			),
			5 => 
			array (
				'key' => 'field_35',
				'label' => 'Button Nav Color',
				'name' => 'button_nav_color',
				'type' => 'color_picker',
				'order_no' => 5,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '#da2a44',
			),
			6 => 
			array (
				'key' => 'field_36',
				'label' => 'Headline Font used',
				'name' => 'font_used',
				'type' => 'select',
				'order_no' => 6,
				'instructions' => 'Select the font',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'field_36',
							'operator' => '==',
							'value' => 'ABeeZee',
						),
					),
					'allorany' => 'all',
				),
				'choices' => 
				array (
					'Arimo' => 'Arimo',
					'Actor' => 'Actor',
					'Cabin' => 'Cabin',
					'Droid Sans' => 'Droid Sans',
					'Lato' => 'Lato',
					'Lobster' => 'Lobster',
					'Montez' => 'Montez',
					'Mr Bedfort' => 'Mr Bedfort',
					'Mrs Saint Delafield' => 'Mrs Saint Delafield',
					'Muli' => 'Muli',
					'PT Sans' => 'PT Sans',
					'PT Serif' => 'PT Serif',
					'Puritan' => 'Puritan',
					'Rochester' => 'Rochester',
					'Rock Salt' => 'Rock Salt',
					'Sacramento' => 'Sacramento',
					'Sail' => 'Sail',
					'Salsa' => 'Salsa',
					'Sanchez' => 'Sanchez',
					'Sancreek' => 'Sancreek',
					'Open Sans' => 'Open Sans',
					'Vibur' => 'Vibur',
					
				),
				'default_value' => 'Arimo : Arimo',
				'allow_null' => '0',
				'multiple' => '0',
			),
			7 => 
			array (
				'key' => 'field_43',
				'label' => 'Google Analytics Code',
				'name' => 'google_analytics_code',
				'type' => 'textarea',
				'order_no' => 7,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'field_36',
							'operator' => '==',
							'value' => 'Actor',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'html',
			),
						8 => 
			array (
				'key' => 'field_44',
				'label' => 'Custom CSS',
				'name' => 'custom_css',
				'type' => 'textarea',
				'order_no' => 8,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'field_36',
							'operator' => '==',
							'value' => 'Actor',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
			),
		),
		'location' => 
		array (
			'rules' => 
			array (
				0 => 
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'acf-options-global',
					'order_no' => 0,
				),
			),
			'allorany' => 'all',
		),
		'options' => 
		array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => 
			array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => '50d17292b83a9',
		'title' => 'Header Options',
		'fields' => 
		array (
						2 => 
			array (
				'key' => 'field_5',
				'label' => 'Headline',
				'name' => 'headline',
				'type' => 'text',
				'order_no' => 2,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'html',
			),
			3 => 
			array (
				'key' => 'field_22',
				'label' => 'Header Text',
				'name' => 'header_text',
				'type' => 'textarea',
				'order_no' => 3,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'br',
			),
			4 => 
			array (
				'key' => 'field_23',
				'label' => 'Header Call To Action Name',
				'name' => 'header_call_to_action_name',
				'type' => 'text',
				'order_no' => 4,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
			),
			5 => 
			array (
				'key' => 'field_24',
				'label' => 'Header Call To Action URL',
				'name' => 'header_call_to_action_url',
				'type' => 'text',
				'order_no' => 5,
				'instructions' => '',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
			),
			6 => 
			array (
				'key' => 'field_25',
				'label' => 'Email for Sign up',
				'name' => 'email_for_sign_up',
				'type' => 'text',
				'order_no' => 6,
				'instructions' => 'What email address should the sign up go to',
				'required' => '0',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'null',
							'operator' => '==',
							'value' => '',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
			),
		),
		'location' => 
		array (
			'rules' => 
			array (
				0 => 
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'acf-options-header',
					'order_no' => 0,
				),
			),
			'allorany' => 'all',
		),
		'options' => 
		array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => 
			array (
			),
		),
		'menu_order' => 0,
	));
}





?>