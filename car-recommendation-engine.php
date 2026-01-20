<?php
/**
 * Plugin Name: Car Recommendation Engine
 * Description: Intelligent recommendation system for car listings.
 * Version: 1.0.0
 * Author: Noman
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CRE_PATH', plugin_dir_path(__FILE__));
// define('CRE_VERSION', '1.0.0');
define('CRE_VERSION', time());

require_once CRE_PATH . 'inc/Recommendation/Normalizer.php';
require_once CRE_PATH . 'inc/Recommendation/CategoryRules.php';
require_once CRE_PATH . 'inc/Recommendation/QueryBuilder.php';
require_once CRE_PATH . 'inc/Recommendation/ScoreCalculator.php';
require_once CRE_PATH . 'inc/Recommendation/VehicleRecommender.php';

/**
 * Enqueue frontend assets for the recommendation carousels
 */
function cre_enqueue_assets() {
    if (!is_singular('car-listing')) {
        return;
    }

    // Splide Carousel - Modern & Professional
    wp_enqueue_style(
        'cre-splide',
        'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css',
        [],
        '4.1.4'
    );

    wp_enqueue_script(
        'cre-splide',
        'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js',
        [],
        '4.1.4',
        true
    );

    // Plugin styles
    wp_enqueue_style(
        'cre-styles',
        plugins_url('assets/css/recommendations.css', __FILE__),
        ['cre-splide'],
        CRE_VERSION
    );

    // Plugin script to init carousels
    wp_enqueue_script(
        'cre-frontend',
        plugins_url('assets/js/recommendations.js', __FILE__),
        ['cre-splide'],
        CRE_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'cre_enqueue_assets');

/**
 * Shortcode: [car_recommendations]
 */
function cre_recommendations_shortcode(): string {
    if (!is_singular('car-listing')) {
        return '';
    }

    $post_id = get_the_ID();
    if (!$post_id) {
        return '';
    }

    $recommender = new \CRE\Recommendation\VehicleRecommender($post_id);
    $recommendations = $recommender->get();

    if (empty($recommendations)) {
        return '';
    }

    ob_start();
    include CRE_PATH . 'template-parts/car/recommendations.php';
    return (string) ob_get_clean();
}
add_shortcode('car_recommendations', 'cre_recommendations_shortcode');





