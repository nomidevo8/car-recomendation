✅ Decision Confirmed

We will:

✅ Use ONLY your existing ACF fields

✅ Build OOP-based recommendation engine

✅ Use your given 6 categories

✅ Make it future-proof (easy to add attributes later)

✅ Output category-wise carousels

No schema changes required right now.

1️⃣ What Data We Will Use (From Your ACF)

From your JSON, we will rely on:

Logic Use	ACF Field
Price	vehicle_price
Year	vehicle_year
Mileage	vehicle_mileage
Engine hint	vehicle_engine
Transmission	vehicle_transmission
Body type	vehicle_body_style
Fuel type	vehicle_fuel
Interior quality	vehicle_interior
Manual fallback	car_category taxonomy

⚠️ All are text → we will normalize at runtime

2️⃣ Categories (As You Defined)

We hard-define category rules, NOT taxonomy dependency:

Hyper & Super Performance

Luxury Sports & Grand Touring

Ultra Luxury

Performance & Luxury SUVs

Electric Luxury & Performance EVs

Classics & Collectibles

3️⃣ Recommendation Engine Architecture (Final)

This is the actual structure we’ll code:

inc/
└── Recommendation/
    ├── VehicleRecommender.php   (orchestrator)
    ├── CategoryRules.php        (category detection)
    ├── ScoreCalculator.php      (ranking logic)
    ├── QueryBuilder.php         (WP_Query prefilters)
    └── Normalizer.php           (text → number)


Templates stay 100% logic-free.

4️⃣ Category Logic (Using ONLY Your Fields)
1️⃣ Hyper & Super Performance

Heuristic-based (safe & realistic)

engine contains: V8 | V10 | V12 | Turbo
AND body_style contains: Coupe | Sports
AND price >= dynamic high threshold
AND fuel NOT electric

2️⃣ Luxury Sports & Grand Touring
body_style = Coupe OR Sedan
AND transmission = Automatic
AND interior NOT empty
AND price medium-high

3️⃣ Ultra Luxury
body_style = Sedan
AND interior NOT empty
AND mileage low
AND price high

4️⃣ Performance & Luxury SUVs
body_style contains SUV
AND interior NOT empty
AND price medium-high

5️⃣ Electric Luxury & Performance EVs
fuel contains Electric | EV
AND year recent
AND price medium-high

6️⃣ Classics & Collectibles
vehicle_year <= (current year - 25)
OR mileage very low AND year old

5️⃣ Scoring System (THIS is the Intelligence)

Instead of yes/no, we rank matches.

Score Factors
Match	Points
Same body style	+30
Price similarity (±20%)	+25
Same fuel type	+15
Similar year	+10
Same taxonomy category	+20

Best cars appear first in carousel.


** Plugin Structure 


car-recommendation-engine/
│
├── car-recommendation-engine.php   (bootstrap)
│
├── inc/
│   ├── Recommendation/
│   │   ├── VehicleRecommender.php
│   │   ├── CategoryRules.php
│   │   ├── ScoreCalculator.php
│   │   ├── QueryBuilder.php
│   │   └── Normalizer.php  
│   │
│   └── Helpers/
│       └── ACF.php
│
└── readme.txt















EXANPMLE USAGE 

<?php
use CRE\Recommendation\VehicleRecommender;

$recommender = new VehicleRecommender(get_the_ID());
$sections = $recommender->get();

if (!empty($sections)) :
    foreach ($sections as $section) :
?>
        <section class="car-recommendation">
            <h2><?php echo esc_html($section['label']); ?></h2>

            <div class="car-carousel">
                <?php foreach ($section['items'] as $postId) : ?>
                    <?php
                        $post = get_post($postId);
                        setup_postdata($post);
                    ?>
                        <div class="car-card">
                            <?php the_post_thumbnail('medium'); ?>
                            <h3><?php the_title(); ?></h3>
                        </div>
                <?php endforeach; wp_reset_postdata(); ?>
            </div>
        </section>
<?php
    endforeach;
endif;
