@props(['size' => 'medium', 'class' => '', 'showText' => true])

@php
    $sizeClasses = [
        'small' => 'max-h-12 max-w-24',
        'medium' => 'max-h-20 max-w-48',
        'large' => 'max-h-32 max-w-64',
        'xlarge' => 'max-h-40 max-w-80'
    ];
    
    $logoClass = $sizeClasses[$size] ?? $sizeClasses['medium'];
@endphp

<div class="logo-container {{ $class }}" style="display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
    <img src="{{ asset('images/medicare-plus.png') }}" 
         alt="MediCare+" 
         class="platform-logo {{ $logoClass }}"
         style="height: auto; width: auto; filter: brightness(1.1) contrast(1.1); transition: all 0.3s ease;">
    
    @if($showText)
    <div class="logo-text" style="display: flex; flex-direction: column; align-items: flex-start;">
        <h2 class="platform-name" style="margin: 0; font-weight: 700; color: inherit; font-size: {{ $size === 'small' ? '1.25rem' : ($size === 'large' ? '2rem' : '1.5rem') }};">
            MediCare<span style="color: #059669;">+</span>
        </h2>
        <p class="platform-tagline" style="margin: 0; font-size: {{ $size === 'small' ? '0.75rem' : '0.875rem' }}; opacity: 0.8; color: inherit;">
            Votre santé, notre priorité
        </p>
    </div>
    @endif
</div>

<style>
    .platform-logo:hover {
        transform: scale(1.05);
        filter: brightness(1.2) contrast(1.2);
    }
    
    .logo-container:hover .platform-name {
        color: #059669 !important;
        transition: color 0.3s ease;
    }
    
    @media (max-width: 768px) {
        .logo-container {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .logo-text {
            text-align: center !important;
            align-items: center !important;
        }
    }
</style>
