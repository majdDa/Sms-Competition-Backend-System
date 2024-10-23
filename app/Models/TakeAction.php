<?php

namespace App\Models;


class TakeAction
{
    public $gsm;
    public $status;
    public $response;
    public $category;
    // public $username;
    //  public $password;


    public function __construct($request)
    {
        $this->gsm = $request->input('gsm');
        $this->status = $request->input('status');
        $this->category =  $request->has('category') ? $request->input('category') : null;
        $this->response =  $request->has('ticketid') ? $request->input('ticketid') : null;
        // $this->username = $request->input('username');
        // $this->password = $request->input('password');
    }
}
