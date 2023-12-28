<div class="form-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('zone_name', 'Zone Name') !!}
                {!! Form::text(
                    'zone_name',
                    null,
                    'required' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control'],
                ) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('region', 'Region') !!}
                @php
                $region = json_decode($shipping->region);
                @endphp
                <select class="form-control select2" name="region[]" reqired multiple>
                    @foreach($countries as $key => $value)
                        <option value="{{$value->name}}" {{ (in_array($value->name, $region) ? 'selected' : '') }}>{{$value->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('shipping_method', 'Shipping Method') !!}
                {!! Form::text(
                    'shipping_method',
                    null,
                    '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control'],
                ) !!}
            </div>
        </div>
        @foreach($shipping->taxesZone as $tax_edits)
        <div class="col-md-12">
            <div data-repeater-list="attribute">
                <div data-repeater-item="" class="row">
                    <input type="hidden" value="{{ $tax_edits->id}}" name="tax_zone_id[]">
                        <div class="form-group mb-1 col-sm-12 col-md-2">
                            <label for="condition">Condition</label>
                            <br>
                            <select class="form-control" id="attribute_id" name="condition[]" onchange="getval(this)" disabled>
                            <option value="price">Price</option>
                            </select>
                        </div>
                        <div class="form-group mb-1 col-sm-12 col-md-3">
                            <label for="Min">Min</label>
                            <br>
                            <input type="number" name="min[]" step="0.01" value="{{ $tax_edits->min }}" class="form-control" id="qty" >

                        </div>
                        <div class="form-group mb-1 col-sm-12 col-md-3">
                            <label for="Max" class="cursor-pointer">Max</label>
                            <br>
                            <input type="number" name="max[]" step="0.01" class="form-control"  value="{{$tax_edits->max}}">
                        </div>
                        <div class="form-group mb-1 col-sm-12 col-md-2">
                            <label for="Row_Cost" class="cursor-pointer">Row Cost</label>
                            <br>
                            <input type="number" name="row_cost[]" class="form-control"  value="{{ $tax_edits->row_cost }}">
                        </div>
                        <div class="form-group col-sm-12 col-md-2 text-center mt-2">
                            <button onclick="deleteAttr({{ $tax_edits->id }}, this)" type="button" class="btn btn-danger" data-repeater-delete=""> <i class="ft-x"></i>
                                Delete</button>
                        </div>

                    <hr>
                </div>
            </div>
        </div>
        @endforeach
        <div class="repeater-default col-md-12">
            <div data-repeater-list="taxes">
                <div data-repeater-item="" class="row">

                        <div class="form-group mb-1 col-sm-12 col-md-2">
                            <label for="email-addr">Condition</label>
                            <br>
                            <select class="form-control" id="attribute_id" name="condition" onchange="getval(this)">

                                <option value="price">Price</option>

                            </select>
                        </div>
                        <div class="form-group mb-1 col-sm-12 col-md-3">
                            <label for="pass">Min</label>
                            <br>
                            <input type="number" name="min" step="0.01" class="form-control"  >
                        </div>
                        <div class="form-group mb-1 col-sm-12 col-md-3">
                            <label for="bio" class="cursor-pointer">Max</label>
                            <br>
                            <input type="number" name="max" step="0.01" class="form-control" >
                        </div>
                        <div class="form-group mb-1 col-sm-12 col-md-2">
                            <label for="bio" class="cursor-pointer">Row Cost</label>
                            <br>
                            <input type="number" name="row_cost" class="form-control"  >
                        </div>
                        <div class="form-group col-sm-12 col-md-2 text-center mt-2">
                            <button type="button" class="btn btn-danger" data-repeater-delete=""> <i class="ft-x"></i>
                                Delete</button>
                        </div>

                    <hr>
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

    </div>
</div>
<div class="form-actions text-right pb-0">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
