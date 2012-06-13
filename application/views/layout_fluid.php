<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="<?php echo $meta['keywords'] ?>">
    <meta name="description" content="<?php echo $meta['description'] ?>">

    <link rel="shortcut icon" href="<?php echo URL::site('favicon.png') ?>">

    <!-- Le styles -->
    <?php foreach ($styles as $style => $media): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $style ?>"
          media="<?php echo $media ?>"/>
    <?php endforeach; ?>
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
    </style>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body>

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse"
               data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="#">Project name</a>

            <div class="nav-collapse">
                <ul class="nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <?php echo $sidebar ?>
        </div>
        <div class="span9">
            <?php echo Message::display(); //display flash_message ?>
            <?php echo $content ?>
        </div>
    </div>
    <hr>

    <footer>
        <p>&copy; Company 2012</p>
    </footer>

</div>
<!--/.fluid-container-->

<!-- Load Javascripts -->
<script type="text/javascript">var BASEURL = "<?php echo URL::base() ?>";</script>
<?php foreach ($scripts as $script): ?>
<script type="text/javascript" src="<?php echo $script ?>"></script>
    <?php endforeach; ?>

</body>
</html>
