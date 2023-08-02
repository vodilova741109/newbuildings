<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package buildings
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="robots" content="noindex">
    <title>Metro City | Новостройки</title>
    <?php wp_head(); ?>
</head>

<?php wp_body_open(); ?>

<main class="main">

    <div class="container">

        <div class="page-top">

            <div class="page-breadcrumb" itemprop="breadcrumb">
                <?php
                $nav = wp_nav_menu('echo=0');
                $nav = preg_replace('#<li\s(.+)><a\s(.+</a>)</li>#siU', '<a $1 $2', $nav);
                preg_match_all('#(<a.+/a>)#siU', $nav, $matches);
                $nav = implode(' > ', $matches[1]);
                echo $nav;            ?>
            </div>



        </div>