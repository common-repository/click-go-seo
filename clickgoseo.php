<?php
/*
Plugin Name: WordPress Click & Go SEO
Plugin URI:	http://www.siteground.com
Description: This plugin is created to provide you all the necessary SEO features for your WordPress site.
Version: 1.1.0
Author: Hristo Pandjarov
Author URI: http://profiles.wordpress.org/hristo-sg/
License: GPLv2
*/ 

//Add The Admin Menu
add_action( 'admin_menu', 'cg_seo_admin' );
function cg_seo_admin(){
    add_menu_page( __( 'WordPress Click & Go SEO', 'clickgoseo' ), __( 'Click & Go SEO', 'clickgoseo' ), 'manage_options', 'cgseo', 'cgseo_options', plugins_url( 'click-go-seo/images/icon.png' ) );
}

//Adding defaults
register_activation_hook(__FILE__, 'cgs_defaults');

function cgs_defaults() {
	$sitename = get_bloginfo('name');
	$siteurl = get_site_url();
	
	$deftitle = get_option('cgs_setting');
	$deftitle = $deftitle['cgs-home-title'];
	
	$defcanonical = get_option('cgs_setting');
	$defcanonical = $defcanonical['cgs-home-canonical'];
	
	$defauthor = get_option('cgs_setting');
	$defauthor = $defauthor['cgs-google-author'];
	
	$defauto = get_option('cgs_setting');
	$defauto = $defauto['cgs-auto-descr'];
	
	$defblock = get_option('cgs_setting');
	$defblock = $defblock['cgs-block-search'];
	
	$dvalue = array (
		'cgs-home-title' => $sitename,
		'cgs-home-meta' => '',
		'cgs-home-canonical' => $siteurl,
		'cgs-title-prefix' => '',
		'cgs-title-sufix' => '',
		'cgs-google-author' => 1,
		'cgs-google-publisher' => '',
		'cgs-google-wmt' => '',
		'cgs-google-analytics' => '',
		'cgs-auto-descr' => 1,
		'cgs-block-search' => 1
	);	
		
	if ( (empty($deftitle)) && (empty($defcanonical)) && (empty($defauthor)) && (empty($defauto)) && (empty($defblock)) ) {
		update_option( 'cgs_setting', $dvalue );
	}
}

//Register all Settings
add_action( 'admin_init', 'cgs_admin_init' );
function cgs_admin_init() {
	register_setting('cgs_setting', 'cgs_setting');
	
	add_settings_section( 'index-settings',  __( 'Home Page Settings', 'clickgoseo' ), 'index_settings_callback', 'cgseo' );
	add_settings_field( 'cgs-home-title', __( 'Home Page Meta Title', 'clickgoseo' ), 'meta_title_callback', 'cgseo', 'index-settings' );
	add_settings_field( 'cgs-home-meta', __( 'Home Page Meta Description', 'clickgoseo' ), 'meta_desc_callback', 'cgseo', 'index-settings' );
	add_settings_field( 'cgs-home-canonical', __( 'Home Page Rel Canonical', 'clickgoseo' ), 'home_rel_can_callback', 'cgseo', 'index-settings' );
    
	add_settings_section( 'single-settings', __( 'Single Posts/Pages Meta Settings', 'clickgoseo' ), 'single_settings_callback', 'cgseo' );
	add_settings_field( 'cgs-title-prefix', __( 'Meta Title Prefix', 'clickgoseo' ), 'meta_title_prefix_callback', 'cgseo', 'single-settings' );
	add_settings_field( 'cgs-title-sufix', __( 'Meta Title Suffix', 'clickgoseo' ), 'meta_title_sufix_callback', 'cgseo', 'single-settings' );
    
	add_settings_section( 'google-settings', __( 'Google Related Settings', 'clickgoseo' ), 'google_settings_callback', 'cgseo' );
	add_settings_field( 'cgs-google-author', __('Enable the <strong>Google Authorship ID</strong> field in the <strong>Users</strong> page and generate the necessary meta tags.', 'clickgoseo'), 'author_callback', 'cgseo', 'google-settings' );
	add_settings_field( 'cgs-google-publisher', __( 'Google Publisher ID', 'clickgoseo' ), 'publisher_callback', 'cgseo', 'google-settings' );
	add_settings_field( 'cgs-google-wmt', __( 'Google WMT ID', 'clickgoseo'), 'gwt_callback', 'cgseo', 'google-settings' );
	add_settings_field( 'cgs-google-analytics', __( 'Google Analytics ID', 'clickgoseo' ), 'analytics_callback', 'cgseo', 'google-settings' );
   
	add_settings_section( 'additional-settings', __( 'Additional Settings', 'clickgoseo' ), 'posts_settings_callback', 'cgseo' );
	add_settings_field( 'cgs-block-search', __( 'Block <strong>Search</strong> & <strong>404 Not Found</strong> Pages From Indexing', 'clickgoseo' ), 'block_search_callback', 'cgseo', 'additional-settings' );
	add_settings_field( 'cgs-auto-descr', __( 'Automatically generate meta description from your <strong>excerpts</strong>', 'clickgoseo' ), 'auto_descr_callback', 'cgseo', 'additional-settings' );
}

//Creatting the Index Settings section
function index_settings_callback() {
	_e('These settings will handle the behaviour of your home page.', 'clickgoseo' );
}

//HOME PAGE SETTINGS
function meta_title_callback() {
	$setting = get_option('cgs_setting', '');
	$setting = $setting['cgs-home-title'];
	echo '<input id="metatitle" type="text" name="cgs_setting[cgs-home-title]" size="65" value="'.$setting.'" />';
	echo '<p class="description" id="counter">'.__('The perfect length of your title is between 45 and 65 characters.Your meta title is ', 'clickgoseo').'<span id="counterInput">0</span>',__(' characters long.', 'clickgoseo').'</p>';
}

function meta_desc_callback() {
    $setting = get_option('cgs_setting', '');
    $setting = $setting['cgs-home-meta'];
    echo '<textarea id="metadesc" name="cgs_setting[cgs-home-meta]" class="large-text code" rows="3" style="max-width:500px" ">'.$setting.'</textarea>';
    echo '<p class="description" id="dcounter">'.__('The perfect length of your meta description is between 100 and 155 characters.Your meta description is ', 'clickgoseo').'<span id="counterTextarea">0</span>'.__(' characters long.','clickgoseo').'</p>';
}

function home_rel_can_callback() {
	$setting = get_option('cgs_setting', '');
	$setting = esc_url($setting['cgs-home-canonical']);
    echo "<input type='text' name='cgs_setting[cgs-home-canonical]' size='65' value='$setting' />";
    echo '<p class="description">'.__('The "canonical" meta tag helps you avoid duplicated content and tells Google the actual URL of your page that has to be indexed. Find out more on that matter in the <a target="_blank" href="https://support.google.com/webmasters/answer/139394?hl=en">Official Google Documentation</a>.', 'clickgoseo').'</p>';
}

//SINGLE POST/PAGE SETTINGS
function single_settings_callback() {
    _e('Uppon installation Click & Go WordPress SEO will strip the unnecessary content added to your meta titles. If you want to use a prefix or suffix to your titles, please use the fields below to do so.', 'clickgoseo');
}

function meta_title_prefix_callback() {
	$setting = get_option('cgs_setting', '');
	$setting = $setting['cgs-title-prefix'];
    echo "<input type='text' name='cgs_setting[cgs-title-prefix]' value='$setting' />";
}
function meta_title_sufix_callback() {
	$setting = get_option('cgs_setting', '');
	$setting = $setting['cgs-title-sufix'];
    echo "<input type='text' name='cgs_setting[cgs-title-sufix]' value='$setting' />";
}

//GOOGLE-RELATED SETTINGS
function google_settings_callback() {
    _e('These settings will handle the Google related information.','clickgoseo');
}

function author_callback() {
	$setting = get_option('cgs_setting', '');
	$setting = $setting['cgs-google-author'];
	echo "<input type='checkbox' name='cgs_setting[cgs-google-author]' value='1' ";
	if ( $setting ) echo "checked='checked'";
	echo " />";
   	echo '<span class="description"> '.__('The "Author" meta tag specifies the Author for the particular post/page. Check out the ', 'clickgoseo').'<a href="https://plus.google.com/authorship" target="_blank">'.__('Official Google Documentation','clickgoseo').'</a>'.__(' on authorship for more information on that matter. If you configure everything correct, your search result snippets should look like this:', 'clickgoseo').'</span>';
   	echo '<p><img src="'.plugins_url( 'click-go-seo/images/authorship.jpg' ).'" /></p>';
}

function publisher_callback() {
    $setting = get_option('cgs_setting', '');
    $setting = $setting['cgs-google-publisher'];
    echo "<input type='text' name='cgs_setting[cgs-google-publisher]' value='$setting' />";
    echo '<p class="description">'.__('The "Publisher" meta tag shows Google that a specified Google+ profile is responsible for the content on your website. Even if you have multiple authors, there can be only one Publisher per domain name.', 'clickgoseo').'</p>';
}

function gwt_callback() {
	$setting = get_option('cgs_setting', '');
	$setting = $setting['cgs-google-wmt'];
    echo "<input type='text' name='cgs_setting[cgs-google-wmt]' value='$setting' />";
	echo '<p class="description">'.__('Add your Google Webmasters Tools ID to authorize your site with GWT. ', 'clickgoseo' ).' <a href="https://support.google.com/webmasters/answer/35659" target="_blank">'.__('Lean how to Get your GWT ID','clickgoseo').'</a></p>';
}

function analytics_callback() {
	$setting = get_option('cgs_setting', '');
	$setting = $setting['cgs-google-analytics'];
    echo "<input type='text' name='cgs_setting[cgs-google-analytics]' value='$setting' />";
    echo '<p class="description">'.__('Add your Google Analytics ID to authorize your site with Google Analytics.</p>', 'clickgoseo' );
}

//ADDITIONAL SETTINGS
function posts_settings_callback() {
    _e('Few additional settings to further improve different parts of your WordPress site.', 'clickgoseo');
}

function block_search_callback() {
    $setting = get_option('cgs_setting', '');
    $setting = $setting['cgs-block-search'];
    echo "<input type='checkbox' name='cgs_setting[cgs-block-search]' value='1' ";
    if ( $setting ) echo "checked='checked'";
    echo " />";
    _e('<span class="description"> Protects you from SEO spam attacks. Someone might link to a page like "/?s=viagra" on your site causing suspicious content to be indexed by Google.</span>', 'clickgoseo');    
}

function auto_descr_callback() {
	$setting = get_option('cgs_setting', '');
	$setting = $setting['cgs-auto-descr'];
	echo "<input type='checkbox' name='cgs_setting[cgs-auto-descr]' value='1' ";
	if ( $setting ) echo "checked='checked'";
	echo " />";
	echo '<span class="description"> '.__('If checked, WordPress Click & Go SEO will generate a meta description tag for your posts, pages, etc. from the data in your excerpt.</span>', 'clickgoseo');
}

//Rendering the settings page
function cgseo_options() {
	?>
    <div class="wrap">
        <h2><img src="<?php echo plugins_url( 'click-go-seo/images/icon18.png' ) ?>" style="margin-right: 5px;" /><?php _e('WordPress Click & Go SEO Settings Page', 'clickgoseo'); ?></h2>
        <form action="options.php" method="POST"> 
        	<?php settings_fields( 'cgs_setting' ); ?>
            <?php do_settings_sections( 'cgseo' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
    $headercontent = file_get_contents( '../wp-content/themes/'.get_template().'/header.php' );
    preg_match('/<title\>(.*)\</',  $headercontent, $matches);
    $found = $matches[1];
    if ( ! preg_match("/^\s*\<\?php\s*wp_title\((.*?)\);\s*\?\>$/", $found)) {
    	echo '<div  class="error">Warning! Your theme is not using the <strong>wp_title</strong> tag correctly! Open the header.php file of your theme, locate the title declaration and change it to:<br/>
    	<strong>&lt;title&gt;&lt;?php wp_title(); ?&gt;&lt;/title&gt;</strong></div>';
    }
}

//Add the Google Author ID field to the usermeta
add_filter('user_contactmethods', 'modify_contact_methods');
function modify_contact_methods($profile_fields) {
	$authid = get_option('cgs_setting', '');
	if( isset( $authid['cgs-google-author'] ) ) {
		$profile_fields['gauthor'] = 'Google Authorship ID';
	}
	return $profile_fields;
}

//Adding the necessary lines to the head
add_action( 'wp_head', 'cgs_add_head_content' );
function cgs_add_head_content(){
	global $post;
	$publisherid = get_option( 'cgs_setting', '' );
	$publisherid = $publisherid['cgs-google-publisher'];
	$gwtid = get_option( 'cgs_setting', '');
	$gwtid = $gwtid['cgs-google-wmt'];
	$mdescr = get_option ( 'cgs_setting','');
	$mdescr = $mdescr['cgs-home-meta'];
	$cannonicalrel = get_option ( 'cgs_setting','');
	$cannonicalrel = $cannonicalrel['cgs-home-canonical'];
	$blocksearch = get_option ( 'cgs_setting','');
	$blocksearch = $blocksearch['cgs-block-search'];
	$autometa = get_option('cgs_setting','');
	$autometa = $autometa['cgs-auto-descr'];
	$exc = get_the_excerpt();
	$descvalue = substr( (esc_html($exc)), 0, 170);
	$gauthorid = get_user_meta($post->post_author, 'gauthor', 1);
	
	echo '<!-- WordPress Click & Go SEO -->'."\n";
	if( ! empty( $gwtid ) ) echo '<meta name="google-site-verification" content="'.$gwtid.'" />'."\n";
	if( ! empty( $publisherid ) ) echo '<link href="https://plus.google.com/'.$publisherid.'" rel="publisher" />'."\n";
	if (! is_home()) {
		if( ! empty( $gauthorid ) ) echo '<link href="https://plus.google.com/'.$gauthorid.'" rel="author" />'."\n";
		if( ( $autometa == 1 ) && ( $descvalue ) ) echo '<meta name="description" content="'.$descvalue.'" />'."\n";
	}
	if (is_home()) {
		if ( $mdescr ) echo '<meta name="description" content="'.$mdescr.'" />'."\n";
		if ( $cannonicalrel ) echo '<link rel="canonical" href="'.$cannonicalrel.'" />'."\n";
	}
	if( ( $blocksearch == 1) && (is_search() || is_404()) ) echo '<meta name="robots" content="noindex,noarchive" />'."\n";
	echo '<!-- WordPress Click & Go SEO -->'."\n";
}

//Adding the necessary lines to the footer
add_action( 'wp_footer', 'cgs_add_footer_content' );
function cgs_add_footer_content(){
	$analyticsid = get_option('cgs_setting', '');
	$analyticsid = $analyticsid['cgs-google-analytics'];
	if( ! empty( $analyticsid ) ) { ?>
		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', '<?php echo $analyticsid; ?>']);
		  _gaq.push(['_trackPageview']);
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>
		<?php
	}
}

/* Replacing the original WP home title, stripping other titles, adding title prefix/sufix */
add_filter('wp_title', 'set_page_title',42);
function set_page_title() {
	global $post;
	$hometitle = get_option('cgs_setting', '');
	$hometitle = $hometitle['cgs-home-title'];
	$titleprefix = get_option('cgs_setting', '');
	$titleprefix = $titleprefix['cgs-title-prefix'];	
	$titlesufix = get_option('cgs_setting', '');
	$titlesufix = $titlesufix['cgs-title-sufix'];

	if (is_front_page()) return $hometitle;
	if (is_single()) return $titleprefix.$post->post_title.$titlesufix;
	if (is_page()) return $titleprefix.get_the_title().$titlesufix;
	if (is_day()) return $titleprefix.__( 'Daily Archives: ', 'clickgoseo' ).get_the_date().$titlesufix;
	if (is_month()) return $titleprefix.__( 'Monthly Archives: ', 'clickgoseo' ).get_the_date('F Y').$titlesufix;
	if (is_year()) return $titleprefix.__( 'Yearly Archives: ', 'clickgoseo' ).get_the_date('Y').$titlesufix;
	if (is_category()) return $titleprefix.__( 'Category Archives: ', 'clickgoseo' ).single_cat_title( '', false ).$titlesufix;
	if (is_tag()) return $titleprefix.__( 'Tag Archives: ', 'clickgoseo' ).single_tag_title( '', false ).$titlesufix;
	if (is_search()) return $titleprefix.__( 'Search results for: ', 'clickgoseo' ).get_search_query().$titlesufix;
	if (is_404()) return  $titleprefix.__( '404 Page Not Found', 'clickgoseo' ).$titlesufix;
}

//Adding the JavaScript and CSS
function cg_seo_js_init() {
	wp_register_script( 'clickgoseo-backend', plugins_url( '/js/clickgoseo.js', __FILE__ ) );
}

function cg_seo_js_scripts() {
	wp_enqueue_script( 'clickgoseo-backend' );
}

add_action( 'admin_enqueue_scripts', 'cg_seo_js_scripts' );
add_action( 'admin_init', 'cg_seo_js_init' );

function cg_seo_css_init() {
	wp_register_style( 'clickgoseo-css', plugins_url( '/css/clickgoseo.css', __FILE__ ) );
}

function cg_seo_css_scripts() {
	wp_enqueue_style( 'clickgoseo-css' );
}

add_action( 'admin_enqueue_scripts', 'cg_seo_css_scripts' );
add_action( 'admin_init', 'cg_seo_css_init' );

//Initialize Language Files
function cgs_lang_init() {
	load_plugin_textdomain('clickgoseo', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}
add_action('plugins_loaded', 'cgs_lang_init');
