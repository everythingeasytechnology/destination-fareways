@if(!empty($callSettings) && $callSettings->status)
    @if($callSettings->toggle_mobile_floating)
        <!-- Fixed bottom-right gold pulsing button — mobile only -->
        <a href="tel:{{ $callSettings->phone }}" class="floating-call-btn" style="background-color: {{ $callSettings->button_color ?? '#F59E0B' }}; color: {{ $callSettings->text_color ?? '#07111F' }} !important;">
            <i class="fa-solid fa-phone"></i> {{ $callSettings->button_text ?? 'Call Now' }}
        </a>
    @endif

    @if($callSettings->toggle_whatsapp && !empty($callSettings->whatsapp))
        <!-- WhatsApp button above call button -->
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $callSettings->whatsapp) }}" class="floating-whatsapp-btn" target="_blank">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
    @endif
@else
    <!-- Default fallback floating buttons -->
    <a href="tel:+18005550199" class="floating-call-btn">
        <i class="fa-solid fa-phone"></i> Call Now
    </a>
    <a href="https://wa.me/18005550199" class="floating-whatsapp-btn" target="_blank">
        <i class="fa-brands fa-whatsapp"></i>
    </a>
@endif
