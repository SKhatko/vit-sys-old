@if (session()->has('flash_message'))
    <div class="alert js-alert in">
        <div class="alert-dialog">
            <div class="alert-content">

                <div class="alrt {!! session()->get('flash_message_type') ?? '' !!}
                {!! session()->has('flash_message_important') ? 'alert-important' : '' !!}">

                    @if (session()->has('flash_message_important'))
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    @endif

                    {!! session()->get('flash_message') !!}
                </div>

            </div>
        </div>
    </div>
@elseif ($errors->any())

    <div class="alert js-alert in">
        <div class="alert-dialog">
            <div class="alert-content">

                @foreach ($errors->all() as $error)
                        {{ $error }}
                @endforeach

            </div>
        </div>
    </div>
@endif

