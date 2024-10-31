<?php
/**
Plugin Name: PDF Flip Book by Kenrys
description: Simply Add PDF files in Flip Book style to your pages or posts. Full control over Width, Height and Pagination. After activation please go to  PDF Flip Book Page.
Version: 1.1.2
Author: Kenrys
License: Free
Author URI: http://kenrys.com/
*/

// Add Flip book Page
function add_my_pages_plugin_pfbk_flip_book() {
    /************************************* PDF Flip Book page ************************************* */
        add_menu_page( $name, 'PDF Flip Book ', 'manage_options', 'PDF_Flip_Book_Kenrys', 'page_plugin_pfbk_flip_book', '', 85 );
}
add_action( 'admin_menu', 'add_my_pages_plugin_pfbk_flip_book' );



function page_plugin_pfbk_flip_book() {
    
    include('PDF_Flip_Book_Kenrys_settings.php');
}

// Extend Upload & post Size 
@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');

// Extend execute time
@ini_set( 'max_execution_time', '300' );




// Creat pfbk_pdf_flipbook short code
function pfbk_flip_books_shortcode($atts = [], $content = null, $tag = '')
{
    // normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);
 
    // override default attributes with user attributes
    $kenrys_atts = shortcode_atts([
                                     'src' => 'WordPress.org',
                                     'width' => '',
                                     'height' => '',
                                     'pages' => 'off',
                                     'animation' => 'off',



                                 ], $atts, $tag);
 if($kenrys_atts['pages'] == 'off') {$dos = 'hidden';}
 if($kenrys_atts['animation'] == 'off') {$ani = false;} else {$ani = true;}


    wp_enqueue_script( $kenrys_atts['src'] , plugin_dir_url(__FILE__) . 'js/pdf.js', array(), '', false );
   




ob_start();
include "template.php";
$o  = ob_get_clean();
return $o;
}
 
function pfbk_flip_books_shortcodes_init()
{
    add_shortcode('pfbk_pdf_flipbook', 'pfbk_flip_books_shortcode');
}
 

add_action('init', 'pfbk_flip_books_shortcodes_init');



// check if the composer is activated
if(in_array('js_composer/js_composer.php',get_option( 'active_plugins' ))){
    
function v_pfbk_flip_book_component() {
    // Add Element
    vc_map(
        array(
            'name' => __( 'PDF Flip Book ' ),
            'base' => 'v_pfbk_flip_book',
            'category' => __( 'Kenrys Elements' ),
            "icon" => "PDF_Flip_Book_icon", // Simply pass url to your icon here
            'admin_enqueue_css' => array( plugin_dir_url(__FILE__) . 'css/PDF_Flip_Book_icon.css' ),
            'params' => array(
             array(
                    'type' => 'textfield',
                    'holder' => 'div',
                    'class' => '',
                    'heading' => __( 'PDF File URL' ),
                    'param_name' => 'src',
                    'value' => __( '' ),
                    'description' => __( 'Add the PDF file URL, you can uplaod the file from Media Library or you can use an external file URL.' ),
                ),
                  array(
                    'type' => 'textfield',
                    'holder' => 'div',
                    'class' => '',
                    'heading' => __( 'Width' ),
                    'param_name' => 'Width',
                    'value' => __( '' ),
                    'description' => __( 'Width of the PDF File' ),
                ),
                 array(
                    'type' => 'textfield',
                    'holder' => 'div',
                    'class' => '',
                    'heading' => __( 'Height' ),
                    'param_name' => 'Hight',
                    'value' => __( '' ),
                    'description' => __( 'Hight of the PDF File' ),
                ),
                 array(
                    'type' => 'checkbox',
                    'holder' => 'div',
                    'class' => '',
                    'heading' => __( 'Veiw Pagination?' ),
                    'param_name' => 'pages_allow',
                    'value' => __( '' ),
                    'description' => __( 'If enabled this will show the number of the PDF file pages.' ),
                ),
                 array(
                    'type' => 'checkbox',
                    'holder' => 'div',
                    'class' => '',
                    'heading' => __( 'Animation?' ),
                    'param_name' => 'animation_allow',
                    'value' => __( '' ),
                    'description' => __( 'If enabled this will show the number of the PDF file pages.' ),
                ),
                 
               
            )
        )
    );
}
add_action( 'vc_before_init', 'v_pfbk_flip_book_component' );
 
/**
* Function for displaying PDF functionality
*
* @param array $atts    - the attributes of shortcode
* 
*
* @return string $o - the HTML content for this shortcode.
*/
function v_pfbk_flip_book_function( $atts, $content ) {
    $atts = shortcode_atts(
    array(
        'height' => '',
        'width' => '',
        'pages_allow' => false,
        'animation_allow' => false,
        'src' => '',
    ), $atts, 'pdf_flip_book_kenrys'
);
 
    
    

 if($atts['pages_allow'] == false) {$page = 'pages="off"';}
 if($atts['animation_allow'] == false) {$animation = 'animation="on"';}

    // start box
ob_start();
    echo do_shortcode('[pfbk_pdf_flipbook src="'.$atts['src'].'" '.$page.' width="'.$atts['width'].'" height="'.$atts['height'].'" '.$animation.' ]');
$o  = ob_get_clean();
return $o;
 
}
add_shortcode( 'v_pfbk_flip_book', 'v_pfbk_flip_book_function' );
}


if(isset($_GET['pfbk_file']) ) {
    $file_url = $_GET['pfbk_file'];
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
readfile($file_url);
}
