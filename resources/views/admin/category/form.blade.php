<div class="form-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('parent', 'Main Category') !!}
                <select name="parent" id="parent" class="form-control select2">
                    <option value="0">No Parent</option>
                    @foreach($data as $key => $value)
                    <option value="{{ $value->id }}" {{ ($value->id == $category->parent) ? 'selected' : '' }}>{{ $value->getparent() }} {{ $value->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
            	{!! Form::label('name', 'Name') !!}
            	{!! Form::text('name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('menu_image', 'Menu Image') !!}
                <input class="form-control dropify" name="menu_image" type="file" id="menu_image" {{ ($category->menu_image != '') ? "data-default-file = ".asset($category->menu_image) : ''}} {{ ($category->menu_image == '') ? "" : ''}} value="{{$category->menu_image}}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('show_on_home', 'Show On Home') !!}
                <select name="show_on_home" id="show_on_home" class="form-control">
                    <option value="0" {{ $category->show_on_home == 0 ? 'selected' : ' ' }}>NO</option>
                    <option value="1" {{ $category->show_on_home == 1 ? 'selected' : ' ' }}>YES</option>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('image', 'Home Page Image') !!}
                <input class="form-control dropify" name="image" type="file" id="image" {{ ($category->image != '') ? "data-default-file = ".asset($category->image) : ''}} {{ ($category->image == '') ? "" : ''}} value="{{$category->image}}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('slug', 'Slug') !!}
                {!! Form::text('slug', null, ('' == '') ? ['class' => 'form-control', '' => ''] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('seo_title', 'Seo Title') !!}
                {!! Form::text('seo_title', null, ('' == '') ? ['class' => 'form-control', 'id' => 'seo_title','maxlength'=>'60'] : ['class' => 'form-control', 'id' => 'seo_title','maxlength'=>'60']) !!}
                <p class="text-danger">Maximum Characters Recomended 60 <b><span id="character-count-title">{{strlen($category->seo_title)}}</span> characters</b>.</p>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('seo_description', 'Seo Description') !!}
                {!! Form::text('seo_description', null, ('' == '') ? ['class' => 'form-control','id' => 'seo_desc','maxlength'=>'160'] : ['class' => 'form-control'  ,'id' => 'seo_desc','maxlength'=>'160']) !!}
                <p class="text-danger">Maximum Characters Recomended 160 <b><span id="character-count-desc">{{strlen($category->seo_description)}}</span> characters</b>.</p>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('seo_keyword', 'Seo Keyword') !!}
                {!! Form::text('seo_keyword', null, ('' == '') ? ['class' => 'form-control', '' => ''] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('additional_seo', 'Additional Seo Tags') !!}
                {!! Form::textarea('additional_seo', null, ('' == '') ? ['class' => 'form-control', '' => ''] : ['class' => 'form-control']) !!}
            </div>
        </div>
   </div>
</div>
<div class="form-actions text-right pb-0">
	{!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
<div class="form-group row justify-content-center left_css col-md-12 {{ $errors->has('name') ? 'has-error' : ''}}">
    
    <div class="col-md-12">
        
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
