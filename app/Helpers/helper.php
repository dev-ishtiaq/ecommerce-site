<?php
use App\Models\Category;
function getCategoris(){
    return Category::orderBy('name', 'ASC')
    ->with('sub_category')
    ->where('status',1)
    ->where('showHome','Yes')
    ->get();
}
?>
