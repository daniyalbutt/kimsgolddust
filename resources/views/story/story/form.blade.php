<div class="form-body">
    <div class="row">
    	<div class="col-md-12">
            <div class="form-group">
            	{!! Form::label('left_text', 'Left Text') !!}
            	{!! Form::text('left_text', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
            	{!! Form::label('right_text', 'Right Text') !!}
            	{!! Form::text('right_text', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
		<div class="col-md-12">
    		<div class="form-group">
    			{!! Form::label('image', 'Image') !!}
    			<input class="form-control dropify" name="image" type="file" id="image" {{ ($story->image != '') ? "data-default-file = ".asset($story->image) : ''}} {{ ($story->image == '') ? "required" : ''}} value="{{$story->image}}">
    		</div>
		</div>
	</div>
</div>
<div class="form-actions text-right pb-0">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
