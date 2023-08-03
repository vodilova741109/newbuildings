<?php get_header();?>
<div class="page-section">

    <div class="page-content">
        <div class="page-loop__wrapper loop tab-content tab-content__active">
            <?php get_template_part( 'template-parts/content/content-complex', get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' ) ); ?>
            <?php 
                global $wp_query;                
                // текущая страница
                $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
                // максимум страниц
                $max_pages = $wp_query->max_num_pages;
             
                // если текущая страница меньше, чем максимум страниц, то выводим кнопку
                if( $paged < $max_pages ) {
                    echo '<div id="loadmore" class="show-more">
                        <button href="#" class="show-more__button " data-max_pages="' . $max_pages . '" data-paged="' . $paged . '" ><span class="show-more__button-icon"></span>Показать ещё</button>
                    </div>';
                }
                ?>
        </div>

        <?php get_template_part( 'template-parts/content/content-map') ?>
    </div>
    <?php get_template_part( 'template-parts/content/content-filter') ?>

</div>

</div>




<?php
// get_sidebar();
get_footer();