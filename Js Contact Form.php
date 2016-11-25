<?php
/*
Plugin Name: Js Contact Form
Plugin URI: https://cxrana.com
Description: Java Script Contact Form,with attachment support and empty form validation. at first go to plugin editor and (Js Contact Form.php)files and search "yourname@mail.com" then replace it with your own email address. Now create a new page at your WordPress site and insert this code [contact_form]
Author: ahrana 
Version: 1.0
Author URI: https://cxrana.com
*/

function contact_form_markup() {

$form_action    = get_permalink();
$author_default = $_COOKIE['comment_author_'.COOKIEHASH];
$email_default  = $_COOKIE['comment_author_email_'.COOKIEHASH];

if ( ($_SESSION['contact_form_success']) ) {
$contact_form_success = '<p style="color: green">Thank you for Your Messages.</p>';
unset($_SESSION['contact_form_success']);
}

$markup = <<<EOT

<div id="cform">

	{$contact_form_success}
     
   <form onsubmit="return validateForm(this);" action="{$form_action}" method="post" enctype="multipart/form-data" style="text-align: left">
   
   <p><input type="text" name="author" id="author" value="{$author_default}" size="22" /> <label for="author"> Your Name *</label></p>
   <p><input type="text" name="email" id="email" value="{$email_default}" size="22" /> <label for="email"> E mail *</label></p>
   <p><input type="text" name="subject" id="subject" value="" size="22" /> <label for="subject"> Subject *</label></p>
   <div id="message"><p><textarea name="message" id="message" cols="70%" rows="10"></textarea></p>
   </div>
   <p><label for="attachment">File/photos</label> <input type="file" name="attachment" id="attachment" /></p>
   <div id="send">
   <input name="send" type="submit" id="send" value="Send" />
   </div>
   <input type="hidden" name="contact_form_submitted" value="1">
   
   </form>
   
</div>

EOT;

return $markup;

}

add_shortcode('contact_form', 'contact_form_markup');

function contact_form_process() {

session_start();

 if ( !isset($_POST['contact_form_submitted']) ) return;

 $author  = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
 $email   = ( isset($_POST['email']) )   ? trim(strip_tags($_POST['email'])) : null;
 $subject = ( isset($_POST['subject']) ) ? trim(strip_tags($_POST['subject'])) : null;
 $message = ( isset($_POST['message']) ) ? trim(strip_tags($_POST['message'])) : null;

 if ( $author == '' ) wp_die('Error 1: Write your Name please.'); 
 if ( !is_email($email) ) wp_die('Error 2: Type your Email address please.');
 if ( $subject == '' ) wp_die('Error 3: Write a Subject First.');
 
 //we will add e-mail sending support here soon
 
require_once ABSPATH . WPINC . '/class-phpmailer.php';
$mail_to_send = new PHPMailer();

$mail_to_send->FromName = $author;
$mail_to_send->From     = $email;
$mail_to_send->Subject  = $subject;
$mail_to_send->Body     = $message;

$mail_to_send->AddReplyTo($email);
$mail_to_send->AddAddress('yourname@mail.com'); //contact form destination e-mail

if ( !$_FILES['attachment']['error'] == 4 ) { //something was send
	
	if ( $_FILES['attachment']['error'] == 0 && is_uploaded_file($_FILES['attachment']['tmp_name']) )
	
		$mail_to_send->AddAttachment($_FILES['attachment']['tmp_name'], $_FILES['attachment']['name']);
	
	else 
		
		wp_die('Error: Somethimg was wrong(Please Try again later)');
		
}

if ( !$mail_to_send->Send() ) wp_die('Error : Mail Sending Failed,please check your contact form destination e-mail address,login dashboard>Go to plugin Editor>Js Contact Form.php and search "yourname@mail.com" then replace it with your own email address. - ' . $mail_to_send->ErrorInfo);

$_SESSION['contact_form_success'] = 1;

 
 header('Location: ' . $_SERVER['HTTP_REFERER']);
 exit();

} 

add_action('init', 'contact_form_process');

function contact_form_js() { ?>

<script type="text/javascript">
function validateForm(form) {

	var errors = '';
	var regexpEmail = /\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/;
	if (!form.author.value) errors += "Error 1 :  please write your name.\n";
	if (!regexpEmail.test(form.email.value)) errors += "Error 2 :  your e-mail address.\n";
	if (!form.subject.value) errors += "Error 3 :  a subject.\n";

	if (errors != '') {
		alert(errors);
		return false;
	}
	
return true;
	
}
</script>

<?php }

add_action('wp_head', 'contact_form_js');
add_action( 'wp_enqueue_scripts', 'safely_add_stylesheet' );

    /**
     * Add stylesheet to the page
     */
    function safely_add_stylesheet() {
        wp_enqueue_style( 'prefix-style', plugins_url('style.css', __FILE__) );
    }
	function add_customizer_styles()
{
    $bgcolor = get_theme_mod( 'background-color' );
    $custom_css = "
            body
            {
                    background: {$bgcolor};
            }";
    wp_add_inline_style( 'inline-custom-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'add_customizer_styles' );
?>