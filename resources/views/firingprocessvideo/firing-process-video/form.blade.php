<div class="form-body">
    <div class="row">
		<div class="col-md-12">
    <div class="form-group">
    	{!! Form::label('link', 'Link') !!}
    	<textarea class="form-control" name="link" required rows="5">{{ $firingprocessvideo->link }}</textarea>
    </div>
</div>
	</div>
</div>
<div class="form-actions text-right pb-0">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
