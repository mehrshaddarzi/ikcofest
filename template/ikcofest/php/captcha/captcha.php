<?php  if ( ! session_id() ) { session_start();}  ?>
<?php

function hextorgb($hex, $alpha = false) {
    $hex      = str_replace('#', '', $hex);
    $length   = strlen($hex);
    $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
    $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
    $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
    if ( $alpha ) {
        $rgb['a'] = $alpha;
    }
    return $rgb;
}


$captcha = function() {
    //Set the image width and height
    $width = 100;
    $height = 40; 
	
	//ABCDEFGHIJKLMNOPQRSTUVWXYZ
	$chars = "0123456789";
	//$chars = "abcdefghijklmnopqrstuvwxyz0123456789";

	$text = substr(str_shuffle($chars),0,5);
	$_SESSION['captcha-text-form-site'] =$text;

    //Create the image resource 
    $image = ImageCreate($width, $height);  

    //We are making three colors, white, black and gray
    $white = ImageColorAllocate($image, 255, 255, 255);

    //Backgroung color
    $bg = hextorgb($_GET['bg']);
    $black = ImageColorAllocate($image, $bg['r'], $bg['g'], $bg['b']);
    $grey = ImageColorAllocate($image, $bg['r'], $bg['g'], $bg['b']);

    //Make the background black 
    ImageFill($image, 0, 0, $black); 

    //Add randomly generated string in white to the image
    $font = 'opensans.ttf'; //YOUR FONT SIZE
    //ImageString($image, 40, 32, 12, $text, $white);
    imagettftext($image, 18, 0, 25, 26, $white, $font, $text);

    //Throw in some lines to make it a little bit harder for any bots to break 
    ImageRectangle($image,0,0,$width-1,$height-1,$grey);


   // imageline($image, 0, $height/2, $width, $height/2, $grey); 
    //imageline($image, $width/2, 0, $width/2, $height, $grey); 
 
    //Tell the browser what kind of file is come in 
    header("Content-Type: image/jpeg");

    //Output the newly created image in jpeg format 
    ImageJpeg($image);
   
    //Free up resources
    ImageDestroy($image);
};

//Send a generated image to the browser
$captcha();
exit();

?>