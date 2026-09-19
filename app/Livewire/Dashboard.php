<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'roles' => Role::count(),
            'permissions' => Permission::count(),
        ];

        // نمو المستخدمين خلال آخر 6 أشهر (بدون دوال SQL خاصة بمحرك معيّن، عشان يشتغل على MySQL وSQLite بنفس الطريقة)
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $createdDates = User::where('created_at', '>=', $sixMonthsAgo)->pluck('created_at');

        $growth = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $label = $month->translatedFormat('M');
            $count = $createdDates->filter(fn ($date) => $date->format('Y-m') === $month->format('Y-m'))->count();
            $growth->put($label, $count);
        }

        // توزيع المستخدمين حسب الدور
        $roleDistribution = Role::withCount('users')->get()->pluck('users_count', 'name');

        return view('livewire.dashboard', compact('stats', 'growth', 'roleDistribution'));
    }
}
