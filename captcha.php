<?php
session_start();
 
/*
* File: CaptchaSecurityImages.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 03/08/06
* Updated: 07/02/07
* Requirements: PHP 4/5 with GD and FreeType libraries
* Link: http://www.white-hat-web-design.co.uk/articles/php-captcha.php
* 
* This program is free software; you can redistribute it and/or 
* modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation; either version 2 
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful, 
* but WITHOUT ANY WARRANTY; without even the implied warranty of 
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
* GNU General Public License for more details: 
* http://www.gnu.org/licenses/gpl.html
*
*/
 
class CaptchaSecurityImages {

	/**
	 * Name:	Load the MySQL Connection
	 * Usage:	$this->db();
	 * Usage:	$this->mysql->function();
	 **/
	public function db(){
		include dirname(__FILE__)."/inc/class.db.php";
		$this->mysql = new db();

		return $this->mysql->connect();
	}

	function vars(){
		$this->db();

		$var = array(
			"font" 		=> $this->mysql->setting(captcha_font),
			"fontsize"	=> $this->mysql->setting(captcha_fontsize),
			"color"		=> array(
						"background"	=> $this->mysql->setting(captcha_color_background),
						"text"		=> $this->mysql->setting(captcha_color_text),
						"noise"		=> $this->mysql->setting(captcha_color_noise)),
			"lines"		=> $this->mysql->setting(captcha_lines),
			"dots"		=> $this->mysql->setting(captcha_dots),
			);
					
		return $this->var = $var;
		$this->mysql->disconnect();
	}
		

	function explodeColor($color){
		$values = explode(", ", $color);
		$colors = array(
			"r" => $values[0],
			"g" => $values[1],
			"b" => $values[2]
			);
		return $colors;
	}
 
	function generateCode($characters) {
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '23456789bcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 0;
		while ($i < $characters) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}
 
	function CaptchaSecurityImages($width='120',$height='40',$characters='6') {
		// Load the variables
		$this->vars();

		// Generate the random code
		$code = $this->generateCode($characters);

		// Font name
		$font = "./".$this->var[font];

		/* font size will be 75% of the image height
		$font_size = $height * 0.60;*/
		$font_size = $this->var[fontsize];

		// Assign a variable for the image.
		$image = imagecreate($width, $height) or die('Cannot initialize new GD image stream');

		/* set the colours */
		$b_color = $this->explodeColor($this->var[color][background]);
		$t_color = $this->explodeColor($this->var[color][text]);
		$n_color = $this->explodeColor($this->var[color][noise]);

		$background_color = imagecolorallocate(
					$image, 
					$b_color[r],
					$b_color[g],
					$b_color[b]);
		$text_color 	=   imagecolorallocate(
					$image,
					$t_color[r],
					$t_color[g],
					$t_color[b]);
		$noise_color 	=   imagecolorallocate(
					$image,
					$n_color[r],
					$n_color[g],
					$n_color[b]);

		/* generate random dots in background */
		for ($i=0; $i < $this->var[dots] ; $i++) {
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}

		/* generate random lines in background */
		for ($i=0; $i < $this->var[lines]; $i++) {
			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}

		/* create textbox and add text */
		$textbox = imagettfbbox($font_size, 0, $font, $code) or die('Error in imagettfbbox function');
		$x = ($width - $textbox[4])/2;
		$y = ($height - $textbox[5])/2;
		imagettftext($image, $font_size, 0, $x, $y, $text_color, $font , $code) or die('Error in imagettftext function');

		/* output captcha image to browser */
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
		$_SESSION['security_code'] = $code;
	}
}
 
$width = isset($_GET['width']) && $_GET['width'] < 600 ? $_GET['width'] : '120';
$height = isset($_GET['height']) && $_GET['height'] < 200 ? $_GET['height'] : '40';
$characters = isset($_GET['characters']) && $_GET['characters'] > 2 ? $_GET['characters'] : '6';
 
$captcha = new CaptchaSecurityImages($width,$height,$characters);
 
?>