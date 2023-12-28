<?php


	function generateCategories($categories, $name = null){
	    foreach ($categories as $category) {
	    	$edit = url('/admin/category/' . $category->id . '/edit');
	        echo '<li><div class="name">' . ($name != null ? $name . ' <i class="la la-angle-right"></i> ' : ' ') . $category->name . '</div><div class="left-wrapper"><a href="'.$edit.'" class="btn btn-sm btn-info">EDIT</a><a href="#" class="ml-1 btn btn-sm btn-danger">DELETE</a></div></li>';
	        if (count($category->children) > 0) {
	            echo '<ul>';
	                    generateCategories($category->children, $category->name);
	            echo '</ul>';
	        }
	    }
	}


?>