<div class="form-body">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group"> 
    			{!! Form::label('cat_id', 'Select Category') !!} 
                @php
                    
                @endphp
                <select name="cat_id" id="cat_id" class="form-control select2" >
                    @foreach ($items as $key => $value)
                        <option value="{{ $value->id }}" {{ ($value->id == $discount->cat_id) ? 'selected' : '' }}>
                            {{ $value->getparent() }} {{ $value->name }}</option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="col-md-12">
            <div class="form-group">
    	       {!! Form::label('type', 'Type') !!}
               <select name="type" id="type" class="form-control discountSelect">
                   <option value="0">Select Discount</option>
                   <option value="1" {{ ($discount->type == '1') ? 'selected' : '' }}>Fixed Price</option>
                   <option value="2" {{ ($discount->type == '2') ? 'selected' : '' }}>Percentage Price</option>
               </select>
            </div>
        </div>
		<div class="col-md-12">
			<div class="form-group percent" style="{{ ($discount->discount_price != '') ? 'display:block;' : 'display:none;' }}"> 
    			{!! Form::label('discount_price', 'Discount Percentage %') !!} 
    			{!! Form::text('discount_price', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!} 
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group fixed" style="{{ ($discount->fixed_price != '') ? 'display:block;' : 'display:none;' }}"> 
    			{!! Form::label('fixed_price', 'Fixed Price $') !!} 
    			{!! Form::text('fixed_price', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!} 
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group"> 
    			{!! Form::label('date_start', 'Start Date') !!} 
    			{!! Form::date('date_start', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!} 
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group"> 
    			{!! Form::label('date_range', 'Expiry Date') !!} 
    			{!! Form::date('date_range', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!} 
			</div>
		</div>

	</div>
</div>
<div class="form-actions text-right pb-0"> {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!} </div>