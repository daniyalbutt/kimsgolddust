<div class="form-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('name', 'Name') !!}
                {!! Form::text('name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('comments', 'Comments') !!}
                {!! Form::textarea('comments', null, ('required' == 'required') ? ['class' => 'form-control', 'id' => 'summary-ckeditor', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('image', 'Image') !!}
                <input class="form-control dropify" name="image" type="file" id="image" {{ ($testimonial->image != '') ? "data-default-file = ".asset($testimonial->image) : ''}} {{ ($testimonial->image == '') ? "required" : ''}} value="{{$testimonial->image}}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('stars', 'Stars') !!}
                {!! Form::number('stars', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('show_on_home', 'Show On Home') !!}
                <select name="show_on_home" id="show_on_home" class="form-control">
                    <option value="0" {{ $testimonial->show_on_home == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ $testimonial->show_on_home == 1 ? 'selected' : '' }}>Yes</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="form-actions text-right pb-0">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
