<div class="form-body">
    <div class="row">
		<div class="col-md-12">
    		<div class="form-group">
    			{!! Form::label('image', 'Image') !!}
    			<input class="form-control dropify" name="image" type="file" id="image" {{ ($firingprocessslider->image != '') ? "data-default-file = ".asset($firingprocessslider->image) : ''}} {{ ($firingprocessslider->image == '') ? "" : ''}} value="{{$firingprocessslider->image}}">
    		</div>
		</div>
		<div class="col-md-12">
    		<div class="form-group">
    		    {!! Form::label('title', 'Title') !!}
    		    {!! Form::text('title', null, 'required' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control'], ) !!}
		    </div>
	    </div>
	    <div class="col-md-12">
    		<div class="form-group">
    		    {!! Form::label('content', 'Content') !!}
    		    {!! Form::text('content', null, 'required' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control'], ) !!}
		    </div>
	    </div>
	</div>
</div>
<div class="form-actions text-right pb-0">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
