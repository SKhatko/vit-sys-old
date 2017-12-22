@if (session()->has('flash_message'))
    <div class="alert mtop {!! session()->has('flash_message_type') ? session()->get('flash_message_type') : '' !!}
    {!! session()->has('flash_message_important') ? 'alert-important' : '' !!}">

        @if (session()->has('flash_message_important'))
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        @endif

        {!! session()->get('flash_message') !!}

    </div>
@endif

<?php
session()->forget('flash_message');
session()->forget('flash_message_type');
session()->forget('flash_message_important');
?>