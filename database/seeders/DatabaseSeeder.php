<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin + demo customer (always — these are laravel_* local tables).
        User::updateOrCreate(
            ['email' => 'admin@janaushadhi.example'],
            [
                'id' => (string) Uuid::uuid4(),
                'name' => 'Store Admin',
                'password' => 'password',
                'role' => 'admin',
            ]
        );
        User::updateOrCreate(
            ['email' => 'customer@janaushadhi.example'],
            [
                'id' => (string) Uuid::uuid4(),
                'name' => 'Demo Customer',
                'password' => 'password',
                'role' => 'customer',
            ]
        );

        // Sample coupon (laravel_coupons — local).
        Coupon::updateOrCreate(
            ['code' => 'JANSAVE10'],
            [
                'id' => (string) Uuid::uuid4(),
                'type' => 'percent',
                'value' => 10,
                'min_order_amount' => 200,
                'max_discount' => 100,
                'usage_limit' => 1000,
                'used_count' => 0,
                'is_active' => true,
            ]
        );

        // Catalog (products/categories) — only seed locally on sqlite; in prod
        // the shared InsForge Postgres already holds 2439 products / 60 categories.
        if (config('database.shared') !== 'sqlite') {
            $this->command->info('Shared catalog is remote (pgsql); skipping local catalog seed.');
            return;
        }

        $csv = database_path('meds.csv');
        if (! is_file($csv)) {
            $this->command->warn("meds.csv not found at {$csv}; skipping catalog seed.");
            return;
        }

        $rows = array_map('str_getcsv', file($csv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
        $header = array_map('trim', array_shift($rows));
        $idx = array_flip($header); // Sr No, Drug Code, Generic Name, Unit Size, MRP, Group Name

        $categoryIds = [];   // name => uuid
        $seenSlugs = [];
        $productBatch = [];
        $featuredPerCat = [];

        DB::connection(config('database.shared'))->beginTransaction();
        foreach ($rows as $r) {
            $group = trim($r[$idx['Group Name']] ?? 'General');
            if ($group === '') $group = 'General';
            $name = trim($r[$idx['Generic Name']] ?? '');
            if ($name === '') continue;
            $drugCode = trim($r[$idx['Drug Code']] ?? '');
            $unit = trim($r[$idx['Unit Size']] ?? '');
            $mrp = (float) preg_replace('/[^0-9.]/', '', $r[$idx['MRP']] ?? '0');

            if (! isset($categoryIds[$group])) {
                $cid = (string) Uuid::uuid4();
                $categoryIds[$group] = $cid;
                $catSlug = Str::slug($group) ?: 'category';
                if (isset($seenSlugs['cat:' . $catSlug])) {
                    $catSlug .= '-' . (count($categoryIds));
                }
                $seenSlugs['cat:' . $catSlug] = true;
                Category::create([
                    'id' => $cid,
                    'name' => $group,
                    'slug' => $catSlug,
                    'sort_order' => count($categoryIds),
                    'created_at' => now(),
                ]);
            }
            $cid = $categoryIds[$group];

            $slug = Str::slug($name . ' ' . $unit . ' ' . $drugCode);
            if ($slug === '' || isset($seenSlugs[$slug])) {
                $slug = $slug . '-' . substr(md5($drugCode . $name), 0, 6);
            }
            $seenSlugs[$slug] = true;

            $featured = ! isset($featuredPerCat[$group]);
            $featuredPerCat[$group] = true;

            $productBatch[] = [
                'id' => (string) Uuid::uuid4(),
                'drug_code' => $drugCode,
                'name' => $name,
                'slug' => $slug,
                'description' => "{$name} — pack size {$unit}. Category: {$group}. Drug code {$drugCode}.",
                'category_id' => $cid,
                'unit_size' => $unit,
                'mrp' => $mrp,
                'price' => $mrp, // Jan Aushadhi sells at subsidised MRP
                'stock' => 25 + (crc32($drugCode) % 200),
                'is_active' => true,
                'is_featured' => $featured,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($productBatch) >= 300) {
                Product::insert($productBatch);
                $productBatch = [];
            }
        }
        if ($productBatch) Product::insert($productBatch);
        DB::connection(config('database.shared'))->commit();

        $this->command->info('Seeded ' . count($categoryIds) . ' categories and ' . count($seenSlugs) . ' products.');
    }
}
