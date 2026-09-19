<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #16231F; }
    h1 { color: #0F4A43; font-size: 18px; margin-bottom: 4px; }
    p.meta { color: #64766F; font-size: 11px; margin-top: 0; margin-bottom: 16px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #0F4A43; color: #fff; text-align: right; padding: 8px; font-size: 11px; }
    td { padding: 7px 8px; border-bottom: 1px solid #E7E3DA; font-size: 11px; }
    tr:nth-child(even) { background: #FAF8F5; }
</style>
</head>
<body>
    <h1>{{ __('Users') }}</h1>
    <p class="meta">{{ now()->translatedFormat('Y-m-d H:i') }} — {{ __('Total') }}: {{ $users->count() }}</p>
    <table>
        <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Roles') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Created at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roles->pluck('name')->implode('، ') }}</td>
                    <td>{{ $user->is_active ? __('Active') : __('Inactive') }}</td>
                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
