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
     * Get category-wise recommendations
     */
    public function get(): array {

        $results = [];

        $categories = CategoryRules::all();
        $candidates = QueryBuilder::candidates($this->postId, $this->vehicle);

        if (empty($candidates)) {
            return [];
        }

        foreach ($categories as $key => $category) {

            $ranked = ScoreCalculator::rank(
                $this->vehicle,
                $candidates,
                $category
            );

            if (!empty($ranked)) {
                $results[$key] = [
                    'label' => $category['label'],
                    'items' => array_slice($ranked, 0, 8), // limit per carousel
                ];
            }
        }

        return $results;
    }
}


$test = new VehicleRecommender(234);
$sections = $test->get();
error_log(print_r($sections, true));