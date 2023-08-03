<?php
/**
 * Template part for displaying complex
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage buildings
 * @since buildings 1.0
 */

?>

<ul class="page-loop with-filter">


    <?php 
    global $post; 
    $post_id = get_the_ID(); //получаем ID текущего поста  
    //  получаем все таксономии по ID поста, с указанием слага самой таксаномии
     $cur_terms = get_the_terms( $post_id, 'typecom');
     if( is_array( $cur_terms ) ){
        // через цикл выводим все элементы
        foreach( $cur_terms as $cur_term ){
          // получаем slug каждого элемента
        $metca = $cur_term->slug;         
         // в массив помещаем все метки
        $array[] = $metca;       
        }             
      }   
    //   можно вывести массив 
    $query=new WP_Query( [  
        'post_type' => 'complex',    
        'posts_per_page' => 3,
        'order'        => 'ASC',
        // 'typecom' => [$array[0], $array[1], $array[2]],
        'typecom'=> $metca, 
        'paged' => $paged,
    ] );

    if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
    $query->the_post();
    ?>
    <li class="page-loop__item wow animate__animated animate__fadeInUp" data-wow-duration="0.8s">

        <a href="<?php the_permalink(); ?>" class="favorites-link favorites-link__add" title="Добавить в Избранное"
            role="button">
            <span class="icon-heart"><span class="path1"></span><span class="path2"></span></span>
        </a>
        <a href="<?php the_permalink(); ?>" class="page-loop__item-link">


            <div class="page-loop__item-image">

                <img src="<?php echo get_template_directory_uri(). '/assets/img/building.jpg'?>" alt="" />

                <div class="page-loop__item-badges">
                    <?php 
                    if(get_field('usluga_0')) {?>
                    <span class="badge">Услуга 0%</span>
                    <?php }?>
                    <span class="badge">Комфорт+</span>
                </div>

            </div>

            <div class="page-loop__item-info">

                <h3 class="page-title-h3"><?php the_title() ?></h3>

                <p class="page-text">Срок сдачи до <?php the_field('date'); ?></p>

                <div class="page-text to-metro">
                    <span class="icon-metro icon-metro--<?php echo (get_field('metro_new')['color']); ?>"></span>
                    <span class="page-text"><?php echo (get_field('metro_new')['metro_select']); ?>
                        <span>
                            <?php echo (get_field('metro_new')['metro_min']); ?>
                        </span></span>
                    <span class="icon-walk-icon"></span>
                </div>

                <span class="page-text text-desc"><?php the_field('address'); ?></span>

            </div>

        </a>

    </li>
    <?php 
    }
} else {
    // Постов не найдено
}
wp_reset_postdata(); // Сбрасываем $post
?>
</ul>