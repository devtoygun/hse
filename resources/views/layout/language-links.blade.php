<div class="position-absolute top-0 end-0 m-3 small" style="z-index: 1050;">
    <a href="{{ route('locale.switch', 'tr') }}" class="text-decoration-none {{ app()->getLocale() === 'tr' ? 'fw-semibold' : 'text-muted' }}">TR</a>
    <span class="mx-1 text-muted">|</span>
    <a href="{{ route('locale.switch', 'en') }}" class="text-decoration-none {{ app()->getLocale() === 'en' ? 'fw-semibold' : 'text-muted' }}">EN</a>
</div>

