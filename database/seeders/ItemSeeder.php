<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ItemSeeder extends Seeder
{
    public function run()
    {
        // Get category IDs
        $skincare = Category::where('name_en', 'Skincare')->first();
        $haircare = Category::where('name_en', 'Haircare')->first();
        $makeup = Category::where('name_en', 'Makeup')->first();
        $fragrances = Category::where('name_en', 'Fragrances')->first();
        $bodycare = Category::where('name_en', 'Bodycare')->first();

        // Seed items for Skincare
        $skincareItems = [
            ['trade_name_en' => 'Moisturizer', 'used_for_en' => 'Moisturizing the skin', 'trade_name_fa' => 'مرطوب‌کننده', 'used_for_fa' => 'مرطوب‌سازی پوست', 'size' => '50ml', 'description_en' => 'A moisturizing product for dry skin.', 'description_fa' => 'محصولی مرطوب‌کننده برای پوست خشک.'],
            ['trade_name_en' => 'Cleanser', 'used_for_en' => 'Cleansing the skin', 'trade_name_fa' => 'پاک‌کننده', 'used_for_fa' => 'پاک‌سازی پوست', 'size' => '100ml', 'description_en' => 'A gentle cleanser for everyday use.', 'description_fa' => 'پاک‌کننده ملایم برای استفاده روزانه.'],
            ['trade_name_en' => 'Serum', 'used_for_en' => 'Nourishing and treating skin', 'trade_name_fa' => 'سرم', 'used_for_fa' => 'تغذیه و درمان پوست', 'size' => '30ml', 'description_en' => 'A serum to treat wrinkles and fine lines.', 'description_fa' => 'سرم برای درمان چین و چروک‌ها و خطوط ریز.'],
            ['trade_name_en' => 'Sunscreen', 'used_for_en' => 'Protecting skin from UV rays', 'trade_name_fa' => 'ضد آفتاب', 'used_for_fa' => 'محافظت از پوست در برابر اشعه UV', 'size' => '50ml', 'description_en' => 'A sunscreen to protect against harmful UV rays.', 'description_fa' => 'ضد آفتاب برای محافظت در برابر اشعه‌های مضر UV.'],
            ['trade_name_en' => 'Toner', 'used_for_en' => 'Balancing skin pH', 'trade_name_fa' => 'تونر', 'used_for_fa' => 'متعادل کردن pH پوست', 'size' => '200ml', 'description_en' => 'A toner for refreshing and balancing skin.', 'description_fa' => 'تونر برای شادابی و تعادل پوست.'],
        ];

        foreach ($skincareItems as $item) {
            Item::create([
                'item_code' => 'SKC-' . rand(1000, 9999),
                'trade_name_en' => $item['trade_name_en'],
                'used_for_en' => $item['used_for_en'],
                'trade_name_fa' => $item['trade_name_fa'],
                'used_for_fa' => $item['used_for_fa'],
                'size' => $item['size'],
                'description_en' => $item['description_en'],
                'description_fa' => $item['description_fa'],
                'category_id' => $skincare->id,
            ]);
        }

        // Seed items for Haircare
        $haircareItems = [
            ['trade_name_en' => 'Shampoo', 'used_for_en' => 'Cleansing the hair', 'trade_name_fa' => 'شامپو', 'used_for_fa' => 'پاک‌سازی مو', 'size' => '250ml', 'description_en' => 'A shampoo for cleaning hair and scalp.', 'description_fa' => 'شامپویی برای تمیز کردن مو و پوست سر.'],
            ['trade_name_en' => 'Conditioner', 'used_for_en' => 'Conditioning the hair', 'trade_name_fa' => 'نرم‌کننده', 'used_for_fa' => 'نرم‌سازی مو', 'size' => '200ml', 'description_en' => 'A conditioner to smooth and nourish hair.', 'description_fa' => 'نرم‌کننده برای نرم و تغذیه موها.'],
            ['trade_name_en' => 'Hair Mask', 'used_for_en' => 'Deep hair treatment', 'trade_name_fa' => 'ماسک مو', 'used_for_fa' => 'درمان عمیق مو', 'size' => '100ml', 'description_en' => 'A nourishing hair mask for deep treatment.', 'description_fa' => 'ماسک مو برای درمان عمیق موها.'],
            ['trade_name_en' => 'Hair Oil', 'used_for_en' => 'Moisturizing and nourishing hair', 'trade_name_fa' => 'روغن مو', 'used_for_fa' => 'مرطوب‌سازی و تغذیه مو', 'size' => '50ml', 'description_en' => 'Hair oil for soft and shiny hair.', 'description_fa' => 'روغن مو برای داشتن موهای نرم و درخشان.'],
            ['trade_name_en' => 'Hair Serum', 'used_for_en' => 'Treating damaged hair', 'trade_name_fa' => 'سرم مو', 'used_for_fa' => 'درمان موهای آسیب‌دیده', 'size' => '30ml', 'description_en' => 'A serum to repair damaged hair.', 'description_fa' => 'سرم برای ترمیم موهای آسیب‌دیده.'],
        ];

        foreach ($haircareItems as $item) {
            Item::create([
                'item_code' => 'HCR-' . rand(1000, 9999),
                'trade_name_en' => $item['trade_name_en'],
                'used_for_en' => $item['used_for_en'],
                'trade_name_fa' => $item['trade_name_fa'],
                'used_for_fa' => $item['used_for_fa'],
                'size' => $item['size'],
                'description_en' => $item['description_en'],
                'description_fa' => $item['description_fa'],
                'category_id' => $haircare->id,
            ]);
        }
        // Seed items for Makeup
        $makeupItems = [
            ['trade_name_en' => 'Foundation', 'used_for_en' => 'Evening out skin tone', 'trade_name_fa' => 'فاندیشن', 'used_for_fa' => 'یکدست کردن رنگ پوست', 'size' => '30ml', 'description_en' => 'A foundation for smooth and flawless skin.', 'description_fa' => 'فاندیشنی برای پوست صاف و بی‌عیب.'],
            ['trade_name_en' => 'Lipstick', 'used_for_en' => 'Coloring the lips', 'trade_name_fa' => 'رژ لب', 'used_for_fa' => 'رنگ زدن به لب‌ها', 'size' => '5g', 'description_en' => 'A lipstick for bold and vibrant lips.', 'description_fa' => 'رژ لبی برای لب‌های برجسته و پررنگ.'],
            ['trade_name_en' => 'Mascara', 'used_for_en' => 'Enhancing eyelashes', 'trade_name_fa' => 'ریمل', 'used_for_fa' => 'افزایش حجم مژه‌ها', 'size' => '10ml', 'description_en' => 'A mascara for long and voluminous eyelashes.', 'description_fa' => 'ریملی برای مژه‌های بلند و پرحجم.'],
            ['trade_name_en' => 'Eyeshadow', 'used_for_en' => 'Coloring the eyelids', 'trade_name_fa' => 'سایه چشم', 'used_for_fa' => 'رنگ کردن پلک‌ها', 'size' => '4g', 'description_en' => 'An eyeshadow for a dramatic eye look.', 'description_fa' => 'سایه چشمی برای ایجاد چشم‌هایی دراماتیک.'],
            ['trade_name_en' => 'Blush', 'used_for_en' => 'Adding color to the cheeks', 'trade_name_fa' => 'رنگ گونه', 'used_for_fa' => 'افزودن رنگ به گونه‌ها', 'size' => '8g', 'description_en' => 'A blush to add a rosy glow to the cheeks.', 'description_fa' => 'رنگ گونه‌ای برای افزودن درخشش صورتی به گونه‌ها.'],
        ];

        foreach ($makeupItems as $item) {
            Item::create([
                'item_code' => 'MKP-' . rand(1000, 9999),  // 4-digit random number for item_code
                'trade_name_en' => $item['trade_name_en'],
                'used_for_en' => $item['used_for_en'],
                'trade_name_fa' => $item['trade_name_fa'],
                'used_for_fa' => $item['used_for_fa'],
                'size' => $item['size'],
                'description_en' => $item['description_en'],
                'description_fa' => $item['description_fa'],
                'category_id' => $makeup->id,
            ]);
        }

        // Seed items for Fragrances
        $fragranceItems = [
            ['trade_name_en' => 'Eau de Parfum', 'used_for_en' => 'Fragrance for body', 'trade_name_fa' => 'اود پرفیوم', 'used_for_fa' => 'عطر بدن', 'size' => '50ml', 'description_en' => 'A luxurious fragrance for all-day wear.', 'description_fa' => 'عطر لوکسی برای استفاده روزانه.'],
            ['trade_name_en' => 'Body Spray', 'used_for_en' => 'Refreshing body fragrance', 'trade_name_fa' => 'اسپری بدن', 'used_for_fa' => 'عطر بدن تازه‌کننده', 'size' => '200ml', 'description_en' => 'A body spray for a refreshing fragrance.', 'description_fa' => 'اسپری بدنی برای عطری تازه و شاداب.'],
        ];

        foreach ($fragranceItems as $item) {
            Item::create([
                'item_code' => 'FRG-' . rand(1000, 9999),  // 4-digit random number for item_code
                'trade_name_en' => $item['trade_name_en'],
                'used_for_en' => $item['used_for_en'],
                'trade_name_fa' => $item['trade_name_fa'],
                'used_for_fa' => $item['used_for_fa'],
                'size' => $item['size'],
                'description_en' => $item['description_en'],
                'description_fa' => $item['description_fa'],
                'category_id' => $fragrances->id,
            ]);
        }

        // Seed items for Bodycare
        $bodycareItems = [
            ['trade_name_en' => 'Body Lotion', 'used_for_en' => 'Moisturizing the body', 'trade_name_fa' => 'لوسیون بدن', 'used_for_fa' => 'مرطوب‌سازی بدن', 'size' => '250ml', 'description_en' => 'A body lotion for smooth and hydrated skin.', 'description_fa' => 'لوسیونی برای پوست نرم و مرطوب.'],
            ['trade_name_en' => 'Hand Cream', 'used_for_en' => 'Moisturizing and nourishing hands', 'trade_name_fa' => 'کرم دست', 'used_for_fa' => 'مرطوب‌سازی و تغذیه دست‌ها', 'size' => '100ml', 'description_en' => 'A hand cream for soft and nourished hands.', 'description_fa' => 'کرم دستی برای نرم و تغذیه دست‌ها.'],
        ];

        foreach ($bodycareItems as $item) {
            Item::create([
                'item_code' => 'BC-' . rand(1000, 9999),  // 4-digit random number for item_code
                'trade_name_en' => $item['trade_name_en'],
                'used_for_en' => $item['used_for_en'],
                'trade_name_fa' => $item['trade_name_fa'],
                'used_for_fa' => $item['used_for_fa'],
                'size' => $item['size'],
                'description_en' => $item['description_en'],
                'description_fa' => $item['description_fa'],
                'category_id' => $bodycare->id,
            ]);
        }
    }
}
