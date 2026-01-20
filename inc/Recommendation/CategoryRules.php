<?php
namespace CRE\Recommendation;

if (!defined('ABSPATH')) {
    exit;
}

class CategoryRules {

    /**
     * Return all recommendation categories
     */
    public static function all(): array {
        return [
            'hyper_super' => [
                'label' => 'Hyper & Super Performance',
                'match' => [self::class, 'hyperSuperPerformance'],
            ],
            'luxury_gt' => [
                'label' => 'Luxury Sports & Grand Touring',
                'match' => [self::class, 'luxuryGrandTouring'],
            ],
            'ultra_luxury' => [
                'label' => 'Ultra Luxury',
                'match' => [self::class, 'ultraLuxury'],
            ],
            'performance_suv' => [
                'label' => 'Performance & Luxury SUVs',
                'match' => [self::class, 'performanceSUV'],
            ],
            'electric_luxury' => [
                'label' => 'Electric Luxury & Performance EVs',
                'match' => [self::class, 'electricLuxury'],
            ],
            'classic_collectible' => [
                'label' => 'Classics & Collectibles',
                'match' => [self::class, 'classicCollectible'],
            ],
        ];
    }

    /* -------------------------------------------------
     * CATEGORY MATCHERS
     * Each method receives normalized vehicle data
     * ------------------------------------------------- */

    public static function hyperSuperPerformance(array $v): bool {
        return (
            self::engineHas($v['engine'], ['v8', 'v10', 'v12', 'turbo']) &&
            self::bodyIs($v['body_style'], ['coupe', 'sports']) &&
            $v['price'] >= self::priceThreshold('high') &&
            !self::fuelIs($v['fuel'], ['electric', 'ev'])
        );
    }

    public static function luxuryGrandTouring(array $v): bool {
        return (
            self::bodyIs($v['body_style'], ['coupe', 'sedan']) &&
            self::transmissionIs($v['transmission'], ['automatic']) &&
            !empty($v['interior']) &&
            $v['price'] >= self::priceThreshold('medium')
        );
    }

    public static function ultraLuxury(array $v): bool {
        return (
            self::bodyIs($v['body_style'], ['sedan']) &&
            !empty($v['interior']) &&
            $v['mileage'] <= 50000 &&
            $v['price'] >= self::priceThreshold('very_high')
        );
    }

    public static function performanceSUV(array $v): bool {
        return (
            self::bodyIs($v['body_style'], ['suv']) &&
            !empty($v['interior']) &&
            $v['price'] >= self::priceThreshold('medium')
        );
    }

    public static function electricLuxury(array $v): bool {
        return (
            self::fuelIs($v['fuel'], ['electric', 'ev']) &&
            $v['price'] >= self::priceThreshold('medium') &&
            $v['year'] >= (date('Y') - 5)
        );
    }

    public static function classicCollectible(array $v): bool {
        return (
            $v['year'] > 0 &&
            $v['year'] <= (date('Y') - 25)
        );
    }

    /* -------------------------------------------------
     * HELPERS
     * ------------------------------------------------- */

    protected static function engineHas(string $engine, array $keywords): bool {
        foreach ($keywords as $key) {
            if (str_contains($engine, $key)) {
                return true;
            }
        }
        return false;
    }

    protected static function bodyIs(string $body, array $types): bool {
        foreach ($types as $type) {
            if (str_contains($body, $type)) {
                return true;
            }
        }
        return false;
    }

    protected static function fuelIs(string $fuel, array $types): bool {
        foreach ($types as $type) {
            if (str_contains($fuel, $type)) {
                return true;
            }
        }
        return false;
    }

    protected static function transmissionIs(string $transmission, array $types): bool {
        foreach ($types as $type) {
            if (str_contains($transmission, $type)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Dynamic price thresholds (adjust once, affects all logic)
     */
    protected static function priceThreshold(string $level): int {
        return match ($level) {
            'medium'     => 50000,
            'high'       => 150000,
            'very_high'  => 300000,
            default      => 0,
        };
    }
}
