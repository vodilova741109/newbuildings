<?php
/**
 * buildings functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package buildings
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}
//если функция не существует
if ( ! function_exists( 'buildings_setup' ) ) :
	/**
     * то создадим ее
	 */

	function buildings_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on buildings, use a find and replace
		 * to change 'buildings' to the name of your theme in all the template files.
		 */
		// подключение файлов перевода
		load_theme_textdomain( 'buildings', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		
        add_theme_support( 'post-thumbnails' );
		
		/**
		 * /
		 * Enqueue scripts and styles.
		 */
		function buildings_scripts() {
			wp_enqueue_style( 'style', get_stylesheet_uri() );
			wp_enqueue_style('Fonts', get_template_directory_uri() . '/assets/fonts/icomoon/icon-font.css');
			wp_enqueue_style( 'buildings-theme', get_template_directory_uri() . '/assets/css/style.min.css', 'style', time());
			wp_enqueue_style( 'animate', get_template_directory_uri() . '/assets/libs/animate/animate.min.css', 'style', time());
			

			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', 'https://code.jquery.com/jquery-2.2.4.min.js');
			wp_enqueue_script( 'jquery' );
			
			wp_enqueue_script( 'map', 'https://api-maps.yandex.ru/2.1/?apikey=f7f5866c-fcab-4da8-94d7-cdbdb39c7d22&lang=ru_RU');
			wp_enqueue_script('poper', get_template_directory_uri() . '/assets/libs/bootstrap/js/popper.min.js', null, time(), true);
			wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/libs/bootstrap/js/bootstrap.min.js', null, time(), true);
			wp_enqueue_script('ofi', get_template_directory_uri() . '/assets/libs/ofi/ofi.min.js', null, time(), true);
			wp_enqueue_script('wowjs', get_template_directory_uri() . '/assets/libs/wowjs/wow.min.js', null, time(), true);
			wp_enqueue_script('script', get_template_directory_uri() . '/assets/js/scripts.js', null, time(), true);
          			
		}
		add_action( 'wp_enqueue_scripts', 'buildings_scripts' );
		
        // загрузка постов AJAX
		add_action( 'wp_enqueue_scripts', 'true_loadmore_scripts' ); 
		function true_loadmore_scripts() {						
		 
			 wp_register_script( 
				'true_loadmore', 
				get_template_directory_uri() . '/assets/js/loadmore.js', 
				array( 'jquery' ),
				time() // не кэшируем файл, убираем эту строчку после завершение разработки
			);
		 
			wp_localize_script( 
				'true_loadmore', 
				'myajax', 
				array( 'url' => admin_url( 'admin-ajax.php' ) )
			);
		 
			wp_enqueue_script( 'true_loadmore' );
		}

		add_action( 'wp_ajax_loadmore', 'true_loadmore' );		
		add_action( 'wp_ajax_nopriv_loadmore', 'true_loadmore' );		
		function true_loadmore() {
		
			$paged = ! empty( $_POST[ 'paged' ] ) ? $_POST[ 'paged' ] : 1;
			$paged++;
		
			$args = array(
				'paged' => $paged,
				'post_status' => 'publish'
			);
			query_posts( $args );	
			// echo '<pre>';
			// var_dump($args);
			// echo '</pre>';

			get_template_part( 'template-parts/content/content-complex', get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' ) );
		
			
			// while( have_posts() ) : the_post();
 
			// get_template_part( 'template-parts/content/content-complex', get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' ) );
	 
			// endwhile;
	 
		die;
		
		
		}
		//  конец загрузки постов AJAX

		//  фильтр
		add_action( 'wp_enqueue_scripts', 'truemisha_jquery_scripts' ); 
		function truemisha_jquery_scripts() {
			
			wp_register_script( 'filter', get_template_directory_uri() . '/assets/js/filter.js', array( 'jquery' ), time(), true );
			wp_enqueue_script( 'filter' );
		
		}      
		// wp_localize_script( 'filter', 'true_obj', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		add_action( 'wp_ajax_myfilter', 'true_filter_function' ); 
		add_action( 'wp_ajax_nopriv_myfilter', 'true_filter_function' );
		
		function true_filter_function(){
		 
			$args = array(
				'orderby' => 'date', // сортировка по дате у нас будет в любом случае (но вы можете изменить/доработать это)
				'order'	=> $_POST[ 'date' ] // ASC или DESC
			);
		 
			// для таксономий
			if( isset( $_POST[ 'categoryfilter' ] ) ) {
				$args[ 'tax_query' ] = array(
					array(
						'taxonomy' => 'category',
						'field' => 'id',
						'terms' => $_POST[ 'categoryfilter' ]
					)
				);
			}
		 
			// создаём массив $args[ 'meta_query' ] если указана хотя бы одна цена или отмечен чекбокс
			if( 
				isset( $_POST[ 'cena_min' ] ) 
				|| isset( $_POST[ 'cena_max' ] ) 
				|| isset( $_POST[ 'featured_image' ] ) && 'on' == $_POST[ 'featured_image' ]
			) {
				$args[ 'meta_query' ] = array( 'relation' => 'AND' );
			 }
		 
			// условие 1: цена больше $_POST[ 'cena_min' ]
			if( isset( $_POST[ 'cena_min' ] ) ) {
				$args[ 'meta_query' ][] = array(
					'key' => 'cena',
					'value' => $_POST[ 'cena_min' ],
					'type' => 'numeric',
					'compare' => '>'
				);
			}
		 
			// условие 2: цена меньше $_POST[ 'cena_max' ]
			if( isset( $_POST[ 'cena_max' ] ) ) {
				$args[ 'meta_query' ][] = array(
					'key' => 'cena',
					'value' => $_POST[ 'cena_max' ],
					'type' => 'numeric',
					'compare' => '<'
				);
			}
		 
			// условие 3: миниатюра имеется
			if( isset( $_POST[ 'featured_image' ] ) && 'on' == $_POST[ 'featured_image' ] ) {
				$args[ 'meta_query' ][] = array(
					'key' => '_thumbnail_id',
					'compare' => 'EXISTS'
				);
			}
		 
			query_posts( $args );
		 
			if ( have_posts() ) {
					  while ( have_posts() ) : the_post();
					// тут вывод шаблона поста, например через get_template_part()
						  echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
				endwhile;
			} else {
				echo 'Ничего не найдено';
			}
		 
			die();
		}
		//  конец фильтра
		/**
		 * Подключение сайдбара
		 *
		 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
		 */
		// регистрация сайдбара
		function register_my_widgets(){
			register_sidebar( array(
				'name'          => esc_html__( 'Sidebar', 'theme_example' ),
				'id'            => 'sidebar-1',
				'description'   => esc_html__( 'Add widgets here.', 'theme_example' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );
			register_sidebar( array(
				'name' => 'Боковая панель на странице ЖК',
				'id' => '-main-sidebar',
				'description' => 'Выводиться как боковая панель только на странице ЖК',
				'before_widget' => '<li class="homepage-widget-block">',
				'after_widget' => '</li>',
				'before_title' => '<h2 class="widgettitle">',
				'after_title' => '</h2>',
			) );
			
			
		}
		add_action( 'widgets_init', 'register_my_widgets' );

		
		## отключаем создание миниатюр файлов для указанных размеров
		add_filter( 'intermediate_image_sizes', 'delete_intermediate_image_sizes' );
		function delete_intermediate_image_sizes( $sizes ){
			// размеры которые нужно удалить
			return array_diff( $sizes, [
				'1536x1536',
				'2048x2048',
			] );
		}
		// регистрация меню
		register_nav_menus([
			'header_menu' => 'Меню в шапке',
			'footer_menu' => 'Меню в подвале',		
			
		]);
		// Регистрация произвольного типа записей
		add_action( 'init', 'register_post_types' );
		function register_post_types(){
			register_post_type( 'complex', [
				'label'  => null,
				'labels' => [
					'name'               => __('Residential complexes', 'buildings'),  // основное название для типа записи
					'singular_name'      => __('complex', 'buildings' ), // название для одной записи этого типа
					'add_new'            => __('Add complex', 'buildings' ), // для добавления новой записи
					'add_new_item'       => __('Adding a complex', 'buildings' ), // заголовка у вновь создаваемой записи в админ-панели.
					'edit_item'          => __('Editing a complex', 'buildings' ), // для редактирования типа записи
					'new_item'           => __('New complex', 'buildings' ), // текст новой записи
					'view_item'          => __('Watch Residential complexes', 'buildings' ), // для просмотра записи этого типа.
					'search_items'       => __('Search for Residential complexes', 'buildings' ), // для поиска по этим типам записи
					'not_found'          => __('Not found', 'buildings' ), // если в результате поиска ничего не было найдено
					// 'not_found_in_trash' => __('Not found in cart', 'buildings' ), // если не было найдено в корзине
					'parent_item_colon'  => '', // для родителей (у древовидных типов)
					'menu_name'          => __('Сomplex', 'buildings' ), // название меню
				],
				'description'         => __('Residential complexes section', 'buildings' ),
				'public'              => true,
				// 'publicly_queryable'  => null, // зависит от public
				// 'exclude_from_search' => null, // зависит от public
				// 'show_ui'             => null, // зависит от public
				// 'show_in_nav_menus'   => null, // зависит от public
				'show_in_menu'        => true, // показывать ли в меню адмнки
				// 'show_in_admin_bar'   => null, // зависит от show_in_menu
				'show_in_rest'        => true, // добавить в REST API. C WP 4.7 Редактировать запись в админке
				'rest_base'           => null, // $post_type. C WP 4.7
				'menu_position'       => 5,
				'menu_icon'           => 'dashicons-admin-home',
				'capability_type'   => 'post',
				//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
				//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
				'hierarchical'        => true,
				'supports'            =>  array('title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats','page-attributes'), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
				'taxonomies'          => [],
				'has_archive'         => true,
				'rewrite'             => true,
				'query_var'           => true,
				'preview'             => true,
			] );
			
		}
		// хук, через который подключается функция
		// регистрирующая новые таксономии (create_complex_taxonomies)
		add_action( 'init', 'create_complex_taxonomies' );
		// функция, создающая 2 новые таксономии "typecom" и "period" для постов типа "complex"
		function create_complex_taxonomies(){
			// Добавляем древовидную таксономию 'type' (как категории)
			register_taxonomy('typecom', array('complex', 'post'), array(
				'hierarchical'  => true,
				'labels'        => array(
					'name'              => _x( 'Types', 'taxonomy general name', 'buildings' ),
					'singular_name'     => _x( 'Type', 'taxonomy singular name', 'buildings' ),
					'search_items'      =>  __( 'Search Types', 'buildings' ),
					'all_items'         => __( 'All Types', 'buildings' ),
					'parent_item'       => __( 'Parent Type', 'buildings' ),
					'parent_item_colon' => __( 'Parent Type:', 'buildings' ),
					'edit_item'         => __( 'Edit Type', 'buildings' ),
					'update_item'       => __( 'Update Type', 'buildings' ),
					'add_new_item'      => __( 'Add New Type', 'buildings' ),
					'new_item_name'     => __( 'New Type Name', 'buildings' ),
					'menu_name'         => __( 'Type', 'buildings' ),
				),				
				'show_ui'       => true,
				'query_var'     => true,	
				'show_in_rest'  => true, 						
				'rewrite'       => array( 'slug' => 'typecom' ), // свой слаг в URL
			));					

			// Добавляем НЕ древовидную таксономию 'period' (как метки)
			register_taxonomy('period', 'complex',array(
				'hierarchical'  => false,
				'labels'        => array(
					'name'                        => _x( 'Periods', 'taxonomy general name', 'buildings' ),
					'singular_name'               => _x( 'Period', 'taxonomy singular name', 'buildings' ),
					'search_items'                =>  __( 'Search Period', 'buildings' ),
					'popular_items'               => __( 'Popular Period', 'buildings' ),
					'all_items'                   => __( 'All Periods', 'buildings' ),
					'parent_item'                 => null,
					'parent_item_colon'           => null,
					'edit_item'                   => __( 'Edit Period', 'buildings' ),
					'update_item'                 => __( 'Update Period', 'buildings' ),
					'add_new_item'                => __( 'Add New Period', 'buildings' ),
					'new_item_name'               => __( 'New Period Name', 'buildings' ),
					'separate_items_with_commas'  => __( 'Separate Periods with commas', 'buildings' ),
					'add_or_remove_items'         => __( 'Add or remove Periods', 'buildings' ),
					'choose_from_most_used'       => __( 'Choose from the most used Periods', 'buildings' ),
					'menu_name'                   => __( 'Periods', 'buildings' ),
				),
				'show_ui'       => true,
				'query_var'     => true,
				'show_in_rest'  => true, 
				'rewrite'       => array( 'slug' => 'periode' ), // свой слаг в URL
			));		
		}
	}

	
endif;
add_action( 'after_setup_theme', 'buildings_setup' );







/*
 * "Хлебные крошки" для WordPress
 * автор: Dimox
 * версия: 2019.03.03
 * лицензия: MIT
*/
function the_breadcrumbs() {

	/* === ОПЦИИ === */
	$text['home']     = 'Главная'; // текст ссылки "Главная"
	// $text['cat_parent']     = 'Категории'; // текст ссылки "Категории"
	$text['category'] = '%s'; // текст для страницы рубрики
	$text['search']   = 'Результаты поиска по запросу "%s"'; // текст для страницы с результатами поиска
	$text['tag']      = 'Записи с тегом "%s"'; // текст для страницы тега
	$text['type']      = 'Записи с типом "%s"'; // текст для страницы типов комплексов
	$text['author']   = 'Статьи автора %s'; // текст для страницы автора
	$text['404']      = 'Ошибка 404'; // текст для страницы 404
	$text['page']     = 'Страница %s'; // текст 'Страница N'
	$text['cpage']    = 'Страница комментариев %s'; // текст 'Страница комментариев N'

	$wrap_before    = '<div class="bread-crumbs_wrapp" itemscope itemtype="http://schema.org/BreadcrumbList">'; // открывающий тег обертки
		$wrap_after     = '</div><!-- .breadcrumbs -->'; // закрывающий тег обертки
		$sep            = '<span class="breadcrumbs__separator"> > </span>'; // разделитель между "крошками"
		$before         = '<div class="bread-crumbs-item">'; // тег перед текущей "крошкой"
		$after          = '</div>'; // тег после текущей "крошки"

	$show_on_home   = 0; // 1 - показывать "хлебные крошки" на главной странице, 0 - не показывать
	$show_home_link = 1; // 1 - показывать ссылку "Главная", 0 - не показывать
	$show_current   = 1; // 1 - показывать название текущей страницы, 0 - не показывать
	$show_last_sep  = 1; // 1 - показывать последний разделитель, когда название текущей страницы не отображается, 0 - не показывать
	/* === КОНЕЦ ОПЦИЙ === */
	
	global $post;
	$home_url       = home_url('/');	
	$link           = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
	$link          .= '<a class="breadcrumbs__link" href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a>';
	$link          .= '<meta itemprop="position" content="%3$s" />';
	$link          .= '</span>';
	$parent_id      = ( $post ) ? $post->post_parent : '';
	$home_link      = sprintf( $link, $home_url, $text['home'], 1 );

	if ( is_home() || is_front_page() ) {

		if ( $show_on_home ) echo $wrap_before . $home_link . $wrap_after;

	} else {

		$position = 0;

		echo $wrap_before;

		if ( $show_home_link ) {
			$position += 1;
			echo $home_link;
		}

		if ( is_category() ) {
			$parents = get_ancestors( get_query_var('cat'), 'category' );
			foreach ( array_reverse( $parents ) as $cat ) {
				$position += 1;
				if ( $position > 1 ) echo $tax;
				echo $sep;
				echo $before . sprintf( $text['cat_parent']) . $after;
				echo $sep;
				echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
			}
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				$cat = get_query_var('cat');
				echo $before . sprintf( $text['cat_parent']) . $after;
				echo $sep;
				echo $sep . sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_current ) {
					if ( $position >= 1 ) echo $sep;
					// echo get_the_archive_title();
					echo $before . sprintf( $text['cat_parent']) . $after;
					echo $sep;
					echo $before . sprintf( $text['category'], single_cat_title( '', true ) ) . $after;
				} elseif ( $show_last_sep ) echo $sep;
			}

		} elseif ( is_search() ) {
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				if ( $show_home_link ) echo $sep;
				echo sprintf( $link, $home_url . '?s=' . get_search_query(), sprintf( $text['search'], get_search_query() ), $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_current ) {
					if ( $position >= 1 ) echo $sep;
					echo $before . sprintf( $text['search'], get_search_query() ) . $after;
				} elseif ( $show_last_sep ) echo $sep;
			}

		} elseif ( is_year() ) {
			if ( $show_home_link && $show_current ) echo $sep;
			if ( $show_current ) echo $before . get_the_time('Y') . $after;
			elseif ( $show_home_link && $show_last_sep ) echo $sep;

		} elseif ( is_month() ) {
			if ( $show_home_link ) echo $sep;
			$position += 1;
			echo sprintf( $link, get_year_link( get_the_time('Y') ), get_the_time('Y'), $position );
			if ( $show_current ) echo $sep . $before . get_the_time('F') . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( is_day() ) {
			if ( $show_home_link ) echo $sep;
			$position += 1;
			echo sprintf( $link, get_year_link( get_the_time('Y') ), get_the_time('Y'), $position ) . $sep;
			$position += 1;
			echo sprintf( $link, get_month_link( get_the_time('Y'), get_the_time('m') ), get_the_time('F'), $position );
			if ( $show_current ) echo $sep . $before . get_the_time('d') . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( is_single() && ! is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				$position += 1;
				$post_type = get_post_type_object( get_post_type() );
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->labels->name, $position );
				if ( $show_current ) echo $sep . $before . get_the_title() . $after;
				elseif ( $show_last_sep ) echo $sep;
			} else {
				$cat = get_the_category(); $catID = $cat[0]->cat_ID;
				$parents = get_ancestors( $catID, 'category' );
				$parents = array_reverse( $parents );
				$parents[] = $catID;
				foreach ( $parents as $cat ) {
					$position += 1;
					if ( $position > 1 ) echo $sep;
					echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
				}
				if ( get_query_var( 'cpage' ) ) {
					$position += 1;
					echo $sep . sprintf( $link, get_permalink(), get_the_title(), $position );
					echo $sep . $before . sprintf( $text['cpage'], get_query_var( 'cpage' ) ) . $after;
				} else {
					if ( $show_current ) echo $sep . $before . get_the_title() . $after;
					elseif ( $show_last_sep ) echo $sep;
				}
			}

		} elseif ( is_post_type_archive() ) {
			$post_type = get_post_type_object( get_post_type() );
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->label, $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_home_link && $show_current ) echo $sep;
				if ( $show_current ) echo $before . $post_type->label . $after;
				elseif ( $show_home_link && $show_last_sep ) echo $sep;
			}

		} elseif ( is_attachment() ) {
			$parent = get_post( $parent_id );
			$cat = get_the_category( $parent->ID ); $catID = $cat[0]->cat_ID;
			$parents = get_ancestors( $catID, 'category' );
			$parents = array_reverse( $parents );
			$parents[] = $catID;
			foreach ( $parents as $cat ) {
				$position += 1;
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
			}
			$position += 1;
			echo $sep . sprintf( $link, get_permalink( $parent ), $parent->post_title, $position );
			if ( $show_current ) echo $sep . $before . get_the_title() . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( is_page() && ! $parent_id ) {
			if ( $show_home_link && $show_current ) echo $sep;
			if ( $show_current ) echo $before . get_the_title() . $after;
			elseif ( $show_home_link && $show_last_sep ) echo $sep;

		} elseif ( is_page() && $parent_id ) {
			$parents = get_post_ancestors( get_the_ID() );
			foreach ( array_reverse( $parents ) as $pageID ) {
				$position += 1;
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_page_link( $pageID ), get_the_title( $pageID ), $position );
			}
			if ( $show_current ) echo $sep . $before . get_the_title() . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( is_tag() ) {
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				$tagID = get_query_var( 'tag_id' );
				echo $sep . sprintf( $link, get_tag_link( $tagID ), single_tag_title( '', false ), $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_home_link && $show_current ) echo $sep;
				if ( $show_current ) echo $before . sprintf( $text['tag'], single_tag_title( '', false ) ) . $after;
				elseif ( $show_home_link && $show_last_sep ) echo $sep;
			}

		} elseif ( is_author() ) {
			$author = get_userdata( get_query_var( 'author' ) );
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				echo $sep . sprintf( $link, get_author_posts_url( $author->ID ), sprintf( $text['author'], $author->display_name ), $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_home_link && $show_current ) echo $sep;
				if ( $show_current ) echo $before . sprintf( $text['author'], $author->display_name ) . $after;
				elseif ( $show_home_link && $show_last_sep ) echo $sep;
			}

		} elseif ( is_404() ) {
			if ( $show_home_link && $show_current ) echo $sep;
			if ( $show_current ) echo $before . $text['404'] . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( has_post_format() && ! is_singular() ) {
			if ( $show_home_link && $show_current ) echo $sep;
			echo get_post_format_string( get_post_format() );
		}

		echo $wrap_after;

	}
} // end of the_breadcrumbs()
// * Конец "Хлебные крошки" для WordPress

// оптимизация скорости загрузки