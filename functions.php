<?php
/*
Author: Will Christenson [Danger Code]
URL: http://www.dangercode.net

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, etc.
*/

// LOAD BONES CORE (if you remove this, the theme will break)
require_once( 'library/bones.php' );

// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
// require_once( 'library/admin.php' );

/*********************
LAUNCH BONES
Let's get everything up and running.
*********************/

function bones_ahoy() {

  //Allow editor style.
  add_editor_style( get_stylesheet_directory_uri() . '/library/css/editor-style.css' );

  // let's get language support going, if you need it
  load_theme_textdomain( 'bonestheme', get_template_directory() . '/library/translation' );

  // USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
  //require_once( 'library/custom-post-type.php' );

  // launching operation cleanup
  add_action( 'init', 'bones_head_cleanup' );
  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'bones_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'bones_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'bones_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'bones_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  bones_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'bones_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'bones_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'bones_excerpt_more' );

} /* end bones ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'bones_ahoy' );

//
function wp_get_attachment( $attachment_id ) {

	$attachment = get_post( $attachment_id );
	return array(
		'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'caption' => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		'href' => get_permalink( $attachment->ID ),
		'src' => $attachment->guid,
		'title' => $attachment->post_title
	);
}

/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );
add_image_size( 'dc-sidebar-thumb-400', 400, 345, true );

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'bones-thumb-600' => __('600px by 150px'),
        'bones-thumb-300' => __('300px by 100px'),
        'dc-sidebar-thumb-400' => __('400px by 345px'),
    ) );
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

/************* THEME CUSTOMIZE *********************/

/* 
  A good tutorial for creating your own Sections, Controls and Settings:
  http://code.tutsplus.com/series/a-guide-to-the-wordpress-theme-customizer--wp-33722
  
  Good articles on modifying the default options:
  http://natko.com/changing-default-wordpress-theme-customization-api-sections/
  http://code.tutsplus.com/tutorials/digging-into-the-theme-customizer-components--wp-27162
  
  To do:
  - Create a js for the postmessage transport method
  - Create some sanitize functions to sanitize inputs
  - Create some boilerplate Sections, Controls and Settings
*/

function bones_theme_customizer($wp_customize) {
  // $wp_customize calls go here.
  //
  // Uncomment the below lines to remove the default customize sections 

  // $wp_customize->remove_section('title_tagline');
  // $wp_customize->remove_section('colors');
    $wp_customize->remove_section('background_image');
  // $wp_customize->remove_section('static_front_page');
  // $wp_customize->remove_section('nav');

  // Uncomment the below lines to remove the default controls
  // $wp_customize->remove_control('blogdescription');
  
  // Uncomment the following to change the default section titles
  // $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
  // $wp_customize->get_section('background_image')->title = __( 'Images' );
}

add_action( 'customize_register', 'bones_theme_customizer' );

/************* FRONT PAGE CUSTOMIZER [DANGER CODE] *********************/
function dc_front_page_customizer( $wp_customize ) {
    
    //
    $wp_customize->add_panel( 'dc_front_page_panel', array(
        'priority'       => 35,
        'capability'     => 'edit_theme_options',
        'theme_supports' => '',
        'title'      => __('Front Page','dangercode'),
        'description' => 'Options for the Front Page',
    ) );
    
    // Add Front Page Section
/*    $wp_customize->add_section( 'dc_front_page_section' , array(
        'title'      => __('Front Page','dangercode'),
        'description' => 'Options for the Front Page',
        'priority'   => 35,
    ));*/
    $wp_customize->add_section( 'dc_front_page_left_section', array(
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
        'title'          => 'Left',
        'description'    => 'Left section of content',
        'panel'  => 'dc_front_page_panel',
    ) );
    $wp_customize->add_section( 'dc_front_page_center_section', array(
        'priority'       => 20,
        'capability'     => 'edit_theme_options',
        'title'          => 'Center',
        'description'    => 'Center section of content',
        'panel'  => 'dc_front_page_panel',
    ) );
    $wp_customize->add_section( 'dc_front_page_right_section', array(
        'priority'       => 30,
        'capability'     => 'edit_theme_options',
        'title'          => 'Right',
        'description'    => 'Right section of content',
        'panel'  => 'dc_front_page_panel',
    ) );
    
    // Add Settings
    $wp_customize->add_setting( 'dc_front_page_left_text' );
    $wp_customize->add_setting( 'dc_front_page_left_link' );
    $wp_customize->add_setting( 'dc_front_page_left_page' );
    
    $wp_customize->add_setting( 'dc_front_page_center_text' );
    $wp_customize->add_setting( 'dc_front_page_center_link' );
    $wp_customize->add_setting( 'dc_front_page_center_page' );
    
    $wp_customize->add_setting( 'dc_front_page_right_text' );
    $wp_customize->add_setting( 'dc_front_page_right_link' );
    $wp_customize->add_setting( 'dc_front_page_right_page' );

    /************************* LEFT *************************/
    // Add control for Text Block
    $wp_customize->add_control( 
        'dc_front_page_left_text',
        array(
            'label' => 'Text',
            'section' => 'dc_front_page_left_section',
            'type' => 'textarea',
        )
    );
    
    // Add control for Link Text
    $wp_customize->add_control( 
        'dc_front_page_left_link',
        array(
            'label' => 'Link Text',
            'section' => 'dc_front_page_left_section',
            'type' => 'text',
        )
    );
    
    // Add control for Page
    $wp_customize->add_control( 
        'dc_front_page_left_page',
        array(
            'label' => 'Link To',
            'section' => 'dc_front_page_left_section',
            'type' => 'dropdown-pages',
        )
    );

    /************************* CENTER *************************/
    // Add control for Text Block
    $wp_customize->add_control( 
        'dc_front_page_center_text',
        array(
            'label' => 'Text',
            'section' => 'dc_front_page_center_section',
            'type' => 'textarea',
        )
    );
    
    // Add control for Link Text
    $wp_customize->add_control( 
        'dc_front_page_center_link',
        array(
            'label' => 'Link Text',
            'section' => 'dc_front_page_center_section',
            'type' => 'text',
        )
    );
    
    // Add control for Page
    $wp_customize->add_control( 
        'dc_front_page_center_page',
        array(
            'label' => 'Link To',
            'section' => 'dc_front_page_center_section',
            'type' => 'dropdown-pages',
        )
    );
    
    /************************* RIGHT *************************/
    // Add control for Text Block
    $wp_customize->add_control( 
        'dc_front_page_right_text',
        array(
            'label' => 'Text',
            'section' => 'dc_front_page_right_section',
            'type' => 'textarea',
        )
    );
    
    // Add control for Link Text
    $wp_customize->add_control( 
        'dc_front_page_right_link',
        array(
            'label' => 'Link Text',
            'section' => 'dc_front_page_right_section',
            'type' => 'text',
        )
    );
    
    // Add control for Page
    $wp_customize->add_control( 
        'dc_front_page_right_page',
        array(
            'label' => 'Link To',
            'section' => 'dc_front_page_right_section',
            'type' => 'dropdown-pages',
        )
    );
}
add_action( 'customize_register', 'dc_front_page_customizer' );
/************* END FRONT PAGE CUSTOMIZER [DANGER CODE] *********************/

/************* LOGO UPLOADER [DANGER CODE] *********************/
function dc_logo_uploader( $wp_customize ) {
    // Add Settings
    $wp_customize->add_setting( 'dc_logo' );
    
    // Add Controls
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'dc_logo', array(
        'label'    => __( 'Logo', 'dangercode' ),
        'section'  => 'title_tagline',
        'settings' => 'dc_logo',
    ) ) );
}
add_action( 'customize_register', 'dc_logo_uploader' );
/************* LOGO UPLOADER [DANGER CODE] *********************/

/************* FRONT PAGE NAV ITEM [DANGER CODE] *********************/
function dc_nav_replace_front_page( $item_output, $item ) {
    if ( get_theme_mod( 'dc_logo' ) ){
        $frontpage_id = get_option('page_on_front');
        //var_dump($item_output, $item);
        if( $item->object_id == $frontpage_id ) {
            $nav_item = '<a href="' . $item->url . '" id="site-logo-alt" rel="home">' . $item->title . '</a>';
            $nav_item .= '<a href="' . $item->url . '" id="site-logo-nav" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" rel="home">';
            $nav_item .= '<img src="' . get_theme_mod( 'dc_logo' ) . '" alt="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '"></a>';
            //return '<div class="img front_page_nav_item" data-key="profile">'.get_avatar( get_current_user_id(), 64 ).'</div>';
            return $nav_item;
        }
        return $item_output;
    }
}
add_filter('walker_nav_menu_start_el','dc_nav_replace_front_page',10,2);
/************* END FRONT PAGE NAV ITEM [DANGER CODE] *********************/

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	// Primary Sidebar
    register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar', 'bonestheme' ),
		'description' => __( 'The primary sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
    
    // Primary Footer Widgets
    register_sidebar(array(
		'id' => 'footer_widgets_center',
		'name' => __( 'Footer Widgets [Center]', 'bonestheme' ),
		'description' => __( 'Footer widgets', 'bonestheme' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<div class="widgettitle">',
		'after_title' => '</div>',
	));
    
    // Secondary Footer Widgets
    register_sidebar(array(
		'id' => 'footer_widgets_right',
		'name' => __( 'Footer Widgets [Right]', 'bonestheme' ),
		'description' => __( 'Secondary Footer widgets', 'bonestheme' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<div class="widgettitle">',
		'after_title' => '</div>',
	));


} // don't remove this bracket!


/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'bonestheme' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'bonestheme' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'bonestheme' )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'bonestheme' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


/*
This is a modification of a function found in the
twentythirteen theme where we can declare some
external fonts. If you're using Google Fonts, you
can replace these fonts, change it in your scss files
and be up and running in seconds.
*/
function bones_fonts() {
  wp_enqueue_style('googleFonts', 'http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
}

//add_action('wp_enqueue_scripts', 'bones_fonts');

/************* BUSINESS INFO CUSTOMIZER [DANGER CODE] *********************/
function dc_business_info_customizer( $wp_customize ) {
    /* Add Business Info Section */
    $wp_customize->add_section(
        'dc_business_info_settings',
        array(
            'title' => 'Business Info',
            'description' => 'Details about the business',
            'priority' => 35,
        )
    );
    
    /* Add Text Block */
    $wp_customize->add_setting( 'dc_business_info_phone' ); // Business Phone
    $wp_customize->add_setting( 'dc_business_info_phone_tagline' ); // Business Phone Tagline
    $wp_customize->add_setting( 'dc_business_info_phone_tagline_link' ); // Business Phone Tagline Link
    $wp_customize->add_setting( 'dc_business_info_office_hours' ); // Office Hours
    $wp_customize->add_setting( 'dc_business_info_service_hours' ); // Service Hours
    $wp_customize->add_setting( 'dc_business_info_service_area' ); // Service Area
    $wp_customize->add_setting( 'dc_business_info_warranty' ); // Warranty
    $wp_customize->add_setting( 'dc_business_info_licenses' ); // Licenses
         
    // Add controls
    $wp_customize->add_control( 
        'dc_business_info_phone',
        array(
            'label' => 'Phone Number',
            'section' => 'dc_business_info_settings',
            'type' => 'text',
        )
    );
    $wp_customize->add_control( 
        'dc_business_info_phone_tagline',
        array(
            'label' => 'Phone Tagline',
            'section' => 'dc_business_info_settings',
            'type' => 'text',
        )
    );
    $wp_customize->add_control( 
        'dc_business_info_phone_tagline_link',
        array(
            'label' => 'Link To',
            'section' => 'dc_business_info_settings',
            'type' => 'dropdown-pages',
        )
    );
    $wp_customize->add_control( 
        'dc_business_info_office_hours',
        array(
            'label' => 'Office Hours',
            'section' => 'dc_business_info_settings',
            'type' => 'textarea',
        )
    );
    $wp_customize->add_control( 
        'dc_business_info_service_hours',
        array(
            'label' => 'Service Hours',
            'section' => 'dc_business_info_settings',
            'type' => 'textarea',
        )
    );
    $wp_customize->add_control( 
        'dc_business_info_service_area',
        array(
            'label' => 'Service Area',
            'section' => 'dc_business_info_settings',
            'type' => 'textarea',
        )
    );
    $wp_customize->add_control( 
        'dc_business_info_warranty',
        array(
            'label' => 'Warranty',
            'section' => 'dc_business_info_settings',
            'type' => 'textarea',
        )
    );
    $wp_customize->add_control( 
        'dc_business_info_licenses',
        array(
            'label' => 'Licenses',
            'section' => 'dc_business_info_settings',
            'type' => 'textarea',
        )
    );
}
add_action( 'customize_register', 'dc_business_info_customizer' );
/************* END BUSINESS INFO CUSTOMIZER [DANGER CODE] *********************/

/**
 * [Danger Code] Contact Widget.
 */
class dc_contact_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'dc_contact_widget', // Base ID
			__( 'Contact Widget', 'text_domain' ), // Name
			array( 'description' => __( 'Contact Us Widget', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
        if ( get_theme_mod( 'dc_business_info_phone' ) ) : 
        ?>
            <a href="tel://<?php echo get_theme_mod( 'dc_business_info_phone' );?>"><?php echo get_theme_mod( 'dc_business_info_phone' );?></a>
        <?php 
        endif;
        if( get_theme_mod( 'dc_business_info_phone_tagline_link' ) ){
            echo '<a href="'. get_permalink( get_theme_mod( 'dc_business_info_phone_tagline_link' ) ).'">';
        }
        if( get_theme_mod( 'dc_business_info_phone_tagline' ) ) echo " | " . strtoupper(get_theme_mod( 'dc_business_info_phone_tagline' ));
        if( get_theme_mod( 'dc_business_info_phone_tagline_link' ) ) echo '</a>';
        if( !empty( $instance['email'] ) ){
			echo ' | <a href="mailto:'.$instance['email'].'">Email Us</a>';
		}
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$email = !empty( $instance['email'] ) ? $instance['email'] : __( 'New Email', 'text_domain' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php _e( 'Contact Email Address:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" type="text" value="<?php echo esc_attr( $email ); ?>">
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['email'] = ( ! empty( $new_instance['email'] ) ) ? strip_tags( $new_instance['email'] ) : '';

		return $instance;
	}

} // class dc_contact_widget


// register dc_contact_widget widget
function register_dc_contact_widget() {
    register_widget( 'dc_contact_widget' );
}
add_action( 'widgets_init', 'register_dc_contact_widget' );

/**
 * [Danger Code] Icon Widget
 */
class dc_icon_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'dc_icon_widget', // Base ID
			__( 'Icon Widget', 'text_domain' ), // Name
			array( 'description' => __( 'Icon Link Widget', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
        if ( !empty( $instance['url'] ) ){
            $url = $instance['url'];
            if( strtolower(substr($link,0,4)) != 'http' ) $url = "http://" . $url;
            echo '<a href="'.$url.'">';
        }
        if( !empty($instance['icon']) ){
            include('library/svg_icons.php');
            switch( $instance['icon'] ){
                case 'bbb':
                    $icon = $bbb_logo_icon;
                    break;
                case 'angies':
                    $icon = $angies_list_icon;
                    break;
                default:
                    $icon = false;
            }
            if( $icon ){
                echo $icon;
            }
        }
        if ( !empty( $instance['url'] ) ) echo '</a>';
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$url = ! empty( $instance['url'] ) ? $instance['url'] : __( 'New Link', 'text_domain' );
		$icon = ! empty( $instance['icon'] ) ? $instance['icon'] : __( 'New Icon', 'text_domain' );
		?>
		<p>
            <label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'URL to link to:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo esc_attr( $url ); ?>">
		</p>
        <label for="<?php echo $this->get_field_id('text'); ?>">Icon: 
            <select class='widefat' id="<?php echo $this->get_field_id('icon'); ?>" name="<?php echo $this->get_field_name('icon'); ?>" type="text">
                <option value='bbb'<?php echo ($icon=='bbb')?'selected':''; ?>>Better Business Bureau</option>
                <option value='angies'<?php echo ($icon=='angies')?'selected':''; ?>>Angie's List</option> 
            </select>                
        </label>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['url'] = ( ! empty( $new_instance['url'] ) ) ? strip_tags( $new_instance['url'] ) : '';
		$instance['icon'] = ( ! empty( $new_instance['icon'] ) ) ? strip_tags( $new_instance['icon'] ) : '';

		return $instance;
	}

} // class dc_icon_widget


// register dc_contact_widget widget
function register_dc_icon_widget() {
    register_widget( 'dc_icon_widget' );
}
add_action( 'widgets_init', 'register_dc_icon_widget' );

// THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA
// Fix Sidebar Galleries Plugin
function custom_colors() {
   echo '<style type="text/css">
           .no-sidebar .media-sidebar,
            .no-sidebar .media-frame-menu .media-menu a,
            .no-sidebar .media-frame-menu .media-menu div,
            .no-sidebar .media-toolbar-secondary,
            .no-sidebar .media-frame .attachment .describe {
                display: block !important;
            }
         </style>';
}

add_action('admin_head', 'custom_colors');

/************* FACEBOOK OPEN GRAPH TAGS [DANGER CODE] *********************/
add_action( 'wp_head', 'dc_facebook_tags' );
function dc_facebook_tags() {
    if( is_front_page() ) {
    ?>
        <meta property="og:title" content="<?php bloginfo( 'name' ) ?>" />
        <meta property="og:site_name" content="<?php bloginfo( 'name' ) ?>" />
        <meta property="og:url" content="<?php the_permalink() ?>" />
        <meta property="og:description" content="<?php bloginfo( 'description' ) ?>" />
        <meta property="og:type" content="website" />

        <?php if( get_theme_mod( 'dc_facebook_og_image' ) ) : ?>
            <meta property="og:image" content="<?php echo get_theme_mod( 'dc_facebook_og_image' ); ?>"/>  
        <?php elseif( get_theme_mod( 'dc_logo' ) ) : ?>
            <meta property="og:image" content="<?php echo get_theme_mod( 'dc_logo' ); ?>"/>  
        <?php endif; ?>

    <?php
    } else {
    ?>
        <meta property="og:title" content="<?php the_title() ?>" />
        <meta property="og:site_name" content="<?php bloginfo( 'name' ) ?>" />
        <meta property="og:url" content="<?php the_permalink() ?>" />
        <meta property="og:description" content="<?php the_excerpt() ?>" />
        <meta property="og:type" content="article" />

        <?php 
        if ( has_post_thumbnail() ) :
            $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' ); 
        ?>
            <meta property="og:image" content="<?php echo $image[0]; ?>"/>  
        <?php endif; ?>

    <?php
    }
}
/************* END FACEBOOK OPEN GRAPH TAGS [DANGER CODE] *********************/

/************* FACEBOOK OPEN GRAPH IMAGE [DANGER CODE] *********************/
function dc_facebook_og_image( $wp_customize ) {
    // Add setting
    $wp_customize->add_setting( 'dc_facebook_og_image' );
    
    // Add control
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'dc_facebook_og_image', array(
        'label'         => __( 'Facebook OpenGraph Image', 'dangercode' ),
        'section'       => 'title_tagline',
        'priority'      => 60,
    ) ) );
}
add_action( 'customize_register', 'dc_facebook_og_image' );
/************* END FACEBOOK OPEN GRAPH IMAGE [DANGER CODE] *********************/

/* DON'T DELETE THIS CLOSING TAG */ ?>
