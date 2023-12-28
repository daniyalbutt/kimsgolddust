<div class="form-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('page_name', 'Page Name') !!}
                {!! Form::text('page_name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
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
                <label for="banner_color">Banner Color ( old color: linear-gradient(0deg, rgba(2,10,18,1) 0%, rgba(17,96,181,1) 100%))</label>
                <input type="text" class="form-control" name="banner_color" id="banner_color" value="{{ $page->banner_color }}">
                <div style="background:{{$page->banner_color}};height: 40px;"></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('left_image', 'Banner Left Image') !!}
                <input class="form-control dropify" name="left_image" type="file" id="left_image" {{ ($page->left_image != '') ? "data-default-file = ".asset($page->left_image) : ''}} {{ ($page->left_image == '') ? "" : ''}} value="{{$page->left_image}}">
                <div class="form-group mt-1 alert alert-info">
                    <label style="color: white;">Add Alter Text of Upper Image</label>
                    <input type="text" name="left_image_alter_tag" class="form-control" value="{{ $page->left_image_alter_tag }}">
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('right_image', 'Banner Right Image') !!}
                <input class="form-control dropify" name="right_image" type="file" id="right_image" {{ ($page->right_image != '') ? "data-default-file = ".asset($page->right_image) : ''}} {{ ($page->right_image == '') ? "" : ''}} value="{{$page->right_image}}">
                <div class="form-group mt-1 alert alert-info">
                    <label style="color: white;">Add Alter Text of Upper Image</label>
                    <input type="text" name="right_image_alter_tag" class="form-control" value="{{ $page->right_image_alter_tag }}">
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('content', 'Content') !!}
                {!! Form::textarea('content', null, ('required' == 'required') ? ['class' => 'form-control', 'id' => 'summary-ckeditor', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('image', 'Image') !!}
                <input class="form-control dropify" name="image" type="file" id="image" {{ ($page->image != '') ? "data-default-file = ".asset($page->image) : ''}} {{ ($page->image == '') ? "" : ''}} value="{{$page->image}}">
                <div class="form-group mt-1 alert alert-info">
                    <label style="color: white;">Add Alter Text of Upper Image</label>
                    <input type="text" name="image_alter_tag" class="form-control" value="{{ $page->image_alter_tag }}">
                </div>
            </div>
        </div>
        @foreach($page->sections as $section)
        <div class="col-md-12">
            <div class="form-group">
                <label>{{$section->label}}</label>
                @if($section->type == 'image')
                <input type="file" name="{{$section->slug}}" class="dropify" data-default-file="{{ asset($section->value) }}">
                <div class="form-group mt-1 alert alert-info">
                    <label style="color: white;">Add Alter Text of Upper Image</label>
                    <input type="text" name="{{$section->slug}}[alter_tag]" class="form-control" value="{{ $section->alter_tag }}">
                </div>
                <hr>
                @elseif($section->type == 'textarea')
                <textarea name="{{$section->slug}}" id="costom-summary-ckeditor-{{$section->id}}">{{$section->value}}</textarea>
                @push('js')

                <script>
                    if($('#costom-summary-ckeditor-{{$section->id}}').length != 0){
                        CKEDITOR.replace( 'costom-summary-ckeditor-{{$section->id}}' );
                    }
                </script>

                @endpush
                @elseif($section->type == 'video')
                <img alt="" class="img-responsive" id="banner1"
                src="{{ asset($section->value) }}" style=" width: 30%; ">
                <input type="file" name="{{$section->slug}}" class="dropify" {{ ($section->value != '') ? "data-default-file =". asset($section->value) : ''}} {{ ($section->value == '') ? "required" : ''}} value="{{$section->value}}">
                @else($section->type == 'text')
                <input type="text" name="{{$section->slug}}" value="{{$section->value}}" class="form-control">
                @endif
            </div>
        </div>
        @endforeach
        <div class="col-md-12">
            <hr>
            <hr>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('seo_title', 'Seo Title') !!}
                {!! Form::text('seo_title', null, ('required' == '') ? ['class' => 'form-control', 'id' => 'seo_title','maxlength'=>'60'] : ['class' => 'form-control', 'id' => 'seo_title','maxlength'=>'60']) !!}
                <p class="text-danger">Maximum Characters Recomended 60 <b><span id="character-count-title">{{strlen($page->seo_title)}}</span> characters</b>.</p>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('seo_description', 'Seo Description') !!}
                {!! Form::text('seo_description', null, ('required' == '') ? ['class' => 'form-control', 'id' => 'seo_desc','maxlength'=>'160'] : ['class' => 'form-control' ,'id' => 'seo_desc','maxlength'=>'160']) !!}
                <p class="text-danger">Maximum Characters Recomended 160 <b><span id="character-count-desc">{{strlen($page->seo_description)}}</span> characters</b>.</p>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('seo_keyword', 'Seo Keyword') !!}
                {!! Form::text('seo_keyword', null, ('required' == '') ? ['class' => 'form-control', '' => ''] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('og_title', 'OG Title') !!}
                {!! Form::text('og_title', null, ('required' == '') ? ['class' => 'form-control', '' => ''] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('og_description', 'OG Description') !!}
                {!! Form::text('og_description', null, ('required' == '') ? ['class' => 'form-control', '' => ''] : ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('additional_seo', 'Additional Seo Tags') !!}
                {!! Form::textarea('additional_seo', null, ('required' == '') ? ['class' => 'form-control', '' => ''] : ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
</div>


<div class="form-actions text-right pb-0">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
