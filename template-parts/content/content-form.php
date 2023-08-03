<?php
        echo '<form action="" method="POST" id="filter">';
 
        // категории
        if( $terms = get_terms( array( 'taxonomy' => 'category', 'orderby' => 'name' ) ) ) { // как я уже говорил, для простоты возьму рубрики category, но get_terms() позволяет работать с любой таксономией
            echo '<select name="categoryfilter"><option>Выберите категорию...</option>';
            foreach ( $terms as $term ) {
                echo '<option value="' . $term->term_id . '">' . $term->name . '</option>'; // в качестве value я взял ID рубрики
            }
            echo '</select>';
        }
         
        // минимальная/максимальная цена
        echo '<input type="text" name="cena_min" placeholder="Минимальная цена" />';
        echo '<input type="text" name="cena_max" placeholder="Максимальная цена" />';
         
        // дата по возрастанию/убыванию
        echo '<label><input type="radio" name="date" value="ASC" /> Дата: по возрастанию</label>';
        echo '<label><input type="radio" name="date" value="DESC" selected="selected" /> Дата: по убыванию</label>';
         
        // чекбокс только с фото
        echo '<label><input type="checkbox" name="featured_image" /> Только с миниатюрой</label>';
         
         
        echo '<button>Применить фильтр</button><input type="hidden" name="action" value="myfilter">
        </form>
        <div id="response"><!-- тут фактически можете вывести посты без фильтрации --></div>';
        ?>