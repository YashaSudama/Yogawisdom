<?php
/**
 * Include Theme Functions
 *
 * @package Yootheme Child Theme
 * @subpackage Functions
 * @version 1.0.0
 */

function yootheme_setup_child_theme() {
	load_child_theme_textdomain( 'yootheme-child', get_stylesheet_directory() . '/languages' );  
}

add_action( 'after_setup_theme', 'yootheme_setup_child_theme', 99 );

function yootheme_child_assets() {

	if (!is_admin()) {
		$version = wp_get_theme()->get( 'Version' );
		wp_enqueue_style('yootheme-child', trailingslashit(get_stylesheet_directory_uri()) . 'style.css', [], $version, 'all');

		if (is_front_page() || is_single()) {
			wp_enqueue_script('yootheme-child-slick', trailingslashit( get_stylesheet_directory_uri() ) . 'js/slick/slick.js', [], $version, true);
		}
		
		if (is_front_page()) {
			wp_enqueue_style('yootheme-child-fancybox-css', trailingslashit(get_stylesheet_directory_uri()) . 'js/fancybox/fancybox.css', [], $version, 'all');
			wp_enqueue_script('yootheme-child-fancybox-js', trailingslashit( get_stylesheet_directory_uri() ) . 'js/fancybox/fancybox.js', [], $version, true);

			wp_add_inline_script(
				'yootheme-child-fancybox-js',
				"document.addEventListener('DOMContentLoaded', function() {
					Fancybox.bind('[data-fancybox]', {
						Thumbs: { autoStart: true },
						Toolbar: { display: ['zoom', 'slideShow', 'fullscreen', 'download', 'thumbs', 'close'] },
					});
				});"
			);

		}

	}

}

add_action( 'wp_enqueue_scripts', 'yootheme_child_assets', 99 );

function logo() { ?>

	<a href="/" class="uk-navbar-item uk-logo">
		<span class="logo-text-top"><?= __('Publishing House', 'yootheme-child'); ?></span>
		<span class="logo-text-bottom"><?= __('Paramatma', 'yootheme-child'); ?></span>
	</a>
	
<?php }

function output_books() {
	
	$args = array(
		'post_type'      => 'product',
		'product_cat'    => 'books',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$loop = new WP_Query($args);

	if ( $loop->have_posts() ) : ?>

		<div class="products-books-block">

			<?php while ( $loop->have_posts() ) : 
				$loop->the_post();
				
				global $product; 

				$product_id = $product->get_id();
				$gallery_ids = $product->get_gallery_image_ids();
				$main_image_id = $product->get_image_id(); ?>

				<div class="product-card book-product-card">

					<div class="product-image">
						<?= woocommerce_get_product_thumbnail('full'); ?>

						<?php if (!empty($gallery_ids) || $main_image_id) : ?>

							<a href="<?= wp_get_attachment_url($main_image_id); ?>" 
							   data-fancybox="gallery-<?= esc_attr($product_id); ?>" 
							   class="uk-button uk-button-default more-photos-btn">
								<span class="uk-text-middle"><?= __('More photos', 'yootheme-child'); ?></span>
							</a>

							<?php if (!empty($gallery_ids)) :

								foreach ($gallery_ids as $gallery_id) :
									$image_url = wp_get_attachment_url($gallery_id); ?>

									<a href="<?= esc_url($image_url); ?>" 
									   data-fancybox="gallery-<?= esc_attr($product_id); ?>" 
									   style="display:none;">
									</a>

								<?php endforeach;

							endif; ?>

						<?php endif; ?>

					</div>
					
					<div class="product-info">
						<h2 class="product-title"><?php the_title(); ?></h2>

						<div class="product-description">
							<div class="excerpt"><?php the_excerpt(); ?></div>
							<div class="content"><?php the_content(); ?></div>
							<div class="expand-collapse-block expanded">
								<span class="expand-collapse-text"><?= __('Expand', 'yootheme-child'); ?></span>
								<img src="/wp-content/uploads/2025/09/paramatma-arrow-down.svg" alt="Expand/Collapse Icon">
							</div>
						</div>

						<div class="product-price-btn">
							<div class="product-price">
								<?= $product->get_price_html(); ?>
							</div>

							<div class="product-btn">
								<button class="uk-button uk-button-primary add-to-cart-button" 
									    data-quantity="1"
									    data-product_id="<?= esc_attr( $product->get_id() ); ?>">
									<?= __('Add to cart', 'yootheme-child'); ?>
									<img src="/wp-content/uploads/2025/09/paramatma-add-shopping-cart.svg">
								</button>
							</div>
						</div>

						<div class="product-attributes">
							<?php $attributes = $product->get_attributes();

							foreach ( $attributes as $attribute ) {

								if ( $attribute->get_variation() ) {
									continue;
								}

								$name = wc_attribute_label( $attribute->get_name() );
								$value = implode( ', ', wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) ) ); ?>

								<div class="product-attribute">
									<span class="attribute-name"><?= esc_html( $name ); ?></span> 
									<span class="attribute-value"><?= esc_html( $value ); ?></span>
								</div>

							<?php } ?>

						</div>

					</div>

				</div>

			<?php endwhile; ?>

		</div>

	<?php endif;

	wp_reset_postdata();

}

function output_reviews() {
	$args = array(
		'post_type'      => 'reviews',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$loop = new WP_Query($args);

	if ( $loop->have_posts() ) : ?>

		<div class="reviews-block">

			<?php while ( $loop->have_posts() ) : 
				$loop->the_post(); ?>

				<div class="review">
					
					<div class="review-description">
						<div class="content"><?php the_content(); ?></div>
					</div>
					
					<div class="review-info">

						<div class="review-image">

							<?php if ( has_post_thumbnail() ) {
								the_post_thumbnail('medium');
							} ?>

						</div>
						<div class="review-details">
							<div class="review-name"><span><?php the_title(); ?></span></div>
							<div class="review-profession"><span><?= get_field('profession'); ?></span></div>
						</div>

					</div>

				</div>

			<?php endwhile; ?>

		</div>

	<?php endif;

	wp_reset_postdata();
}

function output_booklets() {
	$number_of_posts = wp_is_mobile() ? 4 : 5;
	$args = array(
		'post_type'      => 'product',
		'product_cat'    => 'booklets',
		'posts_per_page'  => $number_of_posts,
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$loop = new WP_Query($args);

	if ( $loop->have_posts() ) : ?>

		<div class="products-booklets-block slick-dots-outside">

			<?php while ( $loop->have_posts() ) : $loop->the_post();
				global $product; ?>

				<div class="product-booklet">

					<div class="product-image">
						<?= woocommerce_get_product_thumbnail('full'); ?>
					</div>

					<div class="product-info">

						<div class="product-title-description">
							<h6 class="product-title"><?php the_title(); ?></h6>
							<div class="product-description"><?php the_content(); ?></div>
						</div>
						<div class="product-price-attribute-btn">
							<div class="product-price-attribute">
								<div class="product-price">
									<?= $product->get_price_html(); ?>
								</div>

								<div class="product-attributes">
									<?php $attributes = $product->get_attributes();

									foreach ( $attributes as $attribute ) {

										if ( $attribute->get_variation() ) {
											continue;
										}

										$name = wc_attribute_label( $attribute->get_name() );
										$value = implode( ', ', wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) ) ); ?>

										<div class="product-attribute">
											<span><?= sprintf(_n('%s page', '%s pages', $value, 'yootheme-child'), $value); ?></span>
										</div>

									<?php } ?>

								</div>

							</div>

							<div class="product-btn">
								<button class="uk-button uk-button-secondary add-to-cart-button" 
									    data-quantity="1"
									    data-product_id="<?= esc_attr( $product->get_id() ); ?>">
									<?= __('Add to cart', 'yootheme-child'); ?>
									<svg xmlns="http://www.w3.org/2000/svg" width="13" height="14" viewBox="0 0 13 14" fill="none">
										<path d="M6.6 3.4H5C4.83 3.4 4.6875 3.34283 4.5725 3.2285C4.4575 3.11417 4.4 2.9725 4.4 2.8035C4.4 2.6345 4.4575 2.49167 4.5725 2.375C4.6875 2.25833 4.83 2.2 5 2.2H6.6V0.6C6.6 0.43 6.65717 0.2875 6.7715 0.1725C6.88583 0.0574996 7.0275 0 7.1965 0C7.3655 0 7.50833 0.0574996 7.625 0.1725C7.74167 0.2875 7.8 0.43 7.8 0.6V2.2H9.4C9.57 2.2 9.7125 2.25717 9.8275 2.3715C9.9425 2.48583 10 2.6275 10 2.7965C10 2.9655 9.9425 3.10833 9.8275 3.225C9.7125 3.34167 9.57 3.4 9.4 3.4H7.8V5C7.8 5.17 7.74283 5.3125 7.6285 5.4275C7.51417 5.5425 7.3725 5.6 7.2035 5.6C7.0345 5.6 6.89167 5.5425 6.775 5.4275C6.65833 5.3125 6.6 5.17 6.6 5V3.4ZM3.5965 13.6C3.2655 13.6 2.98333 13.4822 2.75 13.2465C2.51667 13.0108 2.4 12.7275 2.4 12.3965C2.4 12.0655 2.51783 11.7833 2.7535 11.55C2.98917 11.3167 3.2725 11.2 3.6035 11.2C3.9345 11.2 4.21667 11.3178 4.45 11.5535C4.68333 11.7892 4.8 12.0725 4.8 12.4035C4.8 12.7345 4.68217 13.0167 4.4465 13.25C4.21083 13.4833 3.9275 13.6 3.5965 13.6ZM10.7965 13.6C10.4655 13.6 10.1833 13.4822 9.95 13.2465C9.71667 13.0108 9.6 12.7275 9.6 12.3965C9.6 12.0655 9.71783 11.7833 9.9535 11.55C10.1892 11.3167 10.4725 11.2 10.8035 11.2C11.1345 11.2 11.4167 11.3178 11.65 11.5535C11.8833 11.7892 12 12.0725 12 12.4035C12 12.7345 11.8822 13.0167 11.6465 13.25C11.4108 13.4833 11.1275 13.6 10.7965 13.6ZM1.43333 2H0.6C0.43 2 0.2875 1.94283 0.1725 1.8285C0.0575 1.71417 0 1.5725 0 1.4035C0 1.2345 0.0575 1.09167 0.1725 0.975C0.2875 0.858333 0.43 0.8 0.6 0.8H1.81667C1.93889 0.8 2.04722 0.833333 2.14167 0.9C2.23611 0.966667 2.31111 1.05556 2.36667 1.16667L4.8 6.8H9.81667L11.55 2.76667C11.6056 2.65556 11.6816 2.56667 11.7782 2.5C11.8747 2.43333 11.982 2.4 12.1 2.4C12.3278 2.4 12.4986 2.48889 12.6125 2.66667C12.7264 2.84444 12.7389 3.03333 12.65 3.23333L10.9167 7.26667C10.8167 7.48889 10.6694 7.66667 10.475 7.8C10.2806 7.93333 10.0611 8 9.81667 8H4.48333L3.78333 9.2H11.4C11.57 9.2 11.7125 9.25717 11.8275 9.3715C11.9425 9.48584 12 9.6275 12 9.7965C12 9.9655 11.9425 10.1083 11.8275 10.225C11.7125 10.3417 11.57 10.4 11.4 10.4H3.8C3.33333 10.4 2.98333 10.1972 2.75 9.79167C2.51667 9.38611 2.51667 8.98889 2.75 8.6L3.61667 7.1L1.43333 2Z" fill="#201915"/>
									</svg>
								</button>
							</div>
						</div>
					</div>

				</div>

			<?php endwhile; ?>

			<?php if (wp_is_mobile()) : ?>

				<div class="product-booklet additional-mobile-product-booklet">
					<a class="uk-button uk-button-primary" href="/booklets">
						<span><?= __('View all', 'yootheme-child'); ?></span>
						<span uk-icon="arrow-right" class="uk-icon"></span>
					</a>
				</div>

			<?php endif; ?>

		</div>

	<?php endif;

	wp_reset_postdata();

}

function output_blog() {
	$number_of_posts = wp_is_mobile() ? 3 : 5;
	$args = array(
		'post_type'      => 'post',
		'posts_per_page'  => $number_of_posts,
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$loop = new WP_Query($args);

	if ( $loop->have_posts() ) : ?>

		<div class="blog-block slick-dots-outside">

			<?php while ( $loop->have_posts() ) : 
				$loop->the_post(); 
				$thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full'); 
            	$post_link = get_permalink();
				$categories = get_the_category(); ?>

				<div class="post">

					<div class="post-image"><img src="<?= esc_url($thumbnail_url); ?>"></div>

					<div class="post-info">

						<div class="post-category-reading-time">

							<?php if (!empty($categories)) : ?>

								<div class="post-categories">

									<?php foreach ($categories as $cat) : ?>

										<a href="<?= esc_url(get_category_link($cat->term_id)); ?>" class="post-category">
											<?= esc_html($cat->name); ?>
										</a>

									<?php endforeach; ?>

								</div>
								
								<?php endif; ?>
								
							<div class="post-reading-time"><span><?= get_field('reading-time'); ?></span></div>

						</div>

						<h6 class="post-title"><?php the_title(); ?></h6>
						<div class="post-description"><?php the_excerpt(); ?></div>

						<div class="post-btn">
							<a href="<?= esc_url($post_link); ?>" rel="nofollow">
								<?= __('Read more', 'yootheme-child'); ?>
								<img src="/wp-content/uploads/2025/10/paramatma-arrow-blog.svg">
							</a>
						</div>
					</div>

				</div>

			<?php endwhile; ?>

			<?php if (wp_is_mobile()) : ?>

				<div class="post additional-mobile-post">
					<a class="uk-button uk-button-primary" href="/blog">
						<span><?= __('View all', 'yootheme-child'); ?></span>
						<span uk-icon="arrow-right" class="uk-icon"></span>
					</a>
				</div>

			<?php endif; ?>

		</div>

	<?php endif;

	wp_reset_postdata();

}

function output_page_booklets() {
	
	$args = array(
		'post_type'      => 'product',
		'product_cat'    => 'booklets',
		'posts_per_page'  => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$loop = new WP_Query($args);

	if ( $loop->have_posts() ) : ?>

		<div class="products-booklets-page">

			<?php while ($loop->have_posts() ) : $loop->the_post();
				global $product; ?>

				<div class="product-card broshura-product-card">

					<div class="product-image">
						<?= woocommerce_get_product_thumbnail('full'); ?>
					</div>
					
					<div class="product-info">

						<div class="block-1">
							<h2 class="product-title"><?php the_title(); ?></h2>
							<div class="product-description"><?php the_content(); ?></div>
						</div>

						<div class="block-2">
							<div class="product-price-btn">
								<div class="product-price">
									<?= $product->get_price_html(); ?>
								</div>

								<div class="product-btn">
									<button class="uk-button uk-button-primary add-to-cart-button" 
										    data-quantity="1"
										    data-product_id="<?= esc_attr( $product->get_id() ); ?>">
										<?= __('Add to cart', 'yootheme-child'); ?>
										<img src="/wp-content/uploads/2025/09/paramatma-add-shopping-cart.svg">
									</button>
								</div>
							</div>

							<div class="product-attributes">
								<?php $attributes = $product->get_attributes();

								foreach ( $attributes as $attribute ) {

									if ( $attribute->get_variation() ) {
										continue;
									}

									$name = wc_attribute_label( $attribute->get_name() );
									$value = implode( ', ', wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) ) ); ?>

									<div class="product-attribute">
										<span class="attribute-name"><?= esc_html( $name ); ?></span> 
										<span class="attribute-value"><?= esc_html( $value ); ?></span>
									</div>

								<?php } ?>

							</div>
						</div>

					</div>

				</div>

			<?php endwhile; ?>

		</div>

	<?php endif;

	wp_reset_postdata();

}
function output_js_code() { 

	if (is_single()) { ?>
	
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				let btn = document.getElementById('copy-link-btn');

				btn.addEventListener('click', function () {
					let url = window.location.href,
						text = btn.querySelector('span');

					navigator.clipboard.writeText(url).then(function () {
						
						setTimeout(() => {
							text.textContent = '<?= __('Copied', 'yootheme-child'); ?>';
							btn.style.backgroundColor = '#cff9d1';
						}, 500);

						setTimeout(() => {
							text.textContent = '<?= __('Copy link', 'yootheme-child'); ?>';
							btn.style.cssText = '';
						}, 3000);

					});

				});

			});

		</script>

	<?php }
	
	if (!is_checkout()) { ?>

		<script>
			document.addEventListener('DOMContentLoaded', function() {
				let expand_сollapse_texts = document.querySelectorAll('.expand-collapse-block'),
					mini_cart = document.querySelectorAll('.mini-cart'),
					window_width = window.innerWidth,
					type = window_width < 1200 ? 'mobile' : 'desctop',
					mini_cart_popup_scroll = 0,
					a = 'opacity: 0.6; background-color: #fffef2; z-index: 3',
					ajax_url = '';

				if (window.wc_cart_fragments_params) {
					ajax_url = wc_cart_fragments_params.wc_ajax_url;
				} else if (window.wc_add_to_cart_params) {
					ajax_url = wc_add_to_cart_params.wc_ajax_url;
				} else if (window.woocommerce_params) {
					ajax_url = woocommerce_params.wc_ajax_url;
				} else {
					ajax_url = '/?wc-ajax=%%endpoint%%';
				}

				localStorage.setItem('open', '0');
			
				change_quantity(ajax_url, type);

				expand_сollapse_texts.forEach(item => {
					item.addEventListener('click', function() {
						let parent = item.parentElement,
							content = item.previousElementSibling,
							excerpt = item.previousElementSibling.previousElementSibling,
							text = item.querySelector('span'),	
							icon = item.querySelector('img'),
							img_block = item.closest('.book-product-card').querySelector('.product-image'),	
							img_block_height = img_block.offsetHeight;	

						img_block.style.maxHeight = img_block_height + 'px';

						if (item.classList.contains('expanded')) {
							item.classList.remove('expanded');
							text.innerHTML = '<?= __('Collapse', 'yootheme-child'); ?>';
							item.classList.add('collapsed');

							excerpt.style.display = 'none';
							excerpt.style.opacity = '0';

							content.style.display = 'block';
							content.style.opacity = '1';
							icon.style.setProperty('--rot', '180deg');

							if (window_width > 1200) parent.style.marginBottom = '40px';

						} else {
							item.classList.remove('collapsed');
							text.innerHTML = '<?= __('Expand', 'yootheme-child'); ?>';
							item.classList.add('expanded');

							content.style.display = 'none';
							content.style.opacity = '0';

							excerpt.style.display = 'block';
							excerpt.style.opacity = '1';
							icon.style.setProperty('--rot', '0deg');

							if (window_width > 959) parent.style.cssText = '';

						}

					});

				});

				jQuery(document.body).on('adding_to_cart', function(event, button, data) {
					data.popup_open = localStorage.getItem('open');
				});
				
				jQuery(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
					
					setTimeout(() => {

						change_quantity(ajax_url, type);

					}, 0);

				});

				document.addEventListener('click', async function(event) {

					if (event.target.classList.contains('mini-cart') || 
						event.target.parentElement.classList.contains('mini-cart') ||
						event.target.parentElement.parentElement.classList.contains('mini-cart')) {

						if (event.target.classList.contains('header-button') ||
							event.target.closest('.header-button')) return;

						let popup = event.target.closest('.mini-cart').nextElementSibling;

						if (popup.classList.contains('close')) {
							
							open_mini_cart_popup(popup, window_width);

						} else {

							close_mini_cart_popup(popup, window_width);
							
						}
						
					} else if (event.target.classList.contains('icon-content-mini-cart')) {

						close_mini_cart_popup(event.target.closest('.mini-cart-popup'), window_width);

					} else if (event.target.classList.contains('qty-plus')            || 
							event.target.classList.contains('qty-minus')              ||
							event.target.parentElement.classList.contains('qty-plus') || 
							event.target.parentElement.classList.contains('qty-minus')) {
						
						let background_block = event.target.closest('li').querySelector('.background-block'),
							btn = event.target.tagName === 'IMG' ? event.target.parentElement : event.target,
							wrap = btn.closest('.mini-cart-quantity'),
							input = wrap.querySelector('.qty-input'),
							key = btn.dataset.key,
							qty = parseInt(input.value) || 1;

						if (btn.classList.contains('qty-plus')) qty++;

						if (btn.classList.contains('qty-minus')) {
							qty = Math.max(1, qty - 1);

							if (input.value === '1') return;
							
						}

						input.value = qty;

						fetch_data('update_cart_item_qty', 
									ajax_url, 
									key, 
									background_block, 
									type, 
									qty,
									false);

					} else if (event.target.classList.contains('remove-from-cart') || 
							   event.target.parentElement.classList.contains('remove-from-cart')) {
						
						let background_block = event.target.closest('li').querySelector('.background-block'),
							btn = event.target.tagName === 'IMG' ? event.target.parentElement : event.target,
							key = btn.dataset.key;

						fetch_data('remove_from_cart', 
									ajax_url, 
									key, 
									background_block, 
									type, 
									false, 
									false);

					} else if (event.target.classList.contains('add-to-cart-button') || 
							   event.target.parentElement.classList.contains('add-to-cart-button')) {
						
						let btn = event.target.tagName === 'IMG' || event.target.tagName === 'svg' ? event.target.parentElement : event.target,
							quantity = '1',
							product_id = btn.dataset.product_id;

						fetch_data('add_to_cart', 
								    ajax_url, 
									false, 
									false, 
									type, 
									false,
								    product_id);

					} else {
						return;
					}

				});

			});

			document.querySelectorAll('#tm-mobile a[href^="/#"]').forEach(link => {
				link.addEventListener('click', function(event) {
					let href = this.getAttribute('href'),
						pathname = window.location.pathname;
					
					if (pathname === '/' && href.includes('#')) {
						event.preventDefault();
						let targetId = href.split('#')[1],
							target = document.getElementById(targetId);
						
						if (target) {
							UIkit.offcanvas('#tm-mobile').hide();

							UIkit.util.on('#tm-mobile', 'hidden', () => {
								target.scrollIntoView({ behavior: 'smooth', block: 'start' });
							});

						}

					}

				});

			});

			function open_mini_cart_popup(elem, window_width) {
				elem.style.setProperty('display', 'flex', 'important');
				elem.style.zIndex = '2';
				elem.classList.remove('close');
				elem.classList.add('open');

				elem.dataset.open = '1';
				localStorage.setItem('open', '1');

				setTimeout(() => {
					elem.style.opacity = '1';

					setTimeout(() => {
						elem.style.transform = 'translateX(0)';
					}, 100);

				}, 100);
			}

			function close_mini_cart_popup(elem, window_width) {

				if (window_width < 1200) {
					elem.style.transform = 'translateX(-100%)';
				} else {
					elem.style.transform = 'translateX(100%)';
				}

				elem.dataset.open = '0';
				localStorage.setItem('open', '0');

				setTimeout(() => {
					elem.style.opacity = '0';

					setTimeout(() => {
						elem.style.cssText = '';
						elem.classList.remove('open');
						elem.classList.add('close');
					}, 100);

				}, 200);

			}

			function change_fragment(selector, html, type) {

				if (selector === '.mini-cart-popup-' + type) {
					html = html.replace('mini-cart-popup-' + type + ' close', 'mini-cart-popup-' + type + ' open');
					html = html.replace('data-open="0"', 'data-open="1"');
					html = html.replace('style=""', 'style="display: flex !important; z-index: 2; opacity: 1; transform: translateX(0px);"');
				}

				return html;

			}
			
			function change_quantity(ajax_url, type) {
				let qty_inputs = document.querySelectorAll('.qty-input');
				
				if (qty_inputs.length) {

					qty_inputs.forEach(item => {
						item.addEventListener('input', async function(event) {

							if (item.tagName === 'INPUT') {
								if (item.value === '') return;
								if (item.value === '0') item.value = '1';
							}

							let background_block = event.target.closest('li').querySelector('.background-block'),
								formData = new FormData();

							fetch_data('update_cart_item_qty', 
										ajax_url, 
										item.dataset.key, 
										background_block, 
										type, 
										parseInt(item.value) || 1,
										false);
							
						});

					});

				}

			}

			async function fetch_data(url_replace, 
									  ajax_url, 
									  key, 
									  background_block, 
									  type, 
									  elem_qty,
									  product_id) {
				let url = ajax_url.replace('%%endpoint%%', url_replace),
					formData = new FormData(),
					mini_cart_popup_scroll = document.querySelector('.mini-cart-popup').scrollTop;

				if (background_block) background_block.style.cssText = 'opacity: 0.6; background-color: #fffef2; z-index: 3';
				if (key) formData.append('cart_item_key', key);
				if (elem_qty) formData.append('quantity', elem_qty);

				if (product_id) {
					formData.append('product_id', product_id);
					formData.append('quantity', '1');
				}

				let response = await fetch(url, {
					method: 'POST',
					body: formData,
				});

				if (response.ok) {
					let data = await response.json();

					if (data && data.fragments) {

						for (let selector in data.fragments) {
							let elem = document.querySelector(selector),
								html = data.fragments[selector];
							
							localStorage.getItem('open') === '1' ? html = change_fragment(selector, html, type) : html;

							if (elem) elem.outerHTML = html;

						}

						if (mini_cart_popup_scroll > 0) document.querySelector('.mini-cart-popup').scrollTop = mini_cart_popup_scroll;
						if (background_block) background_block.style.cssText = '';

					}

					change_quantity(ajax_url, type);

				} else {
					return;
				}

			}

		</script>

	<?php } else { ?>

		<script>
			let window_width = window.innerWidth;

			jQuery(document).ready( function($) {
				placeholder = '<?= __('The required field', 'yootheme-child'); ?>';
				placeholder_email = '<?= __('Invalid email address', 'yootheme-child'); ?>';
				regexp_email = /^(.+)@(.+)\.(.+)$/;
				fields = [
							{
								'name' : '#billing_first_name',
								'placeholder' : "<?= __('First name', 'yootheme-child'); ?>",
								'hidden' : false,
							}, 
							{
								'name' : '#billing_last_name',
								'placeholder' : '<?= __('Last name', 'yootheme-child'); ?>',
								'hidden' : false,
							}, 
							{
								'name' : '#billing_phone',
								'placeholder' : '<?= __('Number phone', 'yootheme-child'); ?>',
								'hidden' : false,
							}, 
							{
								'name' : '#billing_email',
								'placeholder' : '<?= __('Email', 'yootheme-child'); ?>',
								'hidden' : false,
							}, 
							{
								'name' : 'input[name="wcus_np_billing_city_name"]',
								'placeholder' : '<?= __('City', 'yootheme-child'); ?>',
								'hidden' : true,
							}, 
							{
								'name' : 'input[name="wcus_np_billing_warehouse_name"]',
								'placeholder' : '<?= __('Warehouse/poshtomat', 'yootheme-child'); ?>',
								'hidden' : true,
							}, 

						];
				
				$('form.checkout').on('checkout_place_order', function(event) {
					
					fields.forEach(function(field) {
						let input = $(field.name),
  							value = input.val().trim();

						if (value === '') {

							if (field.hidden) {
								let elem_text = input.parent().find('.zen-ui-select__value');

								elem_text.addClass('checkout-error-message');

								if (!elem_text.next().hasClass('text-error-message')) {
									elem_text.after('<div class="text-error-message"><span>' + placeholder + '</span></div>');
								}

							} else {
								input.addClass('checkout-error-message');

								if (!input.next().hasClass('text-error-message')) {
									input.after('<div class="text-error-message"><span>' + placeholder + '</span></div>');
								} else {

									if (input.is('#billing_email')) {
										input.next('.text-error-message').remove();
										input.after('<div class="text-error-message"><span>' + placeholder + '</span></div>'); 
									}

								}
								
							}

						} else {

							if (field.hidden) {
								let elem_text = input.parent().find('.zen-ui-select__value');

								elem_text.removeClass('checkout-error-message');

								if (elem_text.next('.text-error-message')) {
									elem_text.next('.text-error-message').remove();
								}

							} else {
								input.removeClass('checkout-error-message');

								if (input.next('.text-error-message')) {
									input.next('.text-error-message').remove();
								}

							}

						}

						if (input.is('#billing_email') && value !== '') {

							if (!regexp_email.test(value)) {
								input.addClass('checkout-error-message-email');
								
								if (!input.next().hasClass('text-error-message')) {
									input.after('<div class="text-error-message"><span>' + placeholder_email + '</span></div>');
								} else {
									input.next('.text-error-message').remove();
									input.after('<div class="text-error-message"><span>' + placeholder_email + '</span></div>');
								}

							} else {
								
								if (input.hasClass('checkout-error-message-email')) {
									input.removeClass('checkout-error-message-email');
									
									if (input.next('.text-error-message')) {
										input.next('.text-error-message').remove();
									}

								}

							}

						}

					});

				});

			});

			window.addEventListener('load', function() {
				let block_delivery = document.getElementById('wcus-billing-fields'),
					input_delivery = document.getElementById('shipping_method').querySelector('li:last-child');

				if (block_delivery && input_delivery) input_delivery.append(block_delivery);

			});

			if (window_width > 959) {
				let order_review = document.getElementById('order_review'),
					coord_order_review_x = order_review.getBoundingClientRect().x,
					coord_order_review_y,
					coord_order_review_bottom = order_review.getBoundingClientRect().bottom + window.pageYOffset;
					coord_order_review_width = order_review.getBoundingClientRect().width,

				window.addEventListener('scroll', function() {
					let coord_checkout_form_delivery_bottom = document.querySelector('.checkout-form-delivery').getBoundingClientRect().bottom;

					if ((coord_checkout_form_delivery_bottom) < coord_order_review_bottom) {
						order_review.style.cssText = 'margin-top: auto';
					} else {
						coord_order_review_y = order_review.getBoundingClientRect().y;
						
						order_review.style.cssText = 'width: ' + coord_order_review_width + 'px;' +
													 'position: fixed;' +
													 'left: ' + coord_order_review_x + 'px;' + 
													 'top: ' + coord_order_review_y + 'px';
					}
					
				});

				window.addEventListener('resize', function() {
					order_review.style.cssText = '';
					coord_order_review_x = order_review.getBoundingClientRect().x,
					coord_order_review_y = order_review.getBoundingClientRect().y + window.pageYOffset,
					coord_order_review_bottom = order_review.getBoundingClientRect().bottom + window.pageYOffset;
					coord_order_review_width = order_review.getBoundingClientRect().width;

					order_review.style.cssText = 'width: ' + coord_order_review_width + 'px;' +
												 'position: fixed;' +
												 'left: ' + coord_order_review_x + 'px;' + 
												 'top: ' + coord_order_review_y + 'px';
				});

			}

		</script>

	<?php } ?>

<?php }

add_action('wp_footer', 'output_js_code');

add_filter('woocommerce_currency_symbol', function($symbol, $currency) {

    if ($currency === 'UAH') {
        $symbol = __(' grn', 'yootheme-child');
    }

    return $symbol;

}, 10, 2);

add_filter('woocommerce_price_trim_zeros', '__return_true');

$type = wp_is_mobile() ? 'mobile' : 'desctop'; 

function mini_cart() {
	global $type; ?>

	<div class="mini-cart mini-cart-<?= $type; ?>">

		<?php if (WC()->cart->get_cart_contents_count() > 0) : 
			$count = wp_is_mobile() ? '(' . WC()->cart->get_cart_contents_count() . ')' : '<span>' . WC()->cart->get_cart_contents_count() . '</span>'; ?>
			<span class="count"><?= $count; ?></span>
		<?php endif; ?>

		<?php if (!wp_is_mobile()) : ?> <span class="text-mini-cart"><?=__('Cart', 'yootheme-child'); ?></span> <?php endif; ?>

		<img src="/wp-content/uploads/2025/09/paramatma-remove-shopping-cart.svg" alt="Mini Cart">

		<?php if (!wp_is_mobile()) : ?> 

			<a class="header-button uk-button uk-button-primary" href="/#our-books"><?= __('Choose your book', 'yootheme-child'); ?>
				<span uk-icon="arrow-right" 
					  class="uk-icon">
				</span>
			</a>

		<?php endif; ?>

	</div>
		
<?php }

function mini_cart_popup() { 
	global $type; 
	
	$popup_open = isset($_POST['popup_open']) && $_POST['popup_open'] === '1'; ?>

	<div class="mini-cart-popup mini-cart-popup-<?= $type; ?> <?= $popup_open ? 'open' : 'close'; ?>" 
		 data-open="<?= $popup_open ? '1' : '0'; ?>"
		 style="<?= $popup_open ? 'display: flex !important; z-index: 2; transform: translateX(0px);' : false; ?>">
		<?php woocommerce_mini_cart(); ?> 
	</div>

<?php }

function update_cart($fragments) {
	global $type;

	ob_start(); 
	mini_cart();

	$fragments['.mini-cart-' . $type] = ob_get_clean();

	ob_start();
	mini_cart_popup();

	$fragments['.mini-cart-popup-' . $type] = ob_get_clean();

	return $fragments;

}

add_filter('woocommerce_add_to_cart_fragments', 'update_cart');

remove_action('woocommerce_widget_shopping_cart_total', 'woocommerce_widget_shopping_cart_subtotal', 10);

function woocommerce_widget_shopping_cart_custom_subtotal() {
	echo  '<span class="text-subtotal">' . __( 'Total<br>to pay', 'yootheme-child' ) . '</span>' .
	WC()->cart->get_cart_subtotal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

add_action('woocommerce_widget_shopping_cart_total', 'woocommerce_widget_shopping_cart_custom_subtotal', 15);

function update_cart_item_qty_callback() {
    $cart_item_key = sanitize_text_field( $_POST['cart_item_key'] ?? '' );
    $quantity = intval( $_POST['quantity'] ?? 1 );

    if ( !$cart_item_key ) {
        wp_send_json_error();
    }

    WC()->cart->set_quantity( $cart_item_key, $quantity, true );
	WC_AJAX::get_refreshed_fragments();

}

add_action( 'wc_ajax_update_cart_item_qty', 'update_cart_item_qty_callback' );
add_action( 'wc_ajax_nopriv_update_cart_item_qty', 'update_cart_item_qty_callback' );

function change_checkout_fields($fields) {
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_postcode']); 
	
	$fields['billing']['billing_first_name']['label'] = '';
    $fields['billing']['billing_first_name']['placeholder'] = __('First name', 'yootheme-child');

    $fields['billing']['billing_last_name']['label'] = '';
    $fields['billing']['billing_last_name']['placeholder'] = __('Last name', 'yootheme-child');

    $fields['billing']['billing_phone']['label'] = '';
    $fields['billing']['billing_phone']['placeholder'] = __('Number phone', 'yootheme-child');

    $fields['billing']['billing_email']['label'] = '';
    $fields['billing']['billing_email']['placeholder'] = __('Email', 'yootheme-child');

	$fields['billing']['billing_country']['default'] = 'UA';
    $fields['billing']['billing_country']['class'][] = 'hidden';
    $fields['billing']['billing_country']['label'] = '';
    
    return $fields;

}

add_filter('woocommerce_checkout_fields', 'change_checkout_fields');

function translate_text( $translation, $text, $domain ) { 
	
	if ( $domain === 'wc-ukr-shipping-i18n' ) {
		
		if ( $text === 'Select locality' ) {
			$translation = __( 'City', 'yootheme-child' );
		} elseif ( $text === 'Select warehouse or poshtomat' ) {
			$translation = __( 'Warehouse/poshtomat', 'yootheme-child' );
		} elseif ( $text === 'Enter 3 or more characters to search' ) {
			$translation = __( 'Enter 3 or more characters', 'yootheme-child' );
		}

	}
	
	return $translation;

}

add_filter( 'gettext', 'translate_text', 10, 3 );

remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
add_action('woocommerce_checkout_after_customer_details', 'woocommerce_checkout_payment', 5);

function remove_edit_link_from_pages($actions, $page) {
    unset( $actions['edit'] );

    return $actions;

}

add_filter('page_row_actions', 'remove_edit_link_from_pages', 10, 2);













