{{-- ═══════════ LOADING SCREEN (All Devices, First Visit) ═══════════ --}}
<div id="loading-screen" role="status" aria-live="polite">
    <div class="loading-lottie-wrap">
        <dotlottie-player
            id="lottiePlayer"
            src="{{ asset('assets/loading.lottie') }}"
            autoplay
            loop
            speed="1.7"
            direction="1"
            mode="bounce"
        ></dotlottie-player>
    </div>
</div>
