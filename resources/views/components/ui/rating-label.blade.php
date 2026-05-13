@props([
    'rating' => 0,
])

@php
    $r = floatval($rating);
    if ($r >= 4.5) { $label = 'Excellent'; $variant = 'success'; }
    elseif ($r >= 3.5) { $label = 'Very Good'; $variant = 'primary'; }
    elseif ($r >= 2.5) { $label = 'Good'; $variant = 'warning'; }
    elseif ($r >= 1.5) { $label = 'Fair'; $variant = 'secondary'; }
    else { $label = 'Poor'; $variant = 'danger'; }
@endphp

<x-ui.badge :variant="$variant">{{ $label }}</x-ui.badge>
