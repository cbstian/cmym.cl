<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear directorio de productos si no existe
        Storage::disk('public')->makeDirectory('products');

        $productos = [
            [
                'nombre' => 'SILLA COLGANTE SIMPLE',
                'image' => 'product-1-sample.jpg',
                'precio' => 120000,
                'categoria' => 'Sillas',
            ],
            [
                'nombre' => 'SILLA COLGANTE DOBLE',
                'image' => 'product-2-sample.jpg',
                'precio' => 150000,
                'categoria' => 'Sillas',
            ],
            [
                'nombre' => 'SILLA REPOSERA',
                'image' => 'product-3-sample.jpg',
                'precio' => 35000,
                'categoria' => 'Sillas',
            ],
            [
                'nombre' => 'ESTUFA PIRÁMIDE',
                'image' => 'product-4-sample.jpg',
                'precio' => 10000,
                'categoria' => 'Estufas',
            ],
            [
                'nombre' => 'ESTUFA COMFI M9',
                'image' => 'product-5-sample.jpg',
                'precio' => 10000,
                'categoria' => 'Estufas',
            ],
            [
                'nombre' => 'TOLDO PREMIUM 3X3',
                'image' => 'product-6-sample.jpg',
                'precio' => 80000,
                'categoria' => 'Toldos',
            ]
        ];

        foreach ($productos as $producto) {
            $category = Category::where('name', $producto['categoria'])->first();

            if (!$category) {
                continue; // Si no existe la categoría, saltar este producto
            }

            // Copiar imagen desde resources/images al disco público
            $sourceImagePath = resource_path('images/' . $producto['image']);
            $destinationPath = 'products/' . $producto['image'];

            if (file_exists($sourceImagePath)) {
                Storage::disk('public')->put($destinationPath, file_get_contents($sourceImagePath));
            }

            Product::create([
                'category_id' => $category->id,
                'name' => $producto['nombre'],
                'slug' => Str::slug($producto['nombre']),
                'description' => 'Descripción del producto ' . strtolower($producto['nombre']),
                'short_description' => 'Descripción corta del producto',
                'sku' => 'SKU' . str_pad(array_search($producto, $productos) + 1, 3, '0', STR_PAD_LEFT),
                'price' => $producto['precio'],
                'sale_price' => null,
                'weight' => rand(1, 10),
                'dimensions' => rand(10, 50) . ' x ' . rand(10, 50) . ' x ' . rand(10, 50) . ' cm',
                'is_active' => true,
                'is_featured' => rand(0, 1) == 1,
                'image_primary_path' => $destinationPath,
                'image_paths' => [
                    $destinationPath,
                ],
            ]);
        }
    }
}
