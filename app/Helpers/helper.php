<?php
use App\Models\Category;
use App\Models\brand;
function getCategoris(){
    return Category::orderBy('name', 'ASC')
    ->with('sub_category')
    ->orderBy('id', 'DESC')
    ->where('status',1)
    ->where('showHome','Yes')
    ->get();
}
function getBrand(){
    return brand::orderBy('name', 'ASC');
}
?>
