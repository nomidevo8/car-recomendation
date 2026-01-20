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
function cre_get_vehicle_card_data(int $post_id): array
{
    $data = Normalizer::vehicleData($post_id);

    return [
        'id' => $post_id,
        'title' => get_the_title($post_id),
        'link' => get_permalink($post_id),
        'thumbnail' => get_the_post_thumbnail($post_id, 'medium_large', ['class' => 'cre-card__image']),
        'price' => $data['price'] ? number_format_i18n($data['price']) : '',
        'year' => $data['year'] ?: '',
        'body' => $data['body_style'] ?: '',
        'fuel' => $data['fuel'] ?: '',
        // 'rating'    => $data['rating'] ?: 4, 
    ];
}

?>

<div class="cre-recommendations">
    <section class="cre-section splide" role="group" aria-label="Car Recommendations">
        <div class="splide__track">
            <ul class="splide__list">
                <?php foreach ($recommendations as $post_id):
                    $card = cre_get_vehicle_card_data($post_id);
                    ?>
                    <li class="splide__slide">
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
                                <!-- Star Rating -->
                                <div class="cre-card__rating">
                                    <?php
                                    // $rating = isset($card['rating']) ? intval($card['rating']) : 4;
                                    // for ($i = 1; $i <= 5; $i++) {
                                    //     if ($i <= $rating) {
                                    //         echo '<span class="cre-star cre-star--filled">★</span>';
                                    //     } else {
                                    //         echo '<span class="cre-star cre-star--empty">★</span>';
                                    //     }
                                    // }
                                    ?>
                                </div>

                                <!-- More Details Button -->
                                <a href="<?php echo esc_url($card['link']); ?>" class="cre-card__button">
                                    <?php esc_html_e('More Details', 'cre'); ?>
                                </a>
                            </div>
                        </article>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Splide will auto-generate arrows and pagination -->
    </section>
</div>

