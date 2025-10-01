<?php

namespace App\Livewire;

use App\Mail\ContactFormMail;
use App\Models\FormContact;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactForm extends Component
{
    public string $nombre = '';

    public string $correo = '';

    public string $telefono = '';

    public string $direccion = '';

    public string $mensaje = '';

    protected array $rules = [
        'nombre' => 'required|string|max:255',
        'correo' => 'required|email|max:255',
        'telefono' => 'nullable|string|max:255',
        'direccion' => 'nullable|string|max:255',
        'mensaje' => 'required|string',
    ];

    public function submit(): void
    {
        $this->validate();

        $contact = FormContact::create([
            'nombre' => $this->nombre,
            'correo' => $this->correo,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'mensaje' => $this->mensaje,
        ]);

        // Enviar correo de notificación a los administradores
        try {
            $adminEmail = config('mail.from.address');
            Mail::to($adminEmail)->send(new ContactFormMail($contact));
        } catch (\Exception $e) {
            // Log error but don't fail the form submission
            Log::error('Error enviando correo de contacto: '.$e->getMessage());
        }

        $this->reset(['nombre', 'correo', 'telefono', 'direccion', 'mensaje']);

        session()->flash('message', '¡Mensaje enviado exitosamente!');
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
