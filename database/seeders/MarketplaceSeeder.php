<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Package;
use App\Models\PackagePrice;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories
        $categories = [
            ['id' => 1, 'name' => 'Cannabis', 'icon' => 'leaf'],
            ['id' => 2, 'name' => 'Hash', 'icon' => 'cube'],
            ['id' => 3, 'name' => 'Extracts', 'icon' => 'droplet'],
        ];

        foreach ($categories as $category) {
            ItemCategory::firstOrCreate(['id' => $category['id']], $category);
        }

        // Create mock vendors
        $mockVendors = [
            'Plugutopia', 'Hofmanncrew', 'Merckgrade', 'UAEDROPS', 'Norcalgreat',
            'ozdope', 'Roaryohara', 'Chembros', 'Dankorignal', 'JohnAlite',
            'Chadfontain', 'Grimbastard', 'Paladin', 'Potpacks', 'StrainPirate',
            'BERGHAIN', 'Kushmountain', 'Stoopchild20', 'Bostongeorge',
        ];

        $vendors = [];
        foreach ($mockVendors as $username) {
            $vendors[] = User::firstOrCreate(
                ['username' => $username],
                [
                    'uuid' => (string) Str::uuid(),
                    'passphrase_hash' => Hash::make('password123'),
                    'registration_date' => now(),
                    'invite_code' => (string) Str::uuid(),
                    'is_seller' => true,
                    'is_trusted_seller' => rand(0, 1), // Random trusted status
                ]
            );
        }

        // Mock listings
        $mockListings = [
            ['name' => 'Cali Kush [A+++]', 'description' => "🤯 40 telegram:calibudog: HYBRID, INDICA, SATIVA❗️\n🛒 Our oil is FREE OF PESTICIDES. NO FILLERS OR HEAVY METALS. Check our LABS ❗️\n📦Packages are tracked, stealth, legit\nInternational shipping with 100% reship guarantee", 'price' => 10.00, 'category' => 1],
            ['name' => 'Gelato 41 [A+++] 100g', 'description' => "Premium Gelato 41, lab tested, tracked shipping\n🛒 FREE OF PESTICIDES. NO FILLERS OR HEAVY METALS\n📦Tracked, stealth packaging\nInternational orders ship within 24 hours", 'price' => 1600.00, 'category' => 1],
            ['name' => 'Gelato 41 [A+++] 500g', 'description' => "Bulk Gelato 41, international shipping available\n🤯 TOP QUALITY - Lab tested\n📦Guaranteed delivery and satisfaction\nTracking provided. 100% reship if undelivered", 'price' => 2475.00, 'category' => 1],
            ['name' => 'Amnesia Haze [A+++]', 'description' => "Top quality Amnesia Haze, Hybrid strain\n🛒 NO FILLERS OR HEAVY METALS\n📦Stealth, legit packaging\nInternational shipping available", 'price' => 550.00, 'category' => 1],
            ['name' => 'White Widow [A+++]', 'description' => "Classic White Widow strain, stealth packaging\n🤯 Premium quality - Lab tested\n📦Tracked shipping worldwide", 'price' => 550.00, 'category' => 1],
            ['name' => 'Super Silver Haze [A+++]', 'description' => "Premium Super Silver Haze, guaranteed delivery\n🛒 FREE OF PESTICIDES\n📦International orders ship within 24 hours", 'price' => 550.00, 'category' => 1],
            ['name' => 'OG Kush [A+++]', 'description' => "Original OG Kush, tracked international shipping\n🤯 TOP SHELF QUALITY\n📦Stealth packaging, tracked delivery", 'price' => 550.00, 'category' => 1],
            ['name' => 'Lemon Kush [A+++]', 'description' => "Fresh Lemon Kush, pesticide-free, lab tested\n🛒 Premium quality guaranteed\n📦International shipping with tracking", 'price' => 550.00, 'category' => 1],
            ['name' => 'Trainwreck [A+++]', 'description' => "High quality Trainwreck, stealth packaging\n🤯 Sativa dominant hybrid\n📦Tracked, guaranteed delivery", 'price' => 550.00, 'category' => 1],
            ['name' => 'Northern Lights [A+++]', 'description' => "Classic Northern Lights strain, tracked shipping\n🛒 Lab tested, pesticide-free\n📦International shipping available", 'price' => 550.00, 'category' => 1],
            ['name' => 'Green Mountain Extracts', 'description' => "Premium extracts, free of pesticides\n🤯 TOP QUALITY EXTRACTS\n📦Stealth packaging, tracked delivery", 'price' => 550.00, 'category' => 3],
            ['name' => 'Ketama Hash [A++ THC 45%]', 'description' => "High THC Ketama hash, 100% reship guarantee\n🛒 THC 45% - Lab tested\n📦International shipping with tracking", 'price' => 550.00, 'category' => 2],
            ['name' => 'Kosher Kush Hash [A++ THC 45%]', 'description' => "Premium Kosher Kush hash, tracked delivery\n🤯 THC 45% guaranteed\n📦Stealth packaging worldwide", 'price' => 550.00, 'category' => 2],
            ['name' => 'Ice-O-Lator Hash 1000g', 'description' => "Top quality Ice-O-Lator hash, lab tested\n🛒 Premium hash - 1kg\n📦Tracked international shipping", 'price' => 5500.00, 'category' => 2],
            ['name' => 'Girl Scout Cookies Hash', 'description' => "GSC hash, premium quality, stealth shipping\n🤯 TOP SHELF HASH\n📦Guaranteed delivery worldwide", 'price' => 5500.00, 'category' => 2],
            ['name' => 'Mochi Gelato [A++] 100kg', 'description' => "Bulk Mochi Gelato, international shipping\n🛒 WHOLESALE QUANTITY - 100kg\n📦Tracked shipping, guaranteed delivery", 'price' => 220000.00, 'category' => 1],
        ];

        // Create items for each vendor
        foreach ($vendors as $vendor) {
            foreach ($mockListings as $listing) {
                $item = Item::create([
                    'uuid' => (string) Str::uuid(),
                    'name' => $listing['name'],
                    'description' => $listing['description'],
                    'user_uuid' => $vendor->uuid,
                    'item_category_id' => $listing['category'],
                    'number_of_views' => rand(10, 500),
                    'number_of_sales' => rand(0, 50),
                ]);

                $package = Package::create([
                    'uuid' => (string) Str::uuid(),
                    'name' => $listing['name'],
                    'description' => 'International shipping available. Tracked and stealth packaging.',
                    'type' => 'mail',
                    'item_uuid' => $item->uuid,
                    'country_name_en_shipping_from' => 'WORLDWIDE',
                    'country_name_en_shipping_to' => 'WORLDWIDE',
                ]);

                PackagePrice::create([
                    'uuid' => $package->uuid,
                    'currency' => 'EUR',
                    'price' => $listing['price'],
                ]);
            }
        }

        $this->command->info('Marketplace seeded successfully!');
        $this->command->info('Created ' . count($vendors) . ' vendors');
        $this->command->info('Created ' . (count($vendors) * count($mockListings)) . ' products');
    }
}
