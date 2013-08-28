<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

  <title><?php echo $title; ?></title>
  
  <?php echo $meta; ?>
<!--Adds HTML5 Tags to IE7,8--> 
	<?php echo HTML::script(Controller_Master::ASSET_PATH."js/libs/modernizr-2.0.6.min.js"); ?>
    
	<?php echo HTML::script("https://ajax.googleapis.com/ajax/libs/mootools/1.4.5/mootools-yui-compressed.js"); ?>
	
    <!--[if (lt IE 9) & (!IEMobile)]>
    <?php echo HTML::script(Controller_Master::ASSET_PATH."js/libs/selectivizr-min.js"); ?>
	<![endif]-->
	<link rel="profile" href="http://gmpg.org/xfn/11" />	

	
  <!-- CSS concatenated and minified via ant build script-->
  <?php echo $css.$pcss;?>
  <!-- end CSS-->
  
  
  <!-- JS scripts concatenated and minified via ant build script-->
  <?php echo $js;?>
  <!-- end scripts-->


  <script> // Change UA-XXXXX-X to be your site's ID
    window._gaq = [['_setAccount','UAXXXXXXXX1'],['_trackPageview'],['_trackPageLoadTime']];
    Modernizr.load({
      load: ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js'
    });
  </script>
  
</head>
<body>

  <div id="container"> 
    <header id="main_header"  class="inside">
	    <nav class="main_nav"> 
            <?php echo $nav; ?>
        </nav>
    </header>
    <section id="main" class="inside">
        <?php echo $content; ?>
    </section>
    <footer id="main_footer" class="inside">
        <?php echo $footer; ?>
    </footer>
  
  </div> <!--! end of #container -->
</body>
</html>
