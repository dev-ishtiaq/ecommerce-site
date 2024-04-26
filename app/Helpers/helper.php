<?php
use App\Models\Category;
function getCategoris(){
    return Category::orderBy('name', 'ASC')
    ->with('sub_category')
    ->where('showHome','Yes')
    ->get();
}
?>
