<?php
namespace CRE\Recommendation;

if (!defined('ABSPATH')) {
    exit;
}

class VehicleRecommender {

    protected int $postId;
    protected array $vehicle;

    public function __construct(int $postId) {
        $this->postId  = $postId;
        $this->vehicle = Normalizer::vehicleData($postId);
    }

    /**
     * Get flat array of recommended vehicle IDs (up to 6)
     */
    public function get(): array {
      
        $limit = 6;

        // Get all candidate vehicles
        $candidates = QueryBuilder::candidates($this->postId, $this->vehicle);

        if (empty($candidates)) {
            return [];
        }

        // Detect primary category for scoring preference
        $categories = CategoryRules::all();
        $primaryCategory = CategoryRules::detect($this->vehicle);
        $categoryOrder   = CategoryRules::ordered($primaryCategory);

        // Score all candidates across all categories and collect unique results
        $seen = [];
        $recommendations = [];

        foreach ($categoryOrder as $key) {

            if (count($recommendations) >= $limit) {
                break;
            }

            if (!isset($categories[$key])) {
                continue;
            }

            $category = $categories[$key];

            // Get ranked candidates for this category
            $ranked = ScoreCalculator::rank(
                $this->vehicle,
                $candidates,
                $category
            );

            // Add top candidates from this category
            foreach ($ranked as $postId) {
                // Skip if already added
                if (isset($seen[$postId])) {
                    continue;
                }

                $seen[$postId] = true;
                $recommendations[] = $postId;

                if (count($recommendations) >= $limit) {
                    break 2; // Break out of both loops
                }
            }
        }

        return $recommendations;
    }
}