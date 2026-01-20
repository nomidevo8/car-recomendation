<?php
namespace CRE\Recommendation;

if (!defined('ABSPATH')) {
    exit;
}

class QueryBuilder {

    /**
     * Get candidate vehicle IDs for recommendation
     */
    public static function candidates(int $currentPostId, array $vehicle): array {

        $metaQuery = [];

        // --- Price pre-filter (±30%) ---
        if (!empty($vehicle['price'])) {
            $min = (int) ($vehicle['price'] * 0.7);
            $max = (int) ($vehicle['price'] * 1.3);

            $metaQuery[] = [
                'key'     => 'vehicle_price',
                'value'   => [$min, $max],
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC',
            ];
        }

        // --- Body style hint (LIKE because it's text) ---
        if (!empty($vehicle['body_style'])) {
            $metaQuery[] = [
                'key'     => 'vehicle_body_style',
                'value'   => $vehicle['body_style'],
                'compare' => 'LIKE',
            ];
        }

        // --- Fuel type hint ---
        if (!empty($vehicle['fuel'])) {
            $metaQuery[] = [
                'key'     => 'vehicle_fuel',
                'value'   => $vehicle['fuel'],
                'compare' => 'LIKE',
            ];
        }

        $args = [
            'post_type'      => 'car-listing',
            'post_status'    => 'publish',
            'posts_per_page' => 50, // hard cap for performance
            'post__not_in'   => [$currentPostId],
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'meta_query'     => [
                'relation' => 'AND',
                ...$metaQuery,
            ],
        ];

        // --- Taxonomy fallback (optional boost) ---
        if (!empty($vehicle['categories'])) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'car_category',
                    'field'    => 'slug',
                    'terms'    => $vehicle['categories'],
                ],
            ];
        }

        $query = new \WP_Query($args);

        return $query->posts ?? [];
    }
}
