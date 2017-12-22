@extends('admin.index')

@section('content')

    <div class="admin-languages">

        {{ Form::open(['method' => 'POST', 'action' => 'ConfigController@postOnlineLanguages']) }}

        <div class="admin-languages__header">
            <div class="admin-languages__header-default">
                @if (count($menuLanguagesList))
                    {!! Form::label('default_language', trans('admin.default_language'), ['class' => 'label']) !!}
                    {!! Form::select('default_language', $menuLanguagesList, $defaultLanguage, ['class' => 'select']) !!}
                @endif
            </div>

            {{ Form::button('Save', ['type'=>'submit', 'class' => 'admin-languages__header--submit']) }}

        </div>

        @if (count($menuLanguages))
            <div class="admin-languages__content">
                @foreach ($menuLanguages as $menuLanguage)

                    <div class="admin-languages__content-language">
                        {{ Form::label($menuLanguage->language, trans('languages.'.$menuLanguage->language), ['class' => 'label']) }}
                        {{ Form::select('published[' . $menuLanguage->language . ']', $publishStatuses, $menuLanguage->published, ['class' => 'select']) }}
                    </div>

                @endforeach
            </div>
        @endif

        {{ Form::close() }}

    </div>

@stop

@section('script')
    <script>
        new Admin()
    </script>
@stop

