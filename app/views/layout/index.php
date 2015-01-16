<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $title; ?></title>
        <script src="/js/jquery.min.js"></script>
        <script src="/js/jquery.showLoading.js"></script>        
        <link rel="stylesheet" type="text/css" href="/css/style.css" media="screen">
    </head>
    <body>
        <div id="alertas"></div>
        <div id="content">
		<?php echo $content; ?> 
        </div>
    </body>
    <script src="/js/scripts.js"></script>  
    <?php if(isset($js)): ?>
    	<?php if(!empty($js)): ?>
    		<script src="<?php echo $js ?>"></script>
    	<?php endif; ?>
    <?php endif; ?>

</html>