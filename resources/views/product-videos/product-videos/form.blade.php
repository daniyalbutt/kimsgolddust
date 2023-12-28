<div class="form-body">
    <div class="row">
		<div class="col-md-12">
            <div class="form-group">
    	        {!! Form::label('name', 'Name') !!}
    	    	{!! Form::text('name', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
        	    {!! Form::label('video', 'Video') !!}
                @if($productvideo->video != null)
                <video width="100%" controls>
                    <source src="{{ asset($productvideo->video) }}" type="video/mp4">
                    <source src="{{ asset($productvideo->video) }}" type="video/ogg">
                    Your browser does not support the video tag.
                </video>
                @endif
                <input type="file" name="video" class="dropify" {{ ($productvideo->video != '') ? "data-default-file =". asset($productvideo->video) : ''}} {{ ($productvideo->video == '') ? "required" : ''}} value="{{$productvideo->video}}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('product_id', 'Product Name') !!}
                <select class="form-control select2" name="product_id">
                    <option>Select Product</option>
                    @foreach($pro as $key => $value)
                    <option value="{{ $value->id }}" {{ $productvideo->product_id == $value->id ? 'selected' : '' }}>{{ $value->product_title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('show_on_home', 'Show On Our Story Page') !!}
                <select class="form-control select2" name="show_on_home">
                    <option value="1" {{ $productvideo->show_on_home == 1 ? 'selected' : ' ' }}>YES</option>
                    <option value="0" {{ $productvideo->show_on_home == 0 ? 'selected' : ' ' }}>NO</option>
                </select>
            </div>
        </div>
	</div>
</div>
<div class="form-actions text-right pb-0">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
