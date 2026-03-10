<?php

namespace App\Livewire\Settings;

use App\Concerns\ProfileValidationRules;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\User;
use App\Models\Zona;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Profile extends Component
{
 public ?User $user;
    public array $information = [];
    public array $domicilio = [];
    public ?int $departamento_id = 7;

    /**
     * Mount the component.
     */
    public function mount(): void {
        $this->user = Auth::user();
        $this->information = $this->user->information->toArray();
        $this->domicilio = $this->user->information->domicilio->toArray();
    }

    public function render() {
        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios = Municipio::where('departamento_id',$this->departamento_id)->orderBy('nombre')->get();
        $zonas = Zona::all();

        return view('livewire.settings.profile',compact('departamentos','municipios','zonas'));
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void {

        $validated = $this->validate([
            'information.nombres' => 'required|string|max:255',
            'information.apellidos' => 'required|string|max:255',
            'information.cui' => 'required|digits:13|unique:user_information,cui,'.$this->user->information->id,
            'information.telefono' => 'nullable|max:9',
            'information.fecha_nacimiento' => 'required|date',
            'information.correo' => 'required|string|lowercase|email|max:255',
            'information.sexo' => 'required|in:F,M',
            'domicilio.municipio_id' => 'required|exists:municipios,id',
            'domicilio.zona_id' => 'nullable|exists:zonas,id',
            'domicilio.colonia' => 'nullable|string|max:255',
            'domicilio.direccion' => 'required|string|max:255',
        ]);
        

        $this->user->fill($validated);

        if ($this->user->isDirty('email')) {
            $this->user->email_verified_at = null;
        }


        $this->user->information->nombres = $this->information['nombres'];
        $this->user->information->apellidos = $this->information['apellidos'];
        $this->user->information->cui = $this->information['cui'];
        $this->user->information->telefono = $this->information['telefono'];
        $this->user->information->fecha_nacimiento = $this->information['fecha_nacimiento'];
        $this->user->information->correo = $this->information['correo'];
        $this->user->information->sexo = $this->information['sexo'];
        $this->user->information->save();

        $this->user->information->domicilio->municipio_id = $this->domicilio['municipio_id'];
        $this->user->information->domicilio->zona_id = $this->domicilio['zona_id'] ?? null;
        $this->user->information->domicilio->colonia = $this->domicilio['colonia'] ?? null;
        $this->user->information->domicilio->direccion = $this->domicilio['direccion'];
        $this->user->information->domicilio->save();

        $this->dispatch('profile-updated', name: $this->user->information->nombre_completo);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}
