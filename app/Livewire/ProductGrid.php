<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductGrid extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->paginate(8);

        return view('livewire.product-grid', [
            'products' => $products
        ]);
    }
}
