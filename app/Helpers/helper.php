<?php
use App\Models\Category;
function getCategoris(){
    return Category::orderBy('name', 'ASC')->get();
}
?>
