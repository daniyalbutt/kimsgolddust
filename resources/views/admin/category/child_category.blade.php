<li>{{ $child_category->name }}</li>
@php
array_push($array, ['name' => $child_category->name, 'id' => $child_category->id]);
@endphp
@if ($child_category->categories)
    <ul>
        @foreach ($child_category->categories as $childCategory)
            @include('admin.category.child_category', ['child_category' => $childCategory])
        @endforeach
    </ul>
@endif

@php
dump($array);
@endphp

	@for($i = 0; $i < count($array); $i++)
	<tr>
		<td>
		@for($j = 0; $j <= count($array[$i]); $j++)
		{{ $array[$j]['name'] }}
		@endfor
		</td>
	</tr>
	@endfor