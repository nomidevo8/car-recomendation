<?php
namespace CRE\Recommendation;

if (!defined('ABSPATH')) {
    exit;
}

class Normalizer {

    public static function vehicleData(int $postId): array {

        return [
            'id'           => $postId,
            'price'        => self::number(get_field('vehicle_price', $postId)),
            'year'         => self::number(get_field('vehicle_year', $postId)),
            'mileage'      => self::number(get_field('vehicle_mileage', $postId)),
            'engine'       => self::text(get_field('vehicle_engine', $postId)),
            'transmission' => self::text(get_field('vehicle_transmission', $postId)),
            'body_style'   => self::text(get_field('vehicle_body_style', $postId)),
            'fuel'         => self::text(get_field('vehicle_fuel', $postId)),
            'interior'     => self::text(get_field('vehicle_interior', $postId)),
            'categories'   => self::taxonomy($postId, 'car_category'),
        ];
    }

    /** Normalize numeric values from text */
    protected static function number($value): int {
        if (!$value) return 0;
        return (int) preg_replace('/[^0-9]/', '', $value);
    }

    /** Normalize text */
    protected static function text($value): string {
        return strtolower(trim((string) $value));
    }

    /** Taxonomy helper */
    protected static function taxonomy(int $postId, string $taxonomy): array {
        $terms = get_the_terms($postId, $taxonomy);
        if (empty($terms) || is_wp_error($terms)) {
            return [];
        }

        return wp_list_pluck($terms, 'slug');
    }
}
