<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping, WithCustomCsvSettings
{
    public function __construct(
        protected ?string $search = null,
        protected ?string $role = null,
        protected ?string $status = null,
    ) {}

    public function query(): Builder
    {
        return User::query()
            ->with('roles')
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->role, fn ($q) => $q->whereHas('roles', fn ($q2) => $q2->where('name', $this->role)))
            ->when($this->status !== null && $this->status !== '', fn ($q) => $q->where('is_active', $this->status === 'active'))
            ->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            __('Name'), __('Email'), __('Roles'), __('Status'), __('Created at'),
        ];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->roles->pluck('name')->implode(', '),
            $user->is_active ? __('Active') : __('Inactive'),
            $user->created_at->format('Y-m-d'),
        ];
    }

    // مهم لعرض الحروف العربية صح لما يفتح الملف بإكسل
    public function getCsvSettings(): array
    {
        return ['use_bom' => true];
    }
}
