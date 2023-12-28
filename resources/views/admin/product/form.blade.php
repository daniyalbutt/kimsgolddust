<div class="form-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('slug', 'Slug') !!}
                {!! Form::text('slug', null, '' == '' ? ['class' => 'form-control', '' => ''] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::Label('item_id', 'Select Category:') !!}
                @php
                    $cat_array = [];
                    foreach ($product->category_list as $key => $value) {
                        array_push($cat_array, $value->id);
                    }
                @endphp
                <select name="item_id[]" id="item_id" class="form-control select2" multiple>
                    @foreach ($items as $key => $value)
                        <option value="{{ $value->id }}" {{ in_array($value->id, $cat_array) ? 'selected' : '' }}>
                            {{ $value->getparent() }} {{ $value->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('sku', 'SKU') !!}
                {!! Form::text('sku', null, '' == '' ? ['class' => 'form-control', '' => ''] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('product_title', 'Product Title') !!}
                {!! Form::text(
                    'product_title',
                    null,
                    'required' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control'],
                ) !!}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('price', 'Sale Price') !!}
                {!! Form::text('price', null, '' == '' ? ['class' => 'form-control', '' => ''] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('regular_price', 'Regular Price') !!}
                {!! Form::text(
                    'regular_price',
                    null,
                    '' == '' ? ['class' => 'form-control', '' => ''] : ['class' => 'form-control'],
                ) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('stock', 'Stock') !!}
                {!! Form::number('stock', null, '' == '' ? ['class' => 'form-control', '' => ''] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('short_description', 'Description') !!}
                {!! Form::textarea(
                    'short_description',
                    null,
                    'required' == 'required'
                        ? ['class' => 'form-control', 'id' => 'summary-ckeditor', 'required' => 'required']
                        : ['class' => 'form-control'],
                ) !!}
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::Label('item_id', 'Select Related Product:') !!}
                @php
                    $prd_array = [];
                    $productRel = json_decode($product->theseTogether);
                    foreach ($productRel as $key => $value) {
                        
                        array_push($prd_array, $value);
                    }                    
                   
                @endphp
                <select name="theseTogether[]" id="" class="form-control select2" multiple>
                    @foreach ($relatedPrd as $key => $value)
                        <option value="{{ $value->id }}" {{ in_array($value->id, $prd_array) ? 'selected' : '' }}>{{ $value->product_title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" {{ ($product->discount_buy_qty > 0 &&  $product->discount_get_qty > 0) ?'checked' : '' }} value="" id="discount">
                    <label class="form-check-label" for="discount">
                        Add Discount
                    </label>
                </div>
            </div>
        </div>
        <div id="discountdiv" class="col-md-12" style="{{ ($product->discount_buy_qty > 0 &&  $product->discount_get_qty > 0) ?'' : 'display: none' }}">
            <div class="row">
                <div class="col-md-6">
                    <label for="discount_get_qty">Get Quantity</label>
                    <input type="number" class="form-control" id="discount_get_qty" name="discount_get_qty"
                        value="{{ $product->discount_get_qty }}">
                </div>
                <div class="col-md-6">
                    <label for="discount_buy_qty">Buy Quantity</label>
                    <input type="number" class="form-control" name="discount_buy_qty" id="discount_buy_qty"
                        value="{{ $product->discount_buy_qty }}">
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('tags', 'Tags') !!}
                {!! Form::text('tags', null, '' == '' ? ['class' => 'form-control', '' => ''] : ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('image', 'Image') !!}
                <input class="form-control dropify" name="image" type="file" id="image"
                    {{ $product->image != '' ? 'data-default-file =' . asset($product->image) : '' }}
                    {{ $product->image == '' ? 'required' : '' }} value="{{ $product->image }}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('additional_image', 'Gallary Image') !!}
                <div class="gallery Images">
                    @foreach ($product_images as $product_image)
                        <div class="image-single">
                            <img src="{{ asset($product_image->image) }}" alt="" id="image_id">
                            <button type="button" class="btn btn-danger" data-repeater-delete=""
                                onclick="getInputValue({{ $product_image->id }}, this);"> <i
                                    class="ft-x"></i>Delete</button>
                        </div>
                    @endforeach
                </div>
                <input class="form-control dropify" name="images[]" type="file" id="images"
                    {{ $product->additional_image != '' ? 'data-default-file =' . asset($product->additional_image) : '' }}
                    value="{{ $product->additional_image }}" multiple>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="is_featured_home">Is Featured (Home)</label>
                <select name="is_featured_home" id="is_featured_home" class="form-control">
                    <option value="0" {{ $product->is_featured_home == 0 ? 'selected' : ' ' }}>NO</option>
                    <option value="1" {{ $product->is_featured_home == 1 ? 'selected' : ' ' }}>YES</option>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="is_featured_menu">Is Featured (Menu)</label>
                <select name="is_featured_menu" id="is_featured_menu" class="form-control">
                    <option value="0">NO</option>
                    @foreach ($main_cat as $value)
                        <option value="{{ $value->id }}"
                            {{ $product->is_featured_menu == $value->id ? 'selected' : ' ' }}>{{ $value->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('best_seller', 'Best Seller') !!}
                {!! Form::text(
                    'best_seller',
                    null,
                    '' == '' ? ['class' => 'form-control', '' => ''] : ['class' => 'form-control'],
                ) !!}
            </div>
        </div>
        <!--<div class="col-md-12">-->
        <!--    <div class="form-group">-->
        <!--        {!! Form::label('new_product', 'Mark as New Product?') !!}-->
        <!--        <select name="new_product" id="new_product" class="form-control">-->
        <!--            <option value="0" {{ $product->new_product == 0 ? 'selected' : '' }}>NO</option>-->
        <!--            <option value="1" {{ $product->new_product == 1 ? 'selected' : '' }}>YES</option>-->
        <!--        </select>-->
        <!--    </div>-->
        <!--</div>-->
        <div class="col-md-12">
            <h4 class="card-title" id="repeat-form">Add Variation</h4>
        </div>
        <div class="col-md-12">
            <hr>
            <hr>
        </div>
        <div class="col-md-12 attributes-old">
            <div data-repeater-list="" class="drag">
        @foreach ($product->attributes as $key => $pro_att_edits)
                <div data-repeater-item="" class="row" data-id="{{$pro_att_edits->id}}" data-key="{{$key}}" data-order="{{$pro_att_edits->order_id}}"> 
                    <input type="hidden" value="{{ $pro_att_edits->id }}" name="product_attribute[]">
                    <div class="col-md-3">
                        <label for="image-{{ $pro_att_edits->id }}">Image</label>
                        <input class="form-control dropify" name="oldimage[]" type="file" id="image-{{ $pro_att_edits->id }}"
                                {{ $pro_att_edits->image != '' ? 'data-default-file =' . asset($pro_att_edits->image) : '' }}
                                {{ $pro_att_edits->image == '' ? '' : '' }} value="{{ $pro_att_edits->image }}">
                    </div>
                    <div class="col-md-9">
                            <div class="row">
                                <div class="form-group mb-1 col-sm-12 col-md-5">
                                    <label for="email-addr">Attribute</label>
                                    <br>
                                    <select class="form-control" id="attribute_id" name="attribute_id[]"
                                        onchange="getval(this)" disabled>
                                        <option value="{{ $pro_att_edits->attribute_id }}">
                                            {{ $pro_att_edits->attribute->name }}</option>
                                    </select>
                                </div>
                                <div class="form-group mb-1 col-sm-12 col-md-4">
                                    <label for="pass">value</label>
                                    <br>
                                    <select class="form-control value" id="value" name="value[]" disabled>
                                        <option value="{{ $pro_att_edits->value }}">{{ $pro_att_edits->value }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group mb-1 col-sm-12 col-md-3">
                                    <label for="bio" class="cursor-pointer">qty</label>
                                    <br>
                                    <input type="number" name="qty[]" class="form-control" id="qty"
                                        value="{{ $pro_att_edits->qty }}">
                                </div>
                                <div class="form-group mb-1 col-sm-12 col-md-4">
                                    <label for="bio" class="cursor-pointer">Sale Price</label>
                                    <br>
                                    <input type="number" name="v_price[]" class="form-control" id="price"
                                        value="{{ $pro_att_edits->price }}">
                                </div>
                                <div class="form-group mb-1 col-sm-12 col-md-4">
                                    <label for="regular_price" class="cursor-pointer">Regular Price</label>
                                    <br>
                                    <input type="number" name="v_regular_price[]" class="form-control"
                                        id="regular_price" value="{{ $pro_att_edits->regular_price }}">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 text-center mt-2">
                                    <button onclick="deleteAttr({{ $pro_att_edits->id }}, this)" type="button"
                                        class="btn-block btn btn-danger" data-repeater-delete=""
                                        style="margin-top: 6px;"> <i class="ft-x"></i>
                                        Delete</button>
                                </div>
                            </div>
                        </div>
                    <div class="col-md-12">
                        <hr>
                        <hr>
                    </div>
                </div>
        @endforeach
            </div>
        </div>

        <div class="repeater-default col-md-12 attributes-old">
            <div data-repeater-list="attribute" class="drag">
                <div data-repeater-item="" class="row">
                    <div class="col-md-3">
                        <label for="v-image">Image</label>
                        <input class="form-control dropify" name="v-image" type="file" id="v-image">
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="form-group mb-1 col-sm-12 col-md-5">
                                <label for="email-addr">Attribute</label>
                                <br>
                                <select class="form-control select2" id="attribute_id" name="attribute_id"
                                    onchange="getval(this)">
                                    @foreach ($att as $atts)
                                        <option value="{{ $atts->id }}">{{ $atts->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-1 col-sm-12 col-md-4">
                                <label for="pass">value</label>
                                <br>
                                <select class="form-control value" id="value" name="value">

                                </select>
                            </div>
                            <div class="form-group mb-1 col-sm-12 col-md-3">
                                <label for="bio" class="cursor-pointer">qty</label>
                                <br>
                                <input type="number" name="qty" class="form-control" id="qty">
                            </div>
                            <div class="form-group mb-1 col-sm-12 col-md-4">
                                <label for="bio" class="cursor-pointer">Sale Price</label>
                                <br>
                                <input type="number" name="v-price" class="form-control" id="price">
                            </div>
                            <div class="form-group mb-1 col-sm-12 col-md-4">
                                <label for="regular_price" class="cursor-pointer">Regular Price</label>
                                <br>
                                <input type="number" name="v-regular-price" class="form-control"
                                    id="regular_price">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 text-center mt-2">
                                <button type="button" class="btn-block btn btn-danger" data-repeater-delete=""
                                    style="margin-top: 6px;"> <i class="ft-x"></i>
                                    Delete</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                        <hr>
                    </div>
                </div>
            </div>
            <div class="form-group overflow-hidden">
                <div class="">
                    <button type="button" data-repeater-create="" class="btn btn-primary">
                        <i class="ft-plus"></i> Add
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('seo_title', 'Seo Title') !!}
                {!! Form::text('seo_title', null, ('' == '') ? ['class' => 'form-control','id' => 'seo_title','maxlength'=>'60'] : ['class' => 'form-control','id' => 'seo_title','maxlength'=>'60']) !!}
                <p class="text-danger">Maximum Characters Recomended 60 <b><span id="character-count-title">{{strlen($product->seo_title)}}</span> characters</b>.</p>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('seo_description', 'Seo Description') !!}
                {!! Form::text('seo_description', null, ('' == '') ? ['class' => 'form-control','id' => 'seo_desc','maxlength'=>'160'] : ['class' => 'form-control','id' => 'seo_desc','maxlength'=>'160']) !!}
                <p class="text-danger">Maximum Characters Recomended 160 <b><span id="character-count-desc">{{strlen($product->seo_description)}}</span> characters</b>.</p>
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

