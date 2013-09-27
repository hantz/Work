<?php
session_start(); 
session_regenerate_id(); 

if ( !isset( $_SESSION['token'] ) ) { 
	$csrf_token = sha1( uniqid( rand(), true ) ); 
	$_SESSION['csrf_token'] = $csrf_token; 
	$_SESSION['csrf_token_time'] = time(); 
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>contact</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
// <![CDATA[
jQuery(document).ready(function(){
	$('#contactform').submit(function(){				  
		var action = $(this).attr('action');
		$.post(action, { 
			name: $('#name').val(),
			email: $('#email').val(),
			company: $('#company').val(),
			subject: $('#subject').val(),
			message: $('#message').val(),
			csrf_token: $('#csrf_token').val()
		},
			function(data){
				$('#contactform #submit').attr('disabled','');
				$('.response').remove();
				$('#contactform').before('<p class="response">'+data+'</p>');
				$('.response').slideDown();
				if(data=='Message sent!') $('#contactform').slideUp();
			}
		); 
		return false;
	});
});
// ]]>
</script>
</head>
<body>
<div class="main">
  <div class="header">
    <div class="block_header">
      <div class="logo"><a href="index.html"><img src="images/logo.gif" width="225" height="99" border="0" alt="logo" /></a></div>
      <div class="click">Click here to live Support 
Tell Free <a href="#">1-866-123-675</a></div>
<div class="clr"></div>
      <div class="menu_resize">
      <div class="menu">
        <ul>
          <li><a href="index.html"><span>Home</span></a></li>
          <li><a href="about.html"><span> About Us</span></a></li>
          <li><a href="services.html"><span>Services</span></a></li>
          <li><a href="services.html"><span>Portfolio</span></a></li>
          <li><a href="contact.php" class="active"><span> Contact Us </span></a></li>
        </ul>
      </div>
      <div class="search">
        <form id="form1" name="form1" method="post" action="">
          <label><span>
            <input name="q" type="text" class="keywords" id="textfield" maxlength="50" value="Search..." />
            </span>
            <input name="b" type="image" src="images/search.gif" class="button" />
          </label>
        </form>
      </div>
      <div class="clr"></div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <div class="slider_top2">
    <div class="header_text2">
      <h2>Contact us</h2>
      <p>Lorem Ipsum as their default model text, and a search for uncover many web sites still in their infancy. </p>
    </div>
  </div>
    <div class="clr"></div>
   <div class="body2">
    <div class="body_resize">
      <div class="left">
        <h2>Contact to Our Website</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip exea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
        <div class="bg"></div>
        <form action="contact_commit.php" method="post" id="contactform">
          <ol>
            <li>
              <label for="name">First Name * </label>
              <input id="name" name="name" class="text" />
            </li>
            <li>
              <label for="email">Your email * </label>
              <input id="email" name="email" class="text" />
            </li>
            <li>
              <label for="company">Company</label>
              <input id="company" name="company" class="text" />
            </li>
            <li>
              <label for="subject">Subject</label>
              <input id="subject" name="subject" class="text" />
            </li>
            <li>
              <label for="message">Message * </label>
              <textarea id="message" name="message" rows="6" cols="50"></textarea>
            </li>
            <li class="buttons">
              <input id="csrf_token" type="hidden" name="csrf_token" value="<?php echo "$csrf_token"; ?>" />
              <input type="image" name="imageField" id="imageField" src="images/send.gif" />
            </li>
          </ol>
        </form>
        </div>
      <div class="right">
      <img src="images/twitter.gif" alt="picture" class="floated" />
      <div class="clr"></div>
        <h2>Get in touch.</h2>
        <div class="bg"></div>
        <p>Feel free to contact me or please fill up below in the following details and I will be in touch shortly.</p>
    
    <div class="bg"></div>
     <p><strong>Address</strong>:      1458 Sample Road, Greenvalley, 12<br />
       <strong>Telephone</strong>:   +123-1234-5678<br />
       <strong>FAX</strong>:                +458-4578<br />
       <strong>Others</strong>:          +301 - 0125 - 01258<br />
       <strong>E-mail</strong>:            mail@yoursitename.com</p>
    </div>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
  <div class="footer">
  <div class="footer_resize">
  <p class="leftt">© Copyright 2009. Your Site Name Dot Com. All Rights Reserved<br />
    <a href="#">Home</a> | <a href="#">Contact</a> | <a href="#">RSS </a></p>
    <p class="rightt"><a href="#"><strong>Design by DreamTemplate </strong></a></p>
    <div class="clr"></div>
  <div class="clr"></div>
</div>
</div>
</div>


</body>
</html>
