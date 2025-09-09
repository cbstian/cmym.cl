<?php

namespace App\Livewire;

use App\Models\Faq;
use Livewire\Component;

class FaqList extends Component
{
    public function render()
    {
        $faqs = Faq::orderBy('sort')->get();

        return view('livewire.faq-list', compact('faqs'));
    }
}
