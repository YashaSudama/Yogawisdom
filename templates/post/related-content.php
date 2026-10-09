<?php
/**
 * Template part for displaying posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy
 */

$params = $theme->get('post', []);

// Image
$attrs_image['class'][] = $params['image_align'] == 'top' ? 'blog-image' : 'uk-margin-medium-bottom';

// Header
$attrs_header['class'][] = 'blog-content';

// Content
$attrs_content['class'][] = $params['content_align'] ? 'uk-text-center' : '';
$attrs_content['class'][] = $params['content_dropcap'] ? 'uk-dropcap' : '';
$attrs_content['class'][] = 'blog-post-description';

// Tags
$attrs_tags['class'][] = $params['header_align'] ? 'uk-text-center' : '';

// Button
$attrs_button['class'][] = "uk-button uk-button-{$params['button_style']}";
$attrs_button_container['class'][] = $params['header_align'] ? 'uk-text-center' : '';
$attrs_button_container['class'][] = 'uk-margin-medium';

/*
 * Image template
 */
$image = function ($attr) { ?>

    <?php if ($thumbnail = get_the_post_thumbnail('', 'post-thumbnail', ['property' => 'url'])) : ?>

        <div<?= get_attrs($attr) ?> property="image" typeof="ImageObject">

            <?php if (is_single()) : ?>

                <?= $thumbnail ?>

            <?php else : ?>

                <a href="<?php the_permalink() ?>"><?= $thumbnail ?></a>

            <?php endif ?>

        </div>

    <?php endif ?>

<?php };

/*
 * Meta template
 */
$meta = function () use ($params) {
    $category = $params['categories'] ? get_the_category_list(__('<span class="divider"> | </span>')) : '';

    if ($category) : ?>

        <div class="blog-meta-info">

            <?php if ($category) : ?>

                <span class="blog-category"><?= $category ?></span>

            <?php endif ?>

                <span class="post-reading-time"><?= get_field('reading-time'); ?></span>

        </div>

    <?php endif ?>

<?php } ?>

<article id="post-<?php the_ID() ?>" <?php post_class() ?> typeof="Article">

    <meta property="name" content="<?= esc_html(get_the_title()) ?>">
    <meta property="author" typeof="Person" content="<?= esc_html(get_the_author()) ?>">
    <meta property="dateModified" content="<?= get_the_modified_date('c') ?>">
    <meta class="uk-margin-remove-adjacent" property="datePublished" content="<?= get_the_date('c') ?>">

    <?= $image($attrs_image) ?>

    <div<?= get_attrs($attrs_header) ?>>

        <div class="blog-post-info">

            <?= $meta() ?>

            <?php the_title('<h6 class="uk-article-title uk-margin-remove-top"><a class="uk-link-reset" href="' . esc_url(get_permalink()) . '">', '</a></h6>'); ?>

            <div<?= get_attrs($attrs_content) ?> property="text"><?php the_excerpt(); ?></div>

        </div> 

        <div class="blog-post-btn">
            <a href="<?= esc_url(get_permalink()); ?>" rel="nofollow">
                <?= __('Read more', 'yootheme-child'); ?>
                <img src="/wp-content/uploads/2025/10/paramatma-arrow-blog.svg">
            </a>

            <?php if ($edit = get_edit_post_link()) : ?>

                <p class="edit-post"><a href="<?= esc_url($edit) ?>"><?= sprintf(__('%1$s Edit', 'yootheme'), '<span uk-icon="pencil"></span>') ?></a></p>

            <?php endif ?>

        </div>

    </div>

</article>
