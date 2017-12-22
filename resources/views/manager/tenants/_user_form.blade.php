<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="form-group">
        {!! Form::label('name', 'Name *') !!}
        {!! Form::text('name', NULL, ['class' => 'form-control', 'required']) !!}
    </div>
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="form-group">
        {!! Form::label('email', 'Email *') !!}
        {!! Form::email('email', NULL, ['class' => 'form-control', 'required']) !!}
    </div>
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="form-group">
        {!! Form::label('phone', 'Phone') !!}
        {!! Form::text('phone', NULL, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="clear"></div>

<div class="col-md-12">
    <div class="form-group">
        {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
    </div>
</div>
