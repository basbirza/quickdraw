<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $selvedgeJeans = Category::where('slug', 'selvedge-jeans')->first();
        $denimJackets = Category::where('slug', 'denim-jackets')->first();
        $shirtsFlannels = Category::where('slug', 'shirts-flannels')->first();
        $outerwear = Category::where('slug', 'outerwear')->first();
        $teesHenleys = Category::where('slug', 'tees-henleys')->first();
        $accessories = Category::where('slug', 'accessories')->first();

        // SELVEDGE JEANS (12 products)
        $this->createDenimProduct('The Frontier Slim', 'frontier-slim', '14oz Japanese Selvedge', 189, 'NEW', '#1a1a2e', $selvedgeJeans, '14oz', 'Kaihara, Japan', 'frontier-slim.png');
        $this->createDenimProduct('The Rancher Straight', 'rancher-straight', '17oz Bumpy Denim', 209, 'BESTSELLER', '#1c2333', $selvedgeJeans, '17oz', 'Oni Denim, Japan', 'rancher-straight.png');
        $this->createDenimProduct('The Prospector Relaxed', 'prospector-relaxed', '15oz Okayama Selvedge', 199, null, '#1a1a2e', $selvedgeJeans, '15oz', 'Okayama, Japan');
        $this->createDenimProduct('The Drifter Wide', 'drifter-wide', '13.5oz Vintage Selvedge', 179, null, '#22223b', $selvedgeJeans, '13.5oz', 'Kuroki, Japan');
        $this->createDenimProduct('The Outlaw Slim Tapered', 'outlaw-slim-tapered', '16oz Unsanforized Indigo', 219, null, '#1c2333', $selvedgeJeans, '16oz', 'Collect, Japan');
        $this->createDenimProduct('The Mustang Boot Cut', 'mustang-boot-cut', '14oz Red Cast Selvedge', 189, null, '#2c1a1a', $selvedgeJeans, '14oz', 'Kaihara, Japan');
        $this->createDenimProduct('The Canyon Straight', 'canyon-straight', '12oz Lightweight Selvedge', 169, 'NEW', '#1a2a3a', $selvedgeJeans, '12oz', 'Kurabo, Japan');
        $this->createDenimProduct('The Trailblazer Slim', 'trailblazer-slim', '18oz Heavyweight Indigo', 239, null, '#0f0f1e', $selvedgeJeans, '18oz', 'Oni Denim, Japan');
        $this->createDenimProduct('The Wrangler Relaxed Tapered', 'wrangler-relaxed-tapered', '14oz Left-Hand Twill', 195, null, '#1e1e30', $selvedgeJeans, '14oz', 'Nihon Menpu, Japan');
        $this->createDenimProduct('The Highwayman Slim', 'highwayman-slim', '15oz Black x Black Selvedge', 209, null, '#111118', $selvedgeJeans, '15oz', 'Kaihara, Japan');
        $this->createDenimProduct('The Settler Regular', 'settler-regular', '13oz Natural Indigo', 225, null, '#1a2040', $selvedgeJeans, '13oz', 'Pure Blue Japan');
        $this->createDenimProduct('The Maverick Athletic', 'maverick-athletic', '14oz Stretch Selvedge', 185, null, '#1c1c30', $selvedgeJeans, '14oz', 'Kaihara, Japan');

        // DENIM JACKETS (6 products)
        $this->createJacketProduct('The Type III Trucker', 'type-iii-trucker', '14oz Indigo x Indigo Selvedge', 229, 'BESTSELLER', '#1b2a4a', $denimJackets, '14oz', 'Kaihara, Japan');
        $this->createJacketProduct('The Stockman Jacket', 'stockman-jacket', '16oz Unsanforized Raw Selvedge', 249, null, '#1a1a2e', $denimJackets, '16oz', 'Collect, Japan');
        $this->createJacketProduct('The Bandit Sherpa-Lined', 'bandit-sherpa-lined', '14oz Indigo with Sherpa Lining', 279, 'NEW', '#22223b', $denimJackets, '14oz', 'Kaihara, Japan');
        $this->createJacketProduct('The Roughneck Chore Coat', 'roughneck-chore-coat', '12oz Washed Selvedge', 219, null, '#2c3040', $denimJackets, '12oz', 'Kuroki, Japan');
        $this->createJacketProduct('The Desperado Black', 'desperado-black', '15oz Black x Black Selvedge', 239, null, '#111118', $denimJackets, '15oz', 'Kurabo, Japan');
        $this->createJacketProduct('The Wagoneer Blanket-Lined', 'wagoneer-blanket-lined', '14oz Indigo with Wool Lining', 289, null, '#1c2333', $denimJackets, '14oz', 'Kaihara, Japan');

        // SHIRTS & FLANNELS (9 products)
        $this->createShirtProduct('The Homesteader Flannel', 'homesteader-flannel', 'Heavyweight Buffalo Check', 89, 'BESTSELLER', '#8b2500', $shirtsFlannels);
        $this->createShirtProduct('The Cattleman Western', 'cattleman-western', '6.5oz Indigo Chambray', 95, null, '#3a5070', $shirtsFlannels);
        $this->createShirtProduct('The Campfire Flannel', 'campfire-flannel', 'Pendleton Wool Blend', 109, 'NEW', '#2d4a2d', $shirtsFlannels);
        $this->createShirtProduct('The Trail Boss Workshirt', 'trail-boss-workshirt', '8oz Hickory Stripe', 85, null, '#d4c9b0', $shirtsFlannels);
        $this->createShirtProduct('The Saloon Chambray', 'saloon-chambray', '4.5oz Japanese Chambray', 79, null, '#6080a0', $shirtsFlannels);
        $this->createShirtProduct('The Frontier Flannel', 'frontier-flannel', 'Brushed Cotton Tartan', 89, null, '#1a3a5a', $shirtsFlannels);
        $this->createShirtProduct('The Bunkhouse Overshirt', 'bunkhouse-overshirt', '10oz Waxed Canvas', 119, null, '#5a4a3a', $shirtsFlannels);
        $this->createShirtProduct('The Prospector Denim Shirt', 'prospector-denim-shirt', '6oz Selvedge Chambray', 99, 'NEW', '#2a3a5a', $shirtsFlannels);
        $this->createShirtProduct('The Ranch Hand Flannel', 'ranch-hand-flannel', 'Heavyweight Ombre Plaid', 95, null, '#4a3a2a', $shirtsFlannels);

        // OUTERWEAR (5 products)
        $this->createOuterwearProduct('The Houston Jacket', 'houston-jacket', 'Waxed Canvas Field Jacket', 269, 'BESTSELLER', '#3a3228', $outerwear, 'houston-jacket1.png');
        $this->createOuterwearProduct('The Rancher Coat', 'rancher-coat', 'Heavyweight Wool Overcoat', 349, null, '#2c2c2c', $outerwear);
        $this->createOuterwearProduct('The Trapper Vest', 'trapper-vest', 'Waxed Cotton Gilet', 149, 'NEW', '#4a3a2a', $outerwear);
        $this->createOuterwearProduct('The Stagecoach Parka', 'stagecoach-parka', 'Insulated Canvas Parka', 329, null, '#2a3020', $outerwear);
        $this->createOuterwearProduct('The Outrider Bomber', 'outrider-bomber', 'Nylon & Wool Blend Bomber', 249, null, '#1a1a20', $outerwear);

        // TEES & HENLEYS (8 products)
        $this->createCasualProduct('Culprits Speedcrew', 'culprits-speedcrew', 'Black Loopwheel Hoodie', 65, null, '#22223b', $teesHenleys, 'culprits-speedcrew1.png');
        $this->createCasualProduct('The Loopwheel Henley', 'loopwheel-henley', 'Heavyweight Tubular Knit', 55, 'BESTSELLER', '#f0ebe0', $teesHenleys);
        $this->createCasualProduct('The Tubular Pocket Tee', 'tubular-pocket-tee', '7oz Slub Cotton', 39, null, '#f5f5f0', $teesHenleys);
        $this->createCasualProduct('The Frontier Henley', 'frontier-henley', 'Waffle Knit Thermal', 49, 'NEW', '#e8ddd0', $teesHenleys);
        $this->createCasualProduct('The Basecamp Tee', 'basecamp-tee', '6.5oz Loopwheel Cotton', 42, null, '#1a1a2e', $teesHenleys);
        $this->createCasualProduct('The Trailhead Stripe Tee', 'trailhead-stripe-tee', 'Indigo Stripe Jersey', 45, null, '#2c3e6b', $teesHenleys);
        $this->createCasualProduct('The Bison Henley', 'bison-henley', 'Heavyweight Slub Cotton', 52, null, '#3a2a20', $teesHenleys);
        $this->createCasualProduct('The Campfire Long Sleeve', 'campfire-long-sleeve', '8oz Tubular Cotton', 48, 'NEW', '#2a3020', $teesHenleys);

        // ACCESSORIES (7 products)
        $this->createAccessoryProduct('The Frontier Belt', 'frontier-belt', 'Vegetable-Tanned Leather', 65, 'BESTSELLER', '#5a3a20', $accessories);
        $this->createAccessoryProduct('The Prospector Wallet', 'prospector-wallet', 'Bridle Leather Bifold', 79, null, '#4a3020', $accessories);
        $this->createAccessoryProduct('The Trailhead Bandana', 'trailhead-bandana', 'Selvedge Cotton Bandana', 19, 'NEW', '#1a2a4a', $accessories);
        $this->createAccessoryProduct('The Wrangler Key Fob', 'wrangler-key-fob', 'Copper & Leather Key Hook', 25, null, '#c45a3a', $accessories);
        $this->createAccessoryProduct('The Ranch Hand Cap', 'ranch-hand-cap', 'Washed Cotton Twill', 35, null, '#2a2a30', $accessories);
        $this->createAccessoryProduct('The Stockman Suspenders', 'stockman-suspenders', 'Elastic & Leather Braces', 45, null, '#3a2a1a', $accessories);
        $this->createAccessoryProduct('The Campfire Tote', 'campfire-tote', 'Waxed Canvas Carryall', 89, 'NEW', '#4a4030', $accessories);
    }

    private function createDenimProduct($name, $slug, $desc, $price, $tag, $color, $category, $weight, $mill, $image = null)
    {
        $product = Product::create([
            'name' => $name,
            'slug' => $slug,
            'short_description' => $desc,
            'full_description' => "Premium {$desc} crafted from Japanese selvedge denim. Woven on vintage shuttle looms, this piece features exceptional construction and will develop beautiful fades over time with wear.",
            'price' => $price,
            'tag' => $tag,
            'color_hex' => $color,
            'weight' => $weight,
            'mill' => $mill,
            'composition' => '100% Cotton',
            'construction' => 'Right-hand twill',
            'treatment' => 'Raw / Unwashed',
            'sanforization' => 'Unsanforized',
            'sizing_info' => "True to size. Expect 1-2 inches of shrinkage in waist and 2-3 inches in inseam after first wash. For best fit, size up if between sizes.",
            'shipping_info' => "Free shipping on orders over €150. Standard delivery 3-5 business days within EU. Express shipping available at checkout.",
            'care_instructions' => "For optimal fades, wear for at least 6 months before first wash. When ready, turn inside out and machine wash cold. Hang to dry. Avoid tumble drying to prevent shrinkage.",
            'sizes_available' => ['28', '29', '30', '31', '32', '33', '34', '36', '38', '40'],
            'stock_quantity' => rand(20, 50),
            'is_active' => true,
            'is_featured' => $tag === 'NEW' || $tag === 'BESTSELLER',
        ]);

        $product->categories()->attach($category);
        if ($image) $this->addImage($product->id, $image, $name);
    }

    private function createJacketProduct($name, $slug, $desc, $price, $tag, $color, $category, $weight, $mill, $image = null)
    {
        $product = Product::create([
            'name' => $name,
            'slug' => $slug,
            'short_description' => $desc,
            'full_description' => "Classic denim jacket in {$desc}. Built to last with reinforced stitching and vintage details. Perfect layering piece that improves with age.",
            'price' => $price,
            'tag' => $tag,
            'color_hex' => $color,
            'weight' => $weight,
            'mill' => $mill,
            'composition' => '100% Cotton',
            'construction' => 'Selvedge denim',
            'treatment' => 'Raw / Unwashed',
            'sizing_info' => "Fits true to size with a classic relaxed fit. Order your normal size for layering, or size down for a slimmer fit.",
            'shipping_info' => "Free shipping on orders over €150. Standard delivery 3-5 business days.",
            'care_instructions' => "Wear often before first wash. Spot clean when needed. Machine wash cold inside out, hang dry.",
            'sizes_available' => ['S', 'M', 'L', 'XL', 'XXL'],
            'stock_quantity' => rand(15, 35),
            'is_active' => true,
            'is_featured' => $tag === 'NEW' || $tag === 'BESTSELLER',
        ]);

        $product->categories()->attach($category);
        if ($image) $this->addImage($product->id, $image, $name);
    }

    private function createShirtProduct($name, $slug, $desc, $price, $tag, $color, $category, $image = null)
    {
        $product = Product::create([
            'name' => $name,
            'slug' => $slug,
            'short_description' => $desc,
            'full_description' => "Timeless {$desc} designed for comfort and durability. Perfect for layering or wearing on its own. Built with quality materials and attention to detail.",
            'price' => $price,
            'tag' => $tag,
            'color_hex' => $color,
            'composition' => 'Cotton blend',
            'sizing_info' => "Classic fit. True to size. Consult size chart for exact measurements.",
            'shipping_info' => "Free shipping on orders over €150. Standard delivery 3-5 business days.",
            'care_instructions' => "Machine wash cold. Tumble dry low or hang dry. Iron on medium heat if needed.",
            'sizes_available' => ['S', 'M', 'L', 'XL', 'XXL'],
            'stock_quantity' => rand(25, 60),
            'is_active' => true,
            'is_featured' => $tag === 'NEW' || $tag === 'BESTSELLER',
        ]);

        $product->categories()->attach($category);
        if ($image) $this->addImage($product->id, $image, $name);
    }

    private function createOuterwearProduct($name, $slug, $desc, $price, $tag, $color, $category, $image = null)
    {
        $product = Product::create([
            'name' => $name,
            'slug' => $slug,
            'short_description' => $desc,
            'full_description' => "Premium {$desc} built for durability and style. Features quality construction, functional pockets, and timeless design. An essential piece for any wardrobe.",
            'price' => $price,
            'tag' => $tag,
            'color_hex' => $color,
            'composition' => 'Waxed canvas and cotton blend',
            'sizing_info' => "Regular fit with room for layering. True to size. Check measurements for best fit.",
            'shipping_info' => "Free shipping on orders over €150. Standard delivery 3-5 business days.",
            'care_instructions' => "Spot clean with damp cloth. Do not machine wash. Re-wax annually to maintain water resistance.",
            'sizes_available' => ['S', 'M', 'L', 'XL', 'XXL'],
            'stock_quantity' => rand(10, 30),
            'is_active' => true,
            'is_featured' => $tag === 'NEW' || $tag === 'BESTSELLER',
        ]);

        $product->categories()->attach($category);
        if ($image) $this->addImage($product->id, $image, $name);
    }

    private function createCasualProduct($name, $slug, $desc, $price, $tag, $color, $category, $image = null)
    {
        $product = Product::create([
            'name' => $name,
            'slug' => $slug,
            'short_description' => $desc,
            'full_description' => "Comfortable {$desc} made from premium materials. Perfect for everyday wear with a focus on quality and longevity.",
            'price' => $price,
            'tag' => $tag,
            'color_hex' => $color,
            'composition' => 'Premium cotton knit',
            'sizing_info' => "Regular fit. True to size. Consult size chart for measurements.",
            'shipping_info' => "Free shipping on orders over €150. Standard delivery 3-5 business days.",
            'care_instructions' => "Machine wash cold. Tumble dry low or hang dry to prevent shrinkage.",
            'sizes_available' => ['S', 'M', 'L', 'XL', 'XXL'],
            'stock_quantity' => rand(30, 70),
            'is_active' => true,
            'is_featured' => $tag === 'NEW' || $tag === 'BESTSELLER',
        ]);

        $product->categories()->attach($category);
        if ($image) $this->addImage($product->id, $image, $name);
    }

    private function createAccessoryProduct($name, $slug, $desc, $price, $tag, $color, $category, $image = null)
    {
        $product = Product::create([
            'name' => $name,
            'slug' => $slug,
            'short_description' => $desc,
            'full_description' => "Handcrafted {$desc} made with premium materials. Designed to develop character and last for years.",
            'price' => $price,
            'tag' => $tag,
            'color_hex' => $color,
            'composition' => 'Premium materials',
            'sizing_info' => "One size fits most. See product details for specific measurements.",
            'shipping_info' => "Free shipping on orders over €150. Standard delivery 3-5 business days.",
            'care_instructions' => "Wipe clean with soft cloth. Condition leather items periodically to maintain quality.",
            'sizes_available' => ['One Size'],
            'stock_quantity' => rand(40, 80),
            'is_active' => true,
            'is_featured' => $tag === 'NEW' || $tag === 'BESTSELLER',
        ]);

        $product->categories()->attach($category);
        if ($image) $this->addImage($product->id, $image, $name);
    }

    private function addImage($productId, $imagePath, $altText)
    {
        ProductImage::create([
            'product_id' => $productId,
            'image_path' => 'products/' . $imagePath,
            'image_type' => 'main',
            'sort_order' => 0,
            'alt_text' => $altText,
        ]);
    }
}
