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

require_once CRE_PATH . 'inc/Recommendation/Normalizer.php';
require_once CRE_PATH . 'inc/Recommendation/CategoryRules.php';
require_once CRE_PATH . 'inc/Recommendation/QueryBuilder.php';
require_once CRE_PATH . 'inc/Recommendation/ScoreCalculator.php';
require_once CRE_PATH . 'inc/Recommendation/VehicleRecommender.php';

