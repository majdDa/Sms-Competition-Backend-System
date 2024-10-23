<?php


namespace App\Models;



class SmsCategory {
    public $_categoryId;
    public $_id;

    public function __construct($categoryId,$id)
    {
        $this -> _categoryId = $categoryId;
        $this -> _id = $id;
    }

}
