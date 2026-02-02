<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;

use App\Models\GlobalSetting;
use Livewire\Attributes\Layout;

class Settings extends Component
{
    public $appName;
    public $supportEmail;
    public $maintenanceMode = false;
    public $footerText;

    protected $rules = [
        'appName' => 'required|string|max:50',
        'supportEmail' => 'required|email',
        'footerText' => 'nullable|string|max:255',
        'maintenanceMode' => 'boolean',
    ];

    public function mount()
    {
        $this->appName = GlobalSetting::value('app_name', 'LaraCarte');
        $this->supportEmail = GlobalSetting::value('support_email', 'support@laracarte.com');
        $this->maintenanceMode = (bool) GlobalSetting::value('maintenance_mode', false);
        $this->footerText = GlobalSetting::value('footer_text', '© 2026 LaraCarte Platform');
    }

    public function save()
    {
        $this->validate();

        GlobalSetting::set('app_name', $this->appName, 'string', 'Nama Platform');
        GlobalSetting::set('support_email', $this->supportEmail, 'string', 'Email Dukungan');
        GlobalSetting::set('maintenance_mode', $this->maintenanceMode, 'boolean', 'Mode Pemeliharaan');
        GlobalSetting::set('footer_text', $this->footerText, 'string', 'Teks Footer');

        session()->flash('message', 'Pengaturan platform berhasil diperbarui.');
    }

    #[Layout('components.admin-layout')]
    public function render()
    {
        return view('livewire.super-admin.settings')
            ->layout('components.admin-layout', ['header' => 'Pengaturan Platform']);
    }
}
