<?php

use CRE\Recommendation\Normalizer;

if (!defined('ABSPATH')) {
    exit;
}

// Expect $recommendations to be a flat array of post IDs
if (empty($recommendations) || !is_array($recommendations)) {
    return;
}

// Helper: get basic card data for a car
function cre_get_vehicle_card_data(int $post_id): array {
    $data = Normalizer::vehicleData($post_id);

    return [
        'id'        => $post_id,
        'title'     => get_the_title($post_id),
        'link'      => get_permalink($post_id),
        'thumbnail' => get_the_post_thumbnail($post_id, 'medium_large', ['class' => 'cre-card__image']),
        'price'     => $data['price'] ? number_format_i18n($data['price']) : '',
        'year'      => $data['year'] ?: '',
        'body'      => $data['body_style'] ?: '',
        'fuel'      => $data['fuel'] ?: '',
    ];
}

?>

<div class="cre-recommendations">
    <section class="cre-section">
        <div class="cre-section__carousel swiper">
            <div class="swiper-wrapper">
                <?php foreach ($recommendations as $post_id) :
                    $card = cre_get_vehicle_card_data($post_id);
                ?>
                    <div class="swiper-slide">
                        <article class="cre-card">
                            <a href="<?php echo esc_url($card['link']); ?>" class="cre-card__thumb">
                                <?php echo $card['thumbnail']; ?>
                            </a>
                            <div class="cre-card__body">
                                <h3 class="cre-card__title">
                                    <a href="<?php echo esc_url($card['link']); ?>">
                                        <?php echo esc_html($card['title']); ?>
                                    </a>
                                </h3>
                                <div class="cre-card__meta">
                                    <?php if ($card['price']) : ?>
                                        <span class="cre-card__price">
                                            <?php
                                            $symbol = function_exists('get_woocommerce_currency_symbol')
                                                ? get_woocommerce_currency_symbol()
                                                : '';
                                            echo esc_html($symbol . $card['price']);
                                            ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($card['year']) : ?>
                                        <span class="cre-card__badge">
                                            <?php echo esc_html($card['year']); ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($card['body']) : ?>
                                        <span class="cre-card__badge">
                                            <?php echo esc_html($card['body']); ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($card['fuel']) : ?>
                                        <span class="cre-card__badge">
                                            <?php echo esc_html(ucfirst($card['fuel'])); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cre-section__nav">
                <button class="cre-nav cre-nav--prev" type="button" aria-label="<?php esc_attr_e('Previous cars', 'cre'); ?>">
                    ‹
                </button>
                <button class="cre-nav cre-nav--next" type="button" aria-label="<?php esc_attr_e('Next cars', 'cre'); ?>">
                    ›
                </button>
            </div>

            <div class="cre-section__pagination swiper-pagination"></div>
        </div>
    </section>
</div>

