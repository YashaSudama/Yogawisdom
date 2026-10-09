<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 */

get_header();

// Container
$attrs_container = [];

if ($theme->get('post.content_width')) {
    $attrs_container['class'][] = 'uk-container uk-container-small';
}

if (have_posts()) :

    while (have_posts()) : the_post();

        get_template_part('templates/post/content', get_post_format());

        if ($attrs_container) :
            printf('<div%s>', get_attrs($attrs_container));
        endif;

        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;

        if ($attrs_container) :
            echo '</div>';
        endif;

    endwhile;

endif;

$current_id = get_the_ID();
$categories = wp_get_post_categories($current_id);

$related = new WP_Query([
    'posts_per_page' => -1,
    'post__not_in'   => [$current_id],
    'category__in'   => $categories,
]);

if ($related->have_posts()) : ?>

    <div class="related-posts-wrapper">

        <h2><?= __('You may also like', 'yootheme-child'); ?></h2>
        <div class="related-posts">

            <?php while ($related->have_posts()) : $related->the_post();

                get_template_part('templates/post/related-content', get_post_format());

            endwhile; ?>
                
        </div>
    
    </div>

    <?php wp_reset_postdata(); ?>

<?php endif; ?>

<?php get_footer();
