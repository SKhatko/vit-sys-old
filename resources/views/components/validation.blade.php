@if ($errors->any())

    @foreach ($errors->all() as $error)
        <div class="validation">
            {{ $error }}
        </div>
    @endforeach

@endif