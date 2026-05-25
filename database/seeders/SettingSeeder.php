<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\CallSetting;
use App\Models\ApiSetting;
use App\Models\Faq;
use App\Models\Destination;
use App\Models\Offer;
use App\Models\Blog;
use App\Models\Testimonial;
use App\Models\SeoSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = User::where('role', 'superadmin')->first();
        $adminId = $superadmin ? $superadmin->id : 1;

        // 1. Seed Site Settings
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Destination Fareways',
                'tagline' => 'Luxury Travel & Premium Flight Deals',
                'logo' => null,
                'favicon' => null,
                'primary_email' => 'info@destinationfareways.com',
                'secondary_email' => 'sales@destinationfareways.com',
                'primary_phone' => '+1 (800) 555-0199',
                'secondary_phone' => '+1 (800) 555-0144',
                'whatsapp' => '+1 (800) 555-0199',
                'address' => '100 Premium Fareways Blvd, Suite 500',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'zip' => '10001',
                'social_facebook' => 'https://facebook.com/destinationfareways',
                'social_twitter' => 'https://twitter.com/destfareways',
                'social_instagram' => 'https://instagram.com/destinationfareways',
                'social_linkedin' => 'https://linkedin.com/company/destinationfareways',
                'social_youtube' => 'https://youtube.com/c/destinationfareways',
                'analytics_google_id' => 'G-DF12345678',
                'analytics_facebook_pixel' => 'FB-PIXEL-12345',
                'analytics_custom_code' => '<!-- Custom Site Code -->',
                'header_scripts' => '<!-- Custom Header Scripts -->',
                'footer_scripts' => '<!-- Custom Footer Scripts -->',
                'copyright' => '© ' . date('Y') . ' Destination Fareways. All Rights Reserved.',
                'maintenance_mode' => 'inactive',
            ]
        );

        // 2. Seed Call Settings
        CallSetting::updateOrCreate(
            ['id' => 1],
            [
                'phone' => '+1 (800) 555-0199',
                'whatsapp' => '+1 (800) 555-0199',
                'button_text' => 'Call Now',
                'button_color' => '#F59E0B',
                'text_color' => '#FFFFFF',
                'toggle_header' => true,
                'toggle_footer' => true,
                'toggle_mobile_floating' => true,
                'toggle_desktop' => true,
                'toggle_whatsapp' => true,
                'floating_position' => 'bottom-right',
                'cta_text' => 'Speak with a Travel Expert',
                'cta_phone' => '+1 (800) 555-0199',
                'cta_subtext' => 'Get exclusive phone-only discounts up to 30% off!',
                'status' => true,
            ]
        );

        // 3. Seed API Settings
        ApiSetting::updateOrCreate(
            ['id' => 1],
            [
                'provider' => 'Amadeus Flight API',
                'base_url' => 'https://test.api.amadeus.com',
                'api_key' => 'MockApiKeyForAmadeusSandboxValue',
                'api_secret' => 'MockApiSecretForAmadeusSandboxValue',
                'mode' => 'sandbox',
                'endpoint_search' => '/v2/shopping/flight-offers',
                'endpoint_booking' => '/v1/booking/flight-orders',
                'endpoint_fare_rules' => '/v1/shopping/flight-offers/pricing',
                'endpoint_cancellation' => '/v1/booking/flight-orders/cancel',
                'endpoint_refund' => '/v1/shopping/flight-offers/refund',
                'endpoint_webhook' => '/api/v1/flights/webhook',
                'api_status' => 'active',
                'currency' => 'USD',
                'markup_percent' => 5.00,
                'commission_percent' => 8.50,
                'last_error_log' => null,
                'last_sync_at' => now(),
            ]
        );

        // 4. Seed 8 FAQs
        $faqs = [
            [
                'question' => 'How can I find the cheapest flight deals on Destination Fareways?',
                'answer' => 'To secure the best rates, search in advance, stay flexible with dates, select off-peak travel times, and call our experts directly. Many premium and business class deals are phone-exclusive and not published online.',
                'category' => 'Booking',
                'page_slug' => 'faq',
                'sort_order' => 1,
                'status' => 'active',
            ],
            [
                'question' => 'Are my tickets eligible for refunds or cancellations?',
                'answer' => 'Refund and cancellation eligibility depends on the specific airline fare rules purchased. Economy flight tickets are often non-refundable but can be exchanged for flight credits, while Business and First class reservations typically support flexible cancellation options.',
                'category' => 'Cancellations & Refunds',
                'page_slug' => 'faq',
                'sort_order' => 2,
                'status' => 'active',
            ],
            [
                'question' => 'What is the maximum baggage allowance permitted?',
                'answer' => 'Baggage policies vary per airline, route, and cabin class. Business and First class flights usually include two checked bags up to 70 lbs (32kg) each, while Economy flights usually support one checked bag up to 50 lbs (23kg). Check your specific ticket details for confirmation.',
                'category' => 'Baggage',
                'page_slug' => 'faq',
                'sort_order' => 3,
                'status' => 'active',
            ],
            [
                'question' => 'Can I book a multi-city itinerary?',
                'answer' => 'Yes, our smart booking systems and specialized agents can build custom multi-city itineraries, allowing you to stop over at multiple destinations in a single journey. Select the Multi-City option or dial our helpline to customize.',
                'category' => 'Booking',
                'page_slug' => 'faq',
                'sort_order' => 4,
                'status' => 'active',
            ],
            [
                'question' => 'How do I choose my preferred seats?',
                'answer' => 'You can request seat preferences during the checkout process or update them afterwards in your Admin portal. For premium cabin configurations, including lie-flat seats, seat map selections are available during the booking step.',
                'category' => 'Seating',
                'page_slug' => 'faq',
                'sort_order' => 5,
                'status' => 'active',
            ],
            [
                'question' => 'Do you provide travel insurance services?',
                'answer' => 'Yes, we offer premium travel insurance packages through our airline partners, covering medical emergencies, trip interruptions, baggage losses, and flexible cancellation protections. Make sure to check the insurance options on checkout.',
                'category' => 'Insurance',
                'page_slug' => 'faq',
                'sort_order' => 6,
                'status' => 'active',
            ],
            [
                'question' => 'What should I do if my flight gets delayed or cancelled by the airline?',
                'answer' => 'In case of delays or cancellations, our 24/7 client care team is ready to rebook you on the next available flight. You will receive real-time SMS alerts and email updates regarding your itinerary status.',
                'category' => 'Support',
                'page_slug' => 'faq',
                'sort_order' => 7,
                'status' => 'active',
            ],
            [
                'question' => 'Can I upgrade my cabin class from Economy to Business after booking?',
                'answer' => 'Cabin upgrades are subject to airline availability and fare conditions. You can request upgrades using cash or frequent flyer miles directly in your dashboard or by speaking to one of our reservation concierges.',
                'category' => 'Cabin Upgrades',
                'page_slug' => 'faq',
                'sort_order' => 8,
                'status' => 'active',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }

        // 5. Seed 8 Destinations (NY/LA/Chicago/Miami/Vegas/Dallas/SF/Seattle)
        $destinations = [
            [
                'name' => 'New York City',
                'slug' => 'new-york-city',
                'country' => 'United States',
                'state' => 'New York',
                'airport_code' => 'JFK',
                'short_desc' => 'The cultural, financial, and entertainment capital of the world.',
                'description' => 'New York City comprises 5 boroughs sitting where the Hudson River meets the Atlantic Ocean. At its core is Manhattan, a densely populated borough that’s among the world’s major commercial, financial and cultural centers. Explore Central Park, Broadway musicals, and Times Square.',
                'starting_price' => 199.00,
                'best_time_to_visit' => 'September to November',
                'climate' => 'Temperate (4 seasons)',
                'is_domestic' => true,
                'is_featured' => true,
                'is_popular' => true,
                'sort_order' => 1,
                'status' => 'active',
                'created_by' => $adminId,
            ],
            [
                'name' => 'Los Angeles',
                'slug' => 'los-angeles',
                'country' => 'United States',
                'state' => 'California',
                'airport_code' => 'LAX',
                'short_desc' => 'The center of the nation’s film and television industry.',
                'description' => 'Los Angeles is a sprawling Southern California city and the center of the nation’s film and television industry. Near its iconic Hollywood sign, studios such as Paramount Pictures, Universal and Warner Brothers offer behind-the-scenes tours. Walk along the Hollywood Walk of Fame or relax in Santa Monica.',
                'starting_price' => 249.00,
                'best_time_to_visit' => 'March to May',
                'climate' => 'Mediterranean & Sunny',
                'is_domestic' => true,
                'is_featured' => true,
                'is_popular' => true,
                'sort_order' => 2,
                'status' => 'active',
                'created_by' => $adminId,
            ],
            [
                'name' => 'Chicago',
                'slug' => 'chicago',
                'country' => 'United States',
                'state' => 'Illinois',
                'airport_code' => 'ORD',
                'short_desc' => 'Famous for bold architecture, museums, and deep-dish pizza.',
                'description' => 'Chicago, on Lake Michigan in Illinois, is among the largest cities in the U.S. Famed for its bold architecture, it has a skyline punctuated by skyscrapers such as the iconic John Hancock Center, Willis Tower, and the neo-Gothic Tribune Tower. The city is renowned for its world-class museums and arts.',
                'starting_price' => 179.00,
                'best_time_to_visit' => 'June to August',
                'climate' => 'Continental & Breezy',
                'is_domestic' => true,
                'is_featured' => true,
                'is_popular' => false,
                'sort_order' => 3,
                'status' => 'active',
                'created_by' => $adminId,
            ],
            [
                'name' => 'Miami',
                'slug' => 'miami',
                'country' => 'United States',
                'state' => 'Florida',
                'airport_code' => 'MIA',
                'short_desc' => 'Sunny beaches, vibrant nightlife, and rich Hispanic heritage.',
                'description' => 'Miami is an international city at Florida’s southeastern tip. Its Cuban influence is reflected in the cafes and cigar shops that line Calle Ocho in Little Havana. On barrier islands across Biscayne Bay is Miami Beach, home to South Beach, famous for Art Deco buildings, white sand, and beachfront nightclubs.',
                'starting_price' => 189.00,
                'best_time_to_visit' => 'November to April',
                'climate' => 'Subtropical & Tropical',
                'is_domestic' => true,
                'is_featured' => true,
                'is_popular' => true,
                'sort_order' => 4,
                'status' => 'active',
                'created_by' => $adminId,
            ],
            [
                'name' => 'Las Vegas',
                'slug' => 'las-vegas',
                'country' => 'United States',
                'state' => 'Nevada',
                'airport_code' => 'LAS',
                'short_desc' => 'The world-famous resort city renowned for 24-hour nightlife and casinos.',
                'description' => 'Las Vegas, in Nevada’s Mojave Desert, is a resort city famed for its vibrant energy, 24-hour casinos, and endless entertainment options. Its focal point is the Strip, over 4 miles long, lined with themed hotels, elaborate fountains, and spectacular performance stages.',
                'starting_price' => 149.00,
                'best_time_to_visit' => 'March to May & Sept to Nov',
                'climate' => 'Arid Desert Climate',
                'is_domestic' => true,
                'is_featured' => false,
                'is_popular' => true,
                'sort_order' => 5,
                'status' => 'active',
                'created_by' => $adminId,
            ],
            [
                'name' => 'Dallas',
                'slug' => 'dallas',
                'country' => 'United States',
                'state' => 'Texas',
                'airport_code' => 'DFW',
                'short_desc' => 'A modern metropolis and commercial and cultural hub of North Texas.',
                'description' => 'Dallas, a modern metropolis in north Texas, is a commercial and cultural hub of the region. The Sixth Floor Museum at Dealey Plaza commemorates the site of President John F. Kennedy’s assassination. The Arts District features major museums like the Dallas Museum of Art.',
                'starting_price' => 199.00,
                'best_time_to_visit' => 'September to November',
                'climate' => 'Humid Subtropical',
                'is_domestic' => true,
                'is_featured' => false,
                'is_popular' => false,
                'sort_order' => 6,
                'status' => 'active',
                'created_by' => $adminId,
            ],
            [
                'name' => 'San Francisco',
                'slug' => 'san-francisco',
                'country' => 'United States',
                'state' => 'California',
                'airport_code' => 'SFO',
                'short_desc' => 'Famed Golden Gate bridge, cable cars, and colorful Victorian houses.',
                'description' => 'San Francisco, in northern California, is a hilly city on the tip of a peninsula surrounded by the Pacific Ocean and San Francisco Bay. It’s known for its year-round fog, iconic Golden Gate Bridge, cable cars, and colorful Victorian houses. Alcatraz Island sits in the scenic bay.',
                'starting_price' => 269.00,
                'best_time_to_visit' => 'September to November',
                'climate' => 'Cool Maritime & Foggy',
                'is_domestic' => true,
                'is_featured' => true,
                'is_popular' => true,
                'sort_order' => 7,
                'status' => 'active',
                'created_by' => $adminId,
            ],
            [
                'name' => 'Seattle',
                'slug' => 'seattle',
                'country' => 'United States',
                'state' => 'Washington',
                'airport_code' => 'SEA',
                'short_desc' => 'Famed Space Needle, major tech hub, and evergreen forests.',
                'description' => 'Seattle, on Puget Sound in the Pacific Northwest, is surrounded by water, mountains, and evergreen forests, containing thousands of acres of parkland. Washington State’s largest city, it’s home to a large tech industry, with Microsoft and Amazon headquartered in its metropolitan area.',
                'starting_price' => 229.00,
                'best_time_to_visit' => 'June to September',
                'climate' => 'Temperate Oceanic',
                'is_domestic' => true,
                'is_featured' => true,
                'is_popular' => false,
                'sort_order' => 8,
                'status' => 'active',
                'created_by' => $adminId,
            ],
        ];

        foreach ($destinations as $dest) {
            Destination::updateOrCreate(
                ['slug' => $dest['slug']],
                $dest
            );
        }

        // 6. Seed 4 Flight Offers
        $offers = [
            [
                'title' => 'Summer Escape to Sunny Los Angeles',
                'slug' => 'summer-escape-to-sunny-los-angeles',
                'subtitle' => 'Save 30% on Nonstop Business Class Flights',
                'short_desc' => 'Book premium business class flight tickets from NYC to LA and enjoy unparalleled comfort at the lowest rates.',
                'description' => '<p>Fly in style this summer with our special business class discounts. Enjoy fully lie-flat seats, premium lounge access, gourmet dining options, and free high-speed Wi-Fi throughout your flight to LAX.</p>',
                'from_city' => 'New York (JFK)',
                'to_city' => 'Los Angeles (LAX)',
                'airline' => 'Delta Air Lines',
                'original_price' => 899.00,
                'offer_price' => 599.00,
                'discount_label' => '33% OFF',
                'promo_code' => 'LAXSUMMER30',
                'valid_from' => now()->toDateString(),
                'valid_until' => now()->addMonths(3)->toDateString(),
                'is_featured' => true,
                'status' => 'active',
                'sort_order' => 1,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Weekend Getaway in Miami Beach',
                'slug' => 'weekend-getaway-in-miami-beach',
                'subtitle' => 'Exclusive Round-trip Fare Specials Available',
                'short_desc' => 'Relax on the warm beaches of South Beach with our round-trip ticket specials.',
                'description' => '<p>Fly down to Miami Beach for a refreshing weekend break. Select from major airlines with round-trip economy options starting under $150. Flexible booking with free rescheduling changes included.</p>',
                'from_city' => 'Chicago (ORD)',
                'to_city' => 'Miami (MIA)',
                'airline' => 'American Airlines',
                'original_price' => 299.00,
                'offer_price' => 149.00,
                'discount_label' => '50% OFF',
                'promo_code' => 'MIAMIBEACH50',
                'valid_from' => now()->toDateString(),
                'valid_until' => now()->addMonths(2)->toDateString(),
                'is_featured' => true,
                'status' => 'active',
                'sort_order' => 2,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Fly Premium to San Francisco Bay',
                'slug' => 'fly-premium-to-san-francisco-bay',
                'subtitle' => 'Discounted Premium Economy Seat Upgrades',
                'short_desc' => 'Enjoy wider leather seats, extra legroom, and priority boarding at a discount.',
                'description' => '<p>Treat yourself to premium comfort on your next coast-to-coast flight to SFO. Extra legroom, complimentary alcoholic beverages, priority baggage handling, and boarding access included.</p>',
                'from_city' => 'Boston (BOS)',
                'to_city' => 'San Francisco (SFO)',
                'airline' => 'United Airlines',
                'original_price' => 450.00,
                'offer_price' => 299.00,
                'discount_label' => '$150 SAVINGS',
                'promo_code' => 'SFOPREMIUM',
                'valid_from' => now()->toDateString(),
                'valid_until' => now()->addMonths(4)->toDateString(),
                'is_featured' => false,
                'status' => 'active',
                'sort_order' => 3,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Las Vegas High-Roller Special Flights',
                'slug' => 'las-vegas-high-roller-special-flights',
                'subtitle' => 'Round-trip First Class Seat Clearances',
                'short_desc' => 'Arrive in Vegas in ultimate luxury with our exclusive first class flight discounts.',
                'description' => '<p>Fly like a VIP to the entertainment capital of the world. Premium lounge amenities, fine caviar service, champagne, and multi-course meals prepared by gourmet master chefs.</p>',
                'from_city' => 'Los Angeles (LAX)',
                'to_city' => 'Las Vegas (LAS)',
                'airline' => 'Alaska Airlines',
                'original_price' => 399.00,
                'offer_price' => 249.00,
                'discount_label' => 'SAVE $150',
                'promo_code' => 'VEGASVIP',
                'valid_from' => now()->toDateString(),
                'valid_until' => now()->addMonths(1)->toDateString(),
                'is_featured' => true,
                'status' => 'active',
                'sort_order' => 4,
                'created_by' => $adminId,
            ],
        ];

        foreach ($offers as $off) {
            Offer::updateOrCreate(
                ['slug' => $off['slug']],
                $off
            );
        }

        // 7. Seed 3 Blogs
        $blogs = [
            [
                'title' => 'Top 10 Essential Travel Tips for Premium Cabin Flying',
                'slug' => 'top-10-essential-travel-tips-for-premium-cabin-flying',
                'subtitle' => 'How to Maximize Luxury on Long-Haul Flight Routes',
                'excerpt' => 'Flying business or first class is a unique experience. Here are 10 expert tips to get the absolute most out of your premium ticket.',
                'content' => '<p>From selecting the finest lie-flat seating configurations to maximizing your luxurious lounge amenities prior to boarding, premium flying offers a wealth of comforts. This comprehensive guide covers pre-flight dining strategies, amenity kits, and onboard sleep routines designed for a perfect journey.</p>',
                'author_name' => 'Alex Mercer',
                'category' => 'Travel Guides',
                'tags' => 'Luxury, Travel Tips, Flights',
                'read_time' => '5 mins',
                'views' => 124,
                'is_featured' => true,
                'status' => 'published',
                'published_at' => now(),
                'created_by' => $adminId,
            ],
            [
                'title' => 'Exploring the Scenic Hidden Gems of Miami South Beach',
                'slug' => 'exploring-the-scenic-hidden-gems-of-miami-south-beach',
                'subtitle' => 'Beyond the Art Deco Ocean Drive Coastlines',
                'excerpt' => 'Miami Beach is full of wonders. Discover the absolute best local restaurants, quiet parks, and beautiful art locations hidden from normal tourists.',
                'content' => '<p>While popular sandy coastlines and ocean drive corridors attract major crowds, Miami contains a wealth of peaceful cultural gardens, historical museums, and local seafood joints that off-the-beaten-path explorers will fall in love with. Join us on a tour of these hidden spots.</p>',
                'author_name' => 'Sara Jenkins',
                'category' => 'Destinations',
                'tags' => 'Miami, Florida, Beaches',
                'read_time' => '8 mins',
                'views' => 89,
                'is_featured' => false,
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'created_by' => $adminId,
            ],
            [
                'title' => 'The Evolution of Modern Business Class Cabin Lie-Flat Configurations',
                'slug' => 'the-evolution-of-modern-business-class-cabin-lie-flat-configurations',
                'subtitle' => 'Comparing the Best Airline Seats of This Year',
                'excerpt' => 'Business class seats have evolved significantly. We compare the leading suites, Qsuites, and apex configurations available in the sky today.',
                'content' => '<p>A deep dive comparison into the engineering, seat pitch, privacy doors, and spatial innovations of modern airline cabins. We look at luxury offerings from major carriers and evaluate seat dimensions, comfort, and service rankings to determine the ultimate business class ticket of the year.</p>',
                'author_name' => 'Michael Chang',
                'category' => 'Airlines',
                'tags' => 'Business Class, Cabin Design, Reviews',
                'read_time' => '12 mins',
                'views' => 245,
                'is_featured' => true,
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'created_by' => $adminId,
            ],
        ];

        foreach ($blogs as $blg) {
            Blog::updateOrCreate(
                ['slug' => $blg['slug']],
                $blg
            );
        }

        // 8. Seed 3 Testimonials
        $testimonials = [
            [
                'name' => 'Jonathan Vance',
                'designation' => 'Managing Director',
                'company' => 'Vance & Co',
                'location' => 'Boston, MA',
                'review' => 'Destination Fareways saved me over $1,200 on my premium business class flights to Los Angeles. The seat booking process was seamless, and the flight concierges helped me secure a premium window seat with a full lie-flat configuration.',
                'rating' => 5,
                'flight_route' => 'BOS to LAX',
                'is_featured' => true,
                'sort_order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Emily Sterling',
                'designation' => 'Executive Producer',
                'company' => 'Sterling Arts',
                'location' => 'Chicago, IL',
                'review' => 'Excellent service from start to finish! The client care team answered my phone call immediately and helped resolve a scheduling change on short notice. Highly recommend their exclusive flight deals to everyone.',
                'rating' => 5,
                'flight_route' => 'ORD to MIA',
                'is_featured' => true,
                'sort_order' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Marcus Aurelius',
                'designation' => 'Creative Lead',
                'company' => 'Empire Design',
                'location' => 'Seattle, WA',
                'review' => 'Incredible round-trip prices. I booked a first class escape to Las Vegas, and the flights were fantastic. The luxury lounge access alone was worth the promotional rate.',
                'rating' => 5,
                'flight_route' => 'SEA to LAS',
                'is_featured' => false,
                'sort_order' => 3,
                'status' => 'active',
            ],
        ];

        foreach ($testimonials as $test) {
            Testimonial::updateOrCreate(
                ['name' => $test['name']],
                $test
            );
        }

        // 9. Seed 9 SEO Page Mappings
        $pagesSeo = [
            [
                'page_identifier' => 'home',
                'page_name' => 'Home Page',
                'meta_title' => 'Destination Fareways | Luxury Travel & Premium Flight Deals',
                'meta_description' => 'Book business and first class flights at unparalleled discounts. Access exclusive, unpublished phone deals and luxury travel routes worldwide.',
                'meta_keywords' => 'flights, business class, premium travel, first class deals, cheap flights',
            ],
            [
                'page_identifier' => 'about',
                'page_name' => 'About Us',
                'meta_title' => 'About Destination Fareways | Premium Airline Ticket Concierges',
                'meta_description' => 'Discover our mission to connect luxury travelers with the most affordable premium and business cabin inventory globally.',
                'meta_keywords' => 'about us, luxury travel concierges, cheap business class, flight inventory',
            ],
            [
                'page_identifier' => 'flights',
                'page_name' => 'Search Flights',
                'meta_title' => 'Premium Flight Deals & Airfare Search | Destination Fareways',
                'meta_description' => 'Search live flight fares, routes, and cabin promotions. Compare major carriers for economy, business, and first class reservations.',
                'meta_keywords' => 'search flights, cheap airfare, compare airlines, travel routes',
            ],
            [
                'page_identifier' => 'offers',
                'page_name' => 'Special Offers',
                'meta_title' => 'Featured Travel Deals & Promo Fare Clearances | Destination Fareways',
                'meta_description' => 'Limited-time flight promotions, airline seat clearance sales, and holiday package discounts to top global destinations.',
                'meta_keywords' => 'flight offers, flight promo code, discounted seat clearances, airline coupons',
            ],
            [
                'page_identifier' => 'destinations',
                'page_name' => 'Destinations',
                'meta_title' => 'Top Travel Destinations & Vacation Airfare Specials | Destination Fareways',
                'meta_description' => 'Explore detailed city guides, best visiting seasons, starting prices, and flight routes to the absolute best vacation getaways.',
                'meta_keywords' => 'travel destinations, city guides, flight routes, vacation packages',
            ],
            [
                'page_identifier' => 'blogs',
                'page_name' => 'Travel Blog',
                'meta_title' => 'Luxury Travel Insights & Airline Cabin Comparisons | Destination Fareways',
                'meta_description' => 'Get expert travel tips, cabin seat comparisons, and destination itineraries from our team of premium travel editors.',
                'meta_keywords' => 'travel blog, luxury travel tips, business class comparisons, airline reviews',
            ],
            [
                'page_identifier' => 'faq',
                'page_name' => 'Frequently Asked Questions',
                'meta_title' => 'Frequently Asked Questions & Baggage Rules | Destination Fareways',
                'meta_description' => 'Find answers to flight bookings, baggage allowances, cabin upgrade options, travel insurance, and refund policies.',
                'meta_keywords' => 'frequently asked questions, faq, baggage rules, flight refunds',
            ],
            [
                'page_identifier' => 'contact',
                'page_name' => 'Contact Us',
                'meta_title' => 'Contact Our 24/7 Flight Support Concierge | Destination Fareways',
                'meta_description' => 'Get in touch with our travel experts. Call our direct line or submit a message for assistance with flight reservations.',
                'meta_keywords' => 'contact destination fareways, customer support, 24/7 concierge, flight reservations',
            ],
            [
                'page_identifier' => 'newsletter',
                'page_name' => 'Newsletter Subscription',
                'meta_title' => 'Subscribe to Exclusive Unpublished Flight Deals | Destination Fareways',
                'meta_description' => 'Join our premium newsletter list to receive weekly notifications of exclusive, phone-only travel deals and promo clearances.',
                'meta_keywords' => 'newsletter, flight deals newsletter, unpublished airline tickets, promo codes',
            ],
        ];

        foreach ($pagesSeo as $seo) {
            SeoSetting::updateOrCreate(
                ['page_identifier' => $seo['page_identifier']],
                array_merge($seo, [
                    'canonical_url' => 'https://destinationfareways.com/' . ($seo['page_identifier'] === 'home' ? '' : $seo['page_identifier']),
                    'og_title' => $seo['meta_title'],
                    'og_description' => $seo['meta_description'],
                    'twitter_title' => $seo['meta_title'],
                    'twitter_description' => $seo['meta_description'],
                ])
            );
        }
    }
}
