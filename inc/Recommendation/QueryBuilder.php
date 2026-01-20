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

        // --- Price pre-filter (±50% - wider range) ---
        if (!empty($vehicle['price']) && $vehicle['price'] > 0) {
            $min = (int) ($vehicle['price'] * 0.5);
            $max = (int) ($vehicle['price'] * 1.5);

            $metaQuery[] = [
                'key'     => 'vehicle_price',
                'value'   => [$min, $max],
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC',
            ];
        }

        $args = [
            'post_type'      => 'car-listing',
            'post_status'    => 'publish',
            'posts_per_page' => 100, // increased for more candidates
            'post__not_in'   => [$currentPostId],
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'orderby'        => 'rand', // randomize to get variety
        ];

        // Only add meta_query if we have conditions
        if (!empty($metaQuery)) {
            $args['meta_query'] = [
                'relation' => 'AND',
                ...$metaQuery,
            ];
        }

        // --- Taxonomy hint (optional, not required) ---
        if (!empty($vehicle['categories'])) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'car_category',
                    'field'    => 'slug',
                    'terms'    => $vehicle['categories'],
                    'operator' => 'IN',
                ],
            ];
        }

        $query = new \WP_Query($args);

        return $query->posts ?? [];
    }
}
