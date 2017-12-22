<div class="back">
    <a href="{{ $action ?? url()->previous() }}" class="back__link">
        <svg class="back__link--icon">
            <use xlink:href="#icon-arrow-down"></use>
        </svg>
        <span class="back__link--text">
            {{ $text ?? 'Back' }}
        </span>
    </a>
</div>