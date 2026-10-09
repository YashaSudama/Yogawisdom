<?php
/**
 * Template part for displaying posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy
 */

$params = $theme->get('post', []);

if (!is_single()) {
    $params->merge($theme->get('blog', []));
}

// Image
$attrs_image['class'][] = $params['image_align'] == 'top' ? 'blog-image' : 'uk-margin-medium-bottom';

// Header
$attrs_header['class'][] = is_single() ? 'blog-content single-blog-content': 'blog-content';

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

    $date = $params['date'] ? '<span>' . get_post_date() . '</span>' : '';
    $author = $params['author'] ? get_post_author() : '';
    $category = $params['categories'] ? get_the_category_list(__('<span class="divider"> | </span>')) : '';
    $comments = !post_password_required() && (comments_open() || get_comments_number());

    if ($date || $author || $category || $comments) {

        switch ($params['meta_style']) {

            case 'list':

                $attrs_header['class'][] = $params['header_align'] ? 'uk-text-center' : ''; ?>

                <div class="blog-meta-info">

                    <?php foreach (array_filter([$date, $author]) as $part) : ?>

                        <span><?= $part ?></span>

                    <?php endforeach ?>

                    <?php if ($category) : ?>

                        <span class="blog-category"><?= $category ?></span>

                    <?php endif ?>

                    <?php if ($comments) : ?>

                        <span><?php comments_popup_link(__('0 Comments', 'yootheme'), __('1 Comment', 'yootheme'), __('% Comments', 'yootheme')) ?></span>

                    <?php endif ?>

                        <span class="post-reading-time"><?= get_field('reading-time'); ?></span>

                </div>

                <?php
                break;

            default: // sentence ?>

                <p class="uk-article-meta">

                <?php

                if ($author && $date) {
                    printf(__('Written by %s on %s.', 'yootheme'), get_post_author(), get_post_date());
                } elseif ($author) {
                    printf(__('Written by %s.', 'yootheme'), get_post_author());
                } elseif ($date) {
                    printf(__('Written on %s.', 'yootheme'), get_post_date());
                }

                ?>

                <?php

                if ($category && $categories = get_the_category_list(__(', '))) {
                    printf(__('Posted in %1$s.', 'yootheme'), $categories);
                }

                ?>

                <?php

                if ($comments) {
                    comments_popup_link(__('Leave a Comment'), __('1 Comment', 'yootheme'), __('% Comments', 'yootheme'));
                }

                ?>
                </p>
                <?php
        }

    }

}; ?>

<article id="post-<?php the_ID() ?>" <?php post_class() ?> typeof="Article">

    <meta property="name" content="<?= esc_html(get_the_title()) ?>">
    <meta property="author" typeof="Person" content="<?= esc_html(get_the_author()) ?>">
    <meta property="dateModified" content="<?= get_the_modified_date('c') ?>">
    <meta class="uk-margin-remove-adjacent" property="datePublished" content="<?= get_the_date('c') ?>">

    <?php if ($params['image_align'] == 'top' && !is_single()) : ?>

        <?= $image($attrs_image) ?>

    <?php endif ?>

    <div<?= get_attrs($attrs_header) ?>>

        <?php if (!is_single()) : ?> <div class="blog-post-info"> <?php endif ?>

            <?php if ($params['meta_align'] == 'top' && !is_single()) : ?>

                <?= $meta() ?>

            <?php endif ?>

            <?php

                if (is_single()) { ?>

                    <div class="post-top-block">
                        <div class="post-icon-name">
                            <img src="/wp-content/uploads/2025/11/paramatma-article-icon.svg">
                            <span><?= __('Article', 'yootheme-child') ?></span>
                        </div>
                        <div class="post-categories">
                            <span><?= get_the_category_list(__('<span class="divider"> | </span>')); ?></span>
                        </div>
                    </div>
                    <div class="post-date"><span><?= date_i18n('j F, Y', strtotime(get_the_date('Y-m-d'))); ?></span></div>

                    <?php the_title('<h1 class="uk-article-title uk-margin-remove-top">', '</h1>');
                    
                } else {
                    the_title('<h2 class="uk-article-title uk-margin-remove-top"><a class="uk-link-reset" href="' . esc_url(get_permalink()) . '">', '</a></h2>');
                }

            ?>

            <?php if ($params['image_align'] == 'top' && is_single()) : ?>

                <?= $image($attrs_image) ?>

            <?php endif ?>

            <?php if ($params['meta_align'] == 'bottom') : ?>

                <?= $meta() ?>

            <?php endif ?>

            <?php if ($params['image_align'] == 'between') : ?>

                <?= $image($attrs_image) ?>

            <?php endif ?>

            <div<?= get_attrs($attrs_content) ?> property="text"><?php if (is_single()) the_content(''); else the_excerpt(); ?></div>

            <?php if (is_single()) : ?> 

                <div class="post-copy-share-block">
                    <div class="post-copy-link">
                        <a id="copy-link-btn" class="copy-link-btn">
                            <span><?= __('Copy link', 'yootheme-child'); ?></span>
                            <img src="/wp-content/uploads/2025/11/paramatma-copy-link.svg">
                        </a>
                    </div>
                    <div class="post-share">
                        <span><?= __('Share:', 'yootheme-child'); ?></span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" 
                           target="_blank" 
                           rel="noopener">
                           <img src="/wp-content/uploads/2025/11/paramatma-facebook-share.svg"></a>
                        <a href="https://t.me/share/url?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" 
                           target="_blank" 
                           rel="noopener">
                           <img src="/wp-content/uploads/2025/11/paramatma-telegram-share.svg"></a>
                    </div>
                </div>

            <?php endif ?>

        <?php if (!is_single()) : ?> </div> <?php endif ?>
        
        <?php if (!is_single()) : ?>

            <div class="blog-post-btn">
                <a href="<?= esc_url(get_permalink()); ?>" rel="nofollow">
                    <?= __('Read more', 'yootheme-child'); ?>
                    <img src="/wp-content/uploads/2025/10/paramatma-arrow-blog.svg">
                </a>

                <?php if ($edit = get_edit_post_link()) : ?>

                    <p class="edit-post"><a href="<?= esc_url($edit) ?>"><?= sprintf(__('%1$s Edit', 'yootheme'), '<span uk-icon="pencil"></span>') ?></a></p>

                <?php endif ?>

            </div>

        <?php endif ?>

        <?php if ($params['tags'] && $tags = get_the_tag_list('', __(', '))) : ?>

            <p<?= get_attrs($attrs_tags) ?>><?php printf(__('Tags: %1$s', 'yootheme'), $tags) ?></p>

        <?php endif ?>

        <?php if (!is_single() and $readmore = get_readmore()) : ?>

            <p<?= get_attrs($attrs_button_container) ?>>
                <a<?= get_attrs($attrs_button) ?> href="<?= get_permalink() ?>"><?= $readmore ?></a>
            </p>

        <?php endif ?>

        <?php if (is_single()) {
            wp_link_pages(['before' => '<div class="uk-margin-medium">' . __('Pages:') . '<ul class="uk-pagination">', 'after'  => '</ul></div>']);
        } ?>

        <?php if (is_single() && $params['navigation']) : ?>

            <ul class="uk-pagination">

                <?php if ($prev = get_previous_post_link('%link', sprintf(__('%1$s Previous', 'yootheme'), '<span uk-pagination-previous></span>'))) : ?>
                    <li><?= $prev ?></li>
                <?php endif ?>

                <?php if ($next = get_next_post_link('%link', sprintf(__('Next %1$s', 'yootheme'), '<span uk-pagination-next></span>'))) : ?>
                    <li class="uk-margin-auto-left"><?= $next ?></li>
                <?php endif ?>
                
            </ul>

        <?php endif ?>

        <?php if (is_single() && get_the_author_meta('description')) : ?>

            <hr class="uk-margin-medium-top">

            <div class="uk-grid-medium" uk-grid>
                <div class="uk-width-auto@m">
                    <?= get_avatar(get_the_author_meta('user_email')) ?>
                </div>
                <div class="uk-width-expand@m">
                    <h4 class="uk-margin-small-bottom"><?php the_author() ?></h4>
                    <div><?php the_author_meta('description') ?></div>
                </div>
            </div>

            <hr>

        <?php endif ?>

    </div>

</article>
