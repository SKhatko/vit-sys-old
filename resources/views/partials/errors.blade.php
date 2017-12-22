<!-- TODO styling of error -->
@if ($errors->any())

    @foreach ($errors->all() as $error)
        <div class="alert">
            {{ $error }}
        </div>
    @endforeach

@elseif (session()->has('flash_message'))
    <!-- TODO change design -->

    <div class="alert {!! session()->has('flash_message_type') ? session()->get('flash_message_type') : '' !!}
    {!! session()->has('flash_message_important') ? 'alert-important' : '' !!}" style="margin-top:20px;">

        @if (session()->has('flash_message_important'))
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        @endif

        {!! session()->get('flash_message') !!}
    </div>
@endif