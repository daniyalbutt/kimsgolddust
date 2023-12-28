<div class="form-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('code', 'Code (For Discount Purpose)') !!}
                {!! Form::text('code', null, ('' == '') ? ['class' => 'form-control', '' => ''] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('checkout_max_price', 'Checkout Maximum Price') !!}
                {!! Form::text('checkout_max_price', null, ('' == '') ? ['class' => 'form-control', '' => ''] : ['class' => 'form-control']) !!}
            </div>
        </div>
		<div class="col-md-12">
            <div class="form-group">
    	       {!! Form::label('type', 'Type') !!}
               <select name="type" id="type" class="form-control">
                   <option value="0">Fixed Price</option>
                   <option value="1">Percentage Price</option>
               </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
    	        {!! Form::label('price', 'Price') !!}
    	    	{!! Form::text('price', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('expire', 'Expire Date') !!}
                {!! Form::date('expire', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
	</div>
</div>
<div class="form-actions text-right pb-0">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
