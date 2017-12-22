<!-- Delete photo dialog -->
@include('partials.modal_top', ['modalId' => 'delete-photo-dialog'])
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>{{ trans('menu.delete_photo') }}</h3>
</div>

<div class="modal-body">

    {!! Form::open(['method' => 'post', 'action' => 'OnlineMenuController@deleteUploadedPhoto', 'id' => 'delete-photo-form']) !!}

    <input type="hidden" name="photo" id="delete-photo-input"/>

    <p>
        {{ trans('menu.delete_photo_confirmation_msg') }}
    </p>

    <div class="clear"></div>
</div>

<div class="modal-footer">
    {!! Form::submit(trans('general.confirm'), ['class' => 'btn btn-danger', 'id' => 'delete-photo-confirm-btn']) !!}

    <a href="javascript:;" class="btn" data-dismiss="modal">{{ trans('general.cancel') }}</a>

    {!! Form::close() !!}
</div>

@include('partials.modal_bottom')


<!-- Photo Album Dialog -->
<div class="modal" id="photo-album-dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <a class="close" data-dismiss="modal">×</a>
                <h3>{{ trans('menu.photo_album') }}</h3>
            </div>

            <div class="modal-body">

                <div class="col-md-12">
                    <div class="photo-categories-holder">
                        <ul class="photo-categories-nav">
                            <?php
                            $photoCategories = \App\Misc::getMenuAlbumCategories();
                            ?>
                            @foreach ($photoCategories as $category)
                                <li><a href="javascript:;" onclick="loadCategoryPhotos($(this), '{{ $category }}');"
                                       data-category="{{ $category }}">{{ trans('menu.'.$category) }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-md-12">
                    <hr style="margin:2px;">
                </div>

                <div class="col-md-12 pnone">
                    <div class="photos-section">

                        <div id="photos-loader" style="display:none;" class="loader calign"><img
                                    src="{{ asset('img/ajax-loader.gif') }}" alt="{{ trans('general.loading') }}..."
                                    style="margin-top:30px;"/></div>

                        <div id="photos-holder">

                        </div>

                        <div class="clear"></div>
                    </div>
                </div>

                <div class="clear"></div>
            </div>

@include('partials.modal_bottom')