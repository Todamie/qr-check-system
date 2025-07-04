@props(['success', 'error'])

@if ($success)
    <div style="background-color: #f0fdf4; border: 1px solid #4ade80; color: #166534; padding: 16px; border-radius: 6px; margin-bottom: 16px;">
        {{ $success }}
    </div>
@endif

@if ($error)
    <div style="background-color: #fee2e2; border: 1px solid #f87171; color: #991b1b; padding: 16px; border-radius: 6px; margin-bottom: 16px;">
        {{ $error }}
    </div>
@endif
