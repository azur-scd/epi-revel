<!DOCTYPE html>
<script type="text/javascript" async src="//epi-revel.univ-cotedazur.fr/application/libraries/Mathjax/MathJax.js?config=TeX-MML-AM_CHTML" async></script>
<!-- importation de mathjax pour les formules mathématiques à afficher, et cnfiguration du module -->
<script type='text/x-mathjax-config'>MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['$$','$$']]}});</script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<html class="<?php echo get_theme_option('Style Sheet'); ?>" lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes" />
    <?php if ($description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>
    <link rel="shortcut icon" href="<?php echo WEB_ROOT;?>/themes/epirevel/images/favicon.ico" type="image/ico" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css2?family=Alegreya:wght@400;700&display=swap' rel='stylesheet'>

    <?php
    if (isset($title)) {
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <?php fire_plugin_hook('public_head',array('view'=>$this)); ?>
    <!-- Stylesheets -->
    <?php
    queue_css_file(array('iconfonts', 'skeleton','style'));

    echo head_css();
    ?>
    <!-- JavaScripts -->
    <?php queue_js_file('vendor/selectivizr', 'javascripts', array('conditional' => '(gte IE 6)&(lte IE 8)')); ?>
    <?php queue_js_file('vendor/respond'); ?>
    <?php queue_js_file('vendor/jquery-accessibleMegaMenu'); ?>
    <?php queue_js_file('epirevel'); ?>
    <?php queue_js_file('globals'); ?>
    <?php echo head_js(); ?>
</head>
 <?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
    <a href="#content" id="skipnav"><?php echo __('Skip to main content'); ?></a>
    <?php fire_plugin_hook('public_body', array('view'=>$this)); ?>

    <header id='header' class="hidden-print">
    <div id="brandBar" class="clearboth">
        <div class="container-fluid">
            <div class="row">
                <nav class="navbar" role="navigation" style="margin-bottom: 0;">
                    <!-- Logo -->
                    <div class="navbar-header">
                        <a href="/" class="navbar-brand logo-desktop"><img src="<?php echo WEB_ROOT;?>/themes/epirevel/images/logo_epirevel.png" alt="épi-revel"></a>                        
                    </div>
                    <!-- /Logo -->
                    <!-- navbar-dropdowns -->
                    <ul class="nav navbar-top-links navbar-right">
                        <!-- Search Bar -->
                        <div class="input-group mb-3">
                            <form name="search-form" action="/search" method='get'>
                            <input type="text" class="form-control input-text" name="query" placeholder="Rechercher dans épi-revel" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <button class="btn btn-outline-warning btn-lg" type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>   
                        <!-- /Search Bar -->
                    </ul>
                    <!-- /navbar-dropdowns -->
                    <!-- Langue -->
                    <li class="dropdown dropdown-langue">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-translate" viewBox="0 0 16 16">
  <path d="M4.545 6.714 4.11 8H3l1.862-5h1.284L8 8H6.833l-.435-1.286H4.545zm1.634-.736L5.5 3.956h-.049l-.679 2.022H6.18z"/>
  <path d="M0 2a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v3h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-3H2a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H2zm7.138 9.995c.193.301.402.583.63.846-.748.575-1.673 1.001-2.768 1.292.178.217.451.635.555.867 1.125-.359 2.08-.844 2.886-1.494.777.665 1.739 1.165 2.93 1.472.133-.254.414-.673.629-.89-1.125-.253-2.057-.694-2.82-1.284.681-.747 1.222-1.651 1.621-2.757H14V8h-3v1.047h.765c-.318.844-.74 1.546-1.272 2.13a6.066 6.066 0 0 1-.415-.492 1.988 1.988 0 0 1-.94.31z"/>
</svg>
                            </a>
                            <div class="dropdown-menu popover bottom">
                                <span class="arrow"></span>
                                <div>
                                    <p class="mb-0">
                                        <a class="btn btn-info btn-block btn-sm" href="<?php echo WEB_ROOT;?>/setlocale?locale=en_US" >SWITCH TO ENGLISH</a>
                                    </p>                                    
                                </div>
                                <div>
                                    <p class="mb-0">
                                        <a class="btn btn-info btn-block btn-sm" href="<?php echo WEB_ROOT;?>/setlocale?locale=fr">PASSER EN FRANCAIS</a>
                                    </p>
                                </div>
                            </div>
                        </li>
                        <!-- /Langue -->
                </nav>
            </div>
        </div>
    </div>
    <!-- /BrandBar -->
</header>

<div class="wrapper">
    <div id="content" role="main" tabindex="-1">

<?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
