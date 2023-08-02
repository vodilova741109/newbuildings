<?php
/**
 * The template for displaying all single posts complex
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package buildings
 */

get_header('post');
?>
<div class="page-section">
    <div class="page-content">
        <article class="post">
            <div class="post-header">
                <h1 class="page-title-h1"><?php the_title()?></h1>
                <span><?php the_field('kompaniya'); ?></span>
                <?php $post_id = get_the_ID();?>
                <div class="article-wrapp">
                    <div class="post-header__details">
                        <div class="address"><?php the_field('address_cmp'); ?></div>
                        <?php
                        $metro = get_field_object('metro_select');                      
                        $value = $metro['value'];
                        foreach ($value as $row) : ?>
                        <div class="metro">
                            <?php  if( $row != 'Сокол 25 мин.' ) {?>
                            <span class="icon-metro icon-metro--red"></span>
                            <?= $row?>
                            <span class="icon-bus"></span>
                            <?php } ?>
                            <?php  if( $row == 'Сокол 25 мин.' ) {?>
                            <span class="icon-metro icon-metro--green"></span>
                            <?= $row?>
                            <span class="icon-bus"></span>
                            <?php } ?>

                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="post-image">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        ?>
                    <img src="<?php 
                            //должно находится внутри цикла
                            if( has_post_thumbnail() ) {
                                echo get_the_post_thumbnail_url('', 'clay-thumb');
                            }
                            else {
                                echo get_template_directory_uri().'/assets/img/img-default.png';
                            }
                            ?>">
                    <!-- получаем id поста -->

                    <div class="page-loop__item-badges">
                        <span class="badge">Услуги 0%</span>
                        <span class="badge">Комфорт+</span>
                    </div>

                    <a href="#" class="favorites-link favorites-link__add" title="Добавить в Избранное" role="button">
                        <span class="icon-heart"><span class="path1"></span><span class="path2"></span></span>
                    </a>

                </div>

                <h2 class="page-title-h1">Характеристики ЖК</h2>

                <ul class="post-specs">
                    <li>
                        <span class="icon-building"></span>
                        <div class="post-specs__info">
                            <span>Класс жилья</span>
                            <p>Комфорт</p>
                        </div>
                    </li>
                    <li>
                        <span class="icon-brick"></span>
                        <div class="post-specs__info">
                            <span>Конструктив</span>
                            <p>Монолит-кирпич</p>
                        </div>
                    </li>
                    <li>
                        <span class="icon-paint"></span>
                        <div class="post-specs__info">
                            <span>Отделка</span>
                            <p>
                                Чистовая
                                <span class="tip tip-info" data-toggle="popover" data-placement="top"
                                    data-content="And here's some amazing content. It's very engaging. Right?">
                                    <span class="icon-prompt"></span>
                                </span>
                            </p>
                        </div>
                    </li>
                    <li>
                        <span class="icon-calendar"></span>
                        <div class="post-specs__info">
                            <span>Срок сдачи</span>
                            <p><?php the_field('date'); ?></p>
                        </div>
                    </li>
                    <li>
                        <span class="icon-ruller"></span>
                        <div class="post-specs__info">
                            <span>Высота потолков</span>
                            <p><?php the_field('height'); ?></p>
                        </div>
                    </li>
                    <li>
                        <span class="icon-parking"></span>
                        <div class="post-specs__info">
                            <span>Подземный паркинг</span>
                            <p>Присутствует</p>
                        </div>
                    </li>
                    <li>
                        <span class="icon-stair"></span>
                        <div class="post-specs__info">
                            <span>Этажность</span>
                            <p><?php  echo (get_field('etazhi_0')['etazhi_1']); ?> -
                                <?php  echo (get_field('etazhi_0')['etazhi_2']); ?></p>
                        </div>
                    </li>
                    <li>
                        <span class="icon-wallet"></span>
                        <div class="post-specs__info">
                            <span>Ценовая группа</span>
                            <p>Выше среднего</p>
                        </div>
                    </li>
                    <li>
                        <span class="icon-rating"></span>
                        <div class="post-specs__info">
                            <span>Рейтинг</span>
                            <p><?php the_field('rejting'); ?></p>
                        </div>
                    </li>
                </ul>


                <?php the_content(); ?>

                <?php
							
                endwhile; // End of the loop.			
                ?>

                <h2 class="page-title-h1">Карта</h2>

                <div class="post-map" id="post-map" style="width: 100%; height: 300px;"></div>


        </article>
    </div>
    <div class="page-filter"></div>
</div>


<?php get_footer();