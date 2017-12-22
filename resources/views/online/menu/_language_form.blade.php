@if (count($menuLanguages) > 1)
    <div id="language-select">
        {!! Form::open(['method' => 'post', 'action' => 'Online\OnlineController@setMenuLanguage']) !!}
        <form action="server-side-script.php">
            <select id="language-options" name="language">
                @foreach ($menuLanguages as $thisLanguage)
                    <?php
                    $lang = $thisLanguage->language;
                    ?>
                    <option value="{{ $lang }}" title="{{ action('Online\OnlineController@setMenuLanguage', [$lang]) }}"
                            {!! ($lang == $menuLanguage) ? 'selected="selected"' : '' !!}>{{ trans('languages.'.$lang) }}</option>
                @endforeach
            </select>

            <input value="Select" type="submit"/>
        {!! Form::close() !!}
    </div>
@endif