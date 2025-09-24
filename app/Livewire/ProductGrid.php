<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductGrid extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 8; // Productos por página

    public $showTitle = true; // Mostrar título

    public function mount($perPage = 8, $showTitle = true)
    {
        $this->perPage = $perPage;
        $this->showTitle = $showTitle;
    }

    public function paginationView()
    {
        return 'vendor.livewire.custom-bootstrap';
    }

    public function render()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->paginate($this->perPage);

        return view('livewire.product-grid', [
            'products' => $products,
        ]);
    }
}
