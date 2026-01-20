<?php
namespace CRE\Recommendation;

if (!defined('ABSPATH')) {
    exit;
}

class ScoreCalculator {

    /**
     * Rank candidate vehicles for a given category
     */
    public static function rank(array $current, array $candidateIds, array $category): array {

        $ranked = [];

        foreach ($candidateIds as $postId) {

            $candidate = Normalizer::vehicleData($postId);

            $score = 0;

            // if (!call_user_func($category['match'], $candidate)) {
            //     continue;
            // }

            // --- Category match bonus (not required, just bonus points) ---
            if (call_user_func($category['match'], $candidate)) {
                $score += 40;
            }

            // --- Body style match ---
            if ($current['body_style'] && str_contains($candidate['body_style'], $current['body_style'])) {
                $score += 30;
            }

            // --- Price similarity (±20%) ---
            if ($current['price'] && $candidate['price']) {
                $min = $current['price'] * 0.8;
                $max = $current['price'] * 1.2;

                if ($candidate['price'] >= $min && $candidate['price'] <= $max) {
                    $score += 25;
                }
            }

            // --- Fuel match ---
            if ($current['fuel'] && str_contains($candidate['fuel'], $current['fuel'])) {
                $score += 15;
            }

            // --- Year similarity ---
            if ($current['year'] && $candidate['year']) {
                if (abs($current['year'] - $candidate['year']) <= 5) {
                    $score += 10;
                }
            }

            // --- Same taxonomy category ---
            if (!empty(array_intersect($current['categories'], $candidate['categories']))) {
                $score += 20;
            }

            // --- Interior bonus ---
            if (!empty($candidate['interior'])) {
                $score += 5;
            }

            // Only include vehicles with some score
            if ($score > 0) {
                $ranked[] = [
                    'post_id' => $postId,
                    'score'   => $score,
                ];
            }
        }

        // Sort by highest score
        usort($ranked, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return array_column($ranked, 'post_id');
    }
}
