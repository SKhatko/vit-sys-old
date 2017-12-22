<div class="error">
    <div class="error-block">
        <div class="error-block__title">
            {{ trans('restaurant.oops') }}
        </div>

        <div class="error-block__content">
            {{ $message }}
        </div>

        <a href="{{ $action }}" class="error-block__link">
            {{ $actionName }}
        </a>
    </div>
</div>