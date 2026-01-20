<?php
/**
 * One-time importer for Car Listings CPT with ACF
 * DELETE THIS FILE AFTER RUNNING ONCE
 */

if ( ! defined('ABSPATH') ) {
    require_once('../../../wp-load.php');
}

if ( ! current_user_can('manage_options') ) {
    wp_die('Unauthorized');
}

$data = [
    [
        "post_title" => "Bugatti Chiron Super Sport",
        "terms" => ["hyper_super"],
        "meta" => [
            "vehicle_price" => "3800000",
            "vehicle_year" => "2022",
            "vehicle_mileage" => "1200",
            "vehicle_engine" => "W16 quad turbo",
            "vehicle_transmission" => "7-speed dual-clutch automatic",
            "vehicle_body_style" => "Coupe",
            "vehicle_fuel" => "Petrol",
            "vehicle_interior" => "Full bespoke leather & carbon"
        ]
    ],
    [
        "post_title" => "Lamborghini Aventador SVJ",
        "terms" => ["hyper_super"],
        "meta" => [
            "vehicle_price" => "650000",
            "vehicle_year" => "2021",
            "vehicle_mileage" => "2500",
            "vehicle_engine" => "V12 NA",
            "vehicle_transmission" => "Automatic",
            "vehicle_body_style" => "Coupe Sports",
            "vehicle_fuel" => "Petrol",
            "vehicle_interior" => "Alcantara sport"
        ]
    ],
    [
        "post_title" => "Porsche 911 Turbo S",
        "terms" => ["luxury_gt"],
        "meta" => [
            "vehicle_price" => "240000",
            "vehicle_year" => "2023",
            "vehicle_mileage" => "3800",
            "vehicle_engine" => "Flat-6 twin turbo",
            "vehicle_transmission" => "8-speed PDK automatic",
            "vehicle_body_style" => "Coupe",
            "vehicle_fuel" => "Petrol",
            "vehicle_interior" => "Leather sport chrono"
        ]
    ],
    [
        "post_title" => "Bentley Continental GT",
        "terms" => ["luxury_gt"],
        "meta" => [
            "vehicle_price" => "280000",
            "vehicle_year" => "2022",
            "vehicle_mileage" => "6200",
            "vehicle_engine" => "W12 twin turbo",
            "vehicle_transmission" => "Automatic",
            "vehicle_body_style" => "Coupe",
            "vehicle_fuel" => "Petrol",
            "vehicle_interior" => "Diamond quilted leather"
        ]
    ],
    [
        "post_title" => "Rolls-Royce Phantom EWB",
        "terms" => ["ultra_luxury"],
        "meta" => [
            "vehicle_price" => "560000",
            "vehicle_year" => "2021",
            "vehicle_mileage" => "12000",
            "vehicle_engine" => "V12 twin turbo",
            "vehicle_transmission" => "Automatic",
            "vehicle_body_style" => "Sedan",
            "vehicle_fuel" => "Petrol",
            "vehicle_interior" => "Starlight headliner, lambswool"
        ]
    ],
    [
        "post_title" => "Mercedes-Maybach S680",
        "terms" => ["ultra_luxury"],
        "meta" => [
            "vehicle_price" => "330000",
            "vehicle_year" => "2023",
            "vehicle_mileage" => "8000",
            "vehicle_engine" => "V12 twin turbo",
            "vehicle_transmission" => "Automatic",
            "vehicle_body_style" => "Sedan",
            "vehicle_fuel" => "Petrol",
            "vehicle_interior" => "Executive rear, Nappa"
        ]
    ],
    [
        "post_title" => "Lamborghini Urus Performante",
        "terms" => ["performance_suv"],
        "meta" => [
            "vehicle_price" => "310000",
            "vehicle_year" => "2023",
            "vehicle_mileage" => "7000",
            "vehicle_engine" => "V8 twin turbo",
            "vehicle_transmission" => "Automatic",
            "vehicle_body_style" => "SUV",
            "vehicle_fuel" => "Petrol",
            "vehicle_interior" => "Alcantara sport"
        ]
    ],
    [
        "post_title" => "Range Rover Autobiography",
        "terms" => ["performance_suv"],
        "meta" => [
            "vehicle_price" => "190000",
            "vehicle_year" => "2022",
            "vehicle_mileage" => "9000",
            "vehicle_engine" => "V8 mild hybrid",
            "vehicle_transmission" => "Automatic",
            "vehicle_body_style" => "SUV",
            "vehicle_fuel" => "Petrol",
            "vehicle_interior" => "Semi-aniline leather"
        ]
    ],
    [
        "post_title" => "Porsche Taycan Turbo S",
        "terms" => ["electric_luxury"],
        "meta" => [
            "vehicle_price" => "210000",
            "vehicle_year" => "2024",
            "vehicle_mileage" => "3000",
            "vehicle_engine" => "Dual-motor electric",
            "vehicle_transmission" => "Automatic",
            "vehicle_body_style" => "Sedan",
            "vehicle_fuel" => "Electric",
            "vehicle_interior" => "Leather / Race-Tex"
        ]
    ],
    [
        "post_title" => "Tesla Model S Plaid",
        "terms" => ["electric_luxury"],
        "meta" => [
            "vehicle_price" => "140000",
            "vehicle_year" => "2024",
            "vehicle_mileage" => "5000",
            "vehicle_engine" => "Tri-motor electric",
            "vehicle_transmission" => "Automatic",
            "vehicle_body_style" => "Sedan",
            "vehicle_fuel" => "Electric",
            "vehicle_interior" => "Yoke, vegan leather"
        ]
    ],
    [
        "post_title" => "Ferrari F40",
        "terms" => ["classic_collectible"],
        "meta" => [
            "vehicle_price" => "2200000",
            "vehicle_year" => "1991",
            "vehicle_mileage" => "18000",
            "vehicle_engine" => "V8 twin turbo",
            "vehicle_transmission" => "Manual",
            "vehicle_body_style" => "Coupe",
            "vehicle_fuel" => "Petrol",
            "vehicle_interior" => "Raw carbon & cloth"
        ]
    ],
    [
        "post_title" => "Porsche 964 Carrera RS",
        "terms" => ["classic_collectible"],
        "meta" => [
            "vehicle_price" => "380000",
            "vehicle_year" => "1992",
            "vehicle_mileage" => "52000",
            "vehicle_engine" => "Flat-6 NA",
            "vehicle_transmission" => "Manual",
            "vehicle_body_style" => "Coupe",
            "vehicle_fuel" => "Petrol",
            "vehicle_interior" => "Lightweight cloth"
        ]
    ]
];

foreach ($data as $item) {

    if ( get_page_by_title($item['post_title'], OBJECT, 'car-listing') ) {
        continue;
    }

    $post_id = wp_insert_post([
        'post_title'  => $item['post_title'],
        'post_status' => 'publish',
        'post_type'   => 'car-listing'
    ]);

    if ( is_wp_error($post_id) ) {
        continue;
    }

    // Assign taxonomy
    wp_set_object_terms($post_id, $item['terms'], 'car_category');

    // Save ACF fields
    foreach ($item['meta'] as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }
}

echo '<h2>✅ Car listings imported successfully.</h2>';
