<?php
/**
 * @package booking-plugin
 * Plugin Name: booking-plugin
 * Plugin URI: https://akismet.com/
 * Description: Booking events plugin for Wordpress.
 * Version:  1.0.0
 * Author:  Aneurin Jones
 * Author URI: https://aneurinj.com
 * License: GPLv2 or later
 * Text Domain: booking-events
 */


class BookingPlugin
{
    public $templates;

    function activate()
    {
       $this->custom_post_type();
       flush_rewrite_rules();
    }

    function deactivate()
    {
        flush_rewrite_rules();
    }
    
    function register()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue'));
       
        $this->templates = array(
            'Assests/templates/formsubmition.php' => 'Booking',
            'Assests/templates/bookingrest.php' => 'Booking-rest',
            'Assests/templates/formconfirm.php' => 'Booking-confirm'
        );
        add_filter('theme_page_templates', array($this, 'booking_templates' ));
        add_filter('template_include', array( $this, 'load_template' ));
    }
    public function booking_templates($templates)
    {
        $templates = array_merge($templates, $this->templates);
        return $templates;
    }
    public function load_template($template)
    {
        $plugindir = dirname( __FILE__ );
        if ( is_page('formsubmition') ) {
            $template = $plugindir . '/Assests/templates/formsubmition.php';
            return $template;
        }
        if ( is_page('confirmadmin') ) {
            $template = $plugindir . '/Assests/templates/formconfirm.php';
            return $template;
        }
        if ( is_page('bookingtable') ) {
            $template = $plugindir . '/Assests/templates/bookingrest.php';
            return $template;
        }
        else
        {
            return $template;
        }

    }

    function uninstall()
    {

    }
    function custom_post_type()
    {

        register_post_type('booking', ['public'=> true, 'label' =>'Bookings']);
    }
    function __construct()
    {
        add_action('init',array($this,'custom_post_type'));

    }
    function enqueue()
    {
        if(is_singular('booking'))
        {
            wp_enqueue_script('jQuery', plugins_url('/Assests/jquerymain.min.js', __FILE__));
            wp_enqueue_style('calanderstyle', plugins_url('/Assests/calanderstyle.css', __FILE__));

            wp_enqueue_style( 'booking-style', plugins_url('/Assests/booking.css', __FILE__));

            wp_enqueue_style('calanderstyle', plugins_url('/Assests/calanderstyle.css', __FILE__));
            wp_enqueue_script('jqueryminjs', plugins_url('/Assests/jquery.js', __FILE__));
            wp_enqueue_script('jqueryuiminjs', plugins_url('/Assests/jquery-ui.min.js', __FILE__));
            wp_enqueue_script('momentminjs', plugins_url('/Assests/moment.js', __FILE__));
            wp_enqueue_script('fullcalendarjquery', plugins_url('/Assests/fullcalendar.js', __FILE__));
            wp_enqueue_script('basic', plugins_url('/Assests/basicjs.php', __FILE__));
            
        }
    }
}

$bookingPugin = new BookingPlugin();
$bookingPugin->register();
function loadCalander()
{

}
add_action( 'wp_enqueue_scripts', 'dequeue_my_scripts', 999 );
function dequeue_my_scripts()
{ 
    if(is_singular('booking'))
    {
        global $wp_styles;
        $wp_styles->queue = array();
        wp_enqueue_script('jQuery', plugins_url('/Assests/jquerymain.min.js', __FILE__));
        wp_enqueue_style( 'booking-style', plugins_url('/Assests/booking.css', __FILE__));
        wp_enqueue_style('calanderstyle', plugins_url('/Assests/calanderstyle.css', __FILE__));
        wp_enqueue_script('jqueryminjs', plugins_url('/Assests/jquery.js', __FILE__));
        wp_enqueue_script('jqueryuiminjs', plugins_url('/Assests/jquery-ui.min.js', __FILE__));
        wp_enqueue_script('momentminjs', plugins_url('/Assests/moment.js', __FILE__));
        wp_enqueue_script('fullcalendarjquery', plugins_url('/Assests/fullcalendar.js', __FILE__));
        wp_enqueue_script('basic', plugins_url('/Assests/basicjs.php', __FILE__));
        
    }

}
register_activation_hook(__FILE__, array($bookingPugin,'activate'));

register_deactivation_hook(__FILE__, array($bookingPugin,'deactivate'));

register_activation_hook(__FILE__, 'add_my_custom_page');
function add_my_custom_page() 
{
    include 'connections.php';
    $data = array();
    $query = "SELECT * FROM bookings WHERE eventType=1 ORDER BY id";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach($result as $row)
    {
        $data[] = array(
        'id'   => $row["id"],
        'start'   => $row["startevent"],
        'end'   => $row["endevent"]
        );
    }
    $restData = json_encode($data); 
    $post_title = 'bookingtable';
    $plugindir = dirname( __FILE__ );
    $template = $plugindir . '/Assests/templates/bookingrest.php';
    if(get_page_by_title($post_title)== NULL)
    {
        $restApi = array(
            'post_title'  => $post_title,
            'post_content'  => $restData,
            'post_type'     => 'page',
             'post_status'   => 'publish',
            'page_template'  => $template,
            'post_author' => 1

        );
            
        $post_id = wp_insert_post( $restApi );
        //update_post_meta( $post_id, '_wp_page_template', 'Booking-rest' );
    }
    $post_title2 = 'formsubmition';
    $template2 = $plugindir . '/Assests/templates/formsubmition.php';
    if(get_page_by_title($post_title2)== NULL)
    {
        $restApi2 = array(
            'post_title'  => $post_title2,
            'post_content'  => '',
            'post_type'     => 'page',
            'post_status'   => 'publish',
            'page_template'  => $template2,
            'post_author' => 1

        );
            
        $post_id2 = wp_insert_post( $restApi2 );
        //update_post_meta( $post_id, '_wp_page_template', 'Booking-rest' );
    }

    $post_title3 = 'booking';
    $content='<p id="calendar"></p>';
    if(get_page_by_title($post_title3)== NULL)
    {
        $restApi3 = array(
            'post_title'  => $post_title3,
            'post_content'  => $content,
            'post_type'     => 'booking',
            'post_status'   => 'publish',
            'post_author' => 1

        );
        kses_remove_filters();
        $post_id3 = wp_insert_post( $restApi3 );
        kses_init_filters();
        //update_post_meta( $post_id, '_wp_page_template', 'Booking-rest' );
    }

}
