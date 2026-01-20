1. Big Picture – What Your Engine Does
Backend workflow (per car page):
Read data
Normalizer::vehicleData($postId) pulls these ACF fields and normalizes them:
vehicle_price, vehicle_year, vehicle_mileage
vehicle_engine, vehicle_transmission
vehicle_body_style, vehicle_fuel, vehicle_interior
taxonomy terms from car_category
Detect main class & fallback order
CategoryRules::detect($vehicle) decides which of the 6 classes this car “belongs to” (Hyper, Luxury GT, Ultra Luxury, SUV, EV, Classic) using only the ACF values.
CategoryRules::ordered($primaryCategory) returns the ordered list of keys starting from that class and then falling down through the others (so Hyper → Luxury GT → Ultra Luxury → …).
Find candidate cars
QueryBuilder::candidates() runs WP_Query to get up to 50 other car-listing posts that are:
In a similar price range (±30%)
With similar body style (LIKE on vehicle_body_style, if set)
With similar fuel (LIKE on vehicle_fuel, if set)
Optionally in the same taxonomy car_category (if set)
Rank per category & build sections
For each category key in that ordered list:
ScoreCalculator::rank():
First checks: “Does this candidate satisfy this category’s rule?” (using CategoryRules matcher).
Then adds score for:
Same body style (+30)
Price within ±20% (+25)
Same/compatible fuel (+15)
Similar year (±5 years) (+10)
Sharing any taxonomy car_category (+20)
Has interior filled (+5)
VehicleRecommender keeps only non-empty categories and slices top 8 cars per section.
Template usage
Your template (from readme.txt) just calls:
   use CRE\Recommendation\VehicleRecommender;   $recommender = new VehicleRecommender(get_the_ID());   $sections = $recommender->get();
Then loops $sections and outputs carousels. No logic in the template.
2. Are WordPress Categories Needed or Not?
You effectively have two “category layers”:
Engine categories (the 6 classes)
Defined in CategoryRules::all() and logic methods.
Always used. These are NOT WP taxonomy terms; they’re hard-coded logic buckets.
WordPress taxonomy car_category (ACF export shows it)
Optional, manual labels like hyper_super, luxury_gt, etc.
Used in two ways:
Normalizer::vehicleData() adds them to categories[]
ScoreCalculator gives +20 points for shared terms.
QueryBuilder uses them in tax_query as a boost for similar category posts.
So:
The plugin works even if you never touch car_category; it will still:
Detect the car’s class via engine/body/fuel/price/year.
Recommend similar cars using only ACF meta.
Using car_category is recommended but optional:
It improves similarity (extra score + better WP_Query filtering).
It also gives you manual control if you want to force groupings.
3. Admin Flow – When Adding a New Car
From the admin’s perspective, this is the workflow:
Go to Car Listings → Add New (your custom post type).
Fill the standard fields:
Title (e.g., “Ferrari 812 Superfast”)
Content (description, optional)
Featured Image (used in your carousel example)
Fill ACF “Vehicle Listing” fields (this is the important part for the engine):
Price: put a numeric-like string, e.g. 450000 or $450,000
Vehicle Year: e.g. 2021
Vehicle Mileage: e.g. 8000 km
Vehicle Engine: e.g. V12, V8 twin turbo, Electric
Vehicle Transmission: e.g. Automatic, 7-speed automatic, Manual
Vehicle Body Style: e.g. Coupe, Sedan, SUV, Sports Coupe
Vehicle Fuel: e.g. Petrol, Diesel, Electric, EV
Vehicle Interior: any non-empty string indicates “nice interior”
These are all free-text fields, but the rules expect to see certain keywords:
Hyper: engine contains v8, v10, v12, turbo; body like coupe/sports; price >= high threshold; not electric.
Luxury GT: body is coupe/sedan, transmission automatic, interior filled, price >= medium.
Ultra Luxury: body sedan, interior filled, low mileage, price >= very_high.
Performance SUV: body contains suv, interior filled, price >= medium.
Electric Luxury: fuel contains electric or ev, recent year, price >= medium.
Classic: year <= (current year - 25).
Optionally set taxonomy Categories (car_category):
In the sidebar or meta box, choose/create terms like:
hyper_super, luxury_gt, ultra_luxury, performance_suv, electric_luxury, classic_collectible
If you don’t set anything, recommendations still work, just without taxonomy weighting.
Hit “Publish”.
From then on:
When you view this car on the front-end:
The engine will:
Compute its primary class via ACF fields.
Build an ordered category sequence starting from that class.
Find candidates among other cars (matching price/body/fuel/taxonomy).
Build category carousels: starting at that class, then falling back to the others if no matches exist in the higher class.
4. Example: Adding a New Hyper Car
Admin adds:
vehicle_engine: V8 twin turbo
vehicle_body_style: Coupe
vehicle_fuel: Petrol
vehicle_price: 700000
vehicle_year: 2023
vehicle_interior: Alcantara sport interior
Taxonomy car_category: (optional) hyper_super
Engine behavior:
CategoryRules::detect() will almost certainly classify it as hyper_super.
ordered() returns:
[ 'hyper_super', 'luxury_gt', 'ultra_luxury', 'performance_suv', 'electric_luxury', 'classic_collectible' ]
For hyper_super, it will try to find other candidates that:
Pass the hyper rule.
Rank them by similarity (body, price, fuel, year, taxonomy, interior).
If there are no other hyper cars, that section may be empty, but the next category in order (luxury_gt) will still be processed, so you still get recommendations (just from the closest “down-level” class).
5. What the Admin Does NOT Need to Do
They do not choose “this car is Hyper” in a field.
That’s inferred by the rules from engine/body/fuel/price/year.
They do not configure any recommendation settings per car.
The logic is completely automatic.
They only need to:
Maintain clean-ish, realistic ACF values.
Optionally use taxonomy car_category if they want extra grouping control.