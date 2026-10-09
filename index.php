<?php
/**
 * The main template file.
 *
 * The most generic template file in the WordPress file hierarchy.
 * Used if WordPress cannot find any other matching template file.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy
 */

get_header();

?>

<?php if (have_posts()) :

    $columns = get_post_columns($theme->get('blog.column', 1), $theme->get('blog.column_order'));

    $attrs = [];
    $attrs['class'][] = 'wrapper-posts uk-child-width-1-' . count($columns) . '@m';
    $attrs['class'][] = $theme->get('blog.column_gutter') ? 'uk-grid-large' : '';
    $attrs['class'][] = $theme->get('blog.column_divider') ? 'uk-grid-divider' : '';
    $attrs['uk-grid'] = true;

    ?>

    <div class="header-blog">
        <h1><?= __('<span class="anastasiascript-font">D</span>elve into <span class="anastasiascript-font minus-left">V</span>edic wisdom with <strong>our blog</strong>', 'yootheme-child'); ?></h1>
        <p><?= __('Explore the depths of yoga and meditation', 'yootheme-child'); ?></p>
    </div>

    <div<?= get_attrs($attrs) ?>>
        
    <?php foreach ($columns as $column) : ?> 

        <?php foreach ($column as $post) {
            setup_postdata($GLOBALS['post'] = $post);

            get_template_part('templates/post/content', get_post_format());
        } ?>

    <?php endforeach ?>
    </div>

    <?php

    get_template_part('templates/pagination');

else :

    get_template_part('templates/post/content', 'none');

endif;

get_footer();