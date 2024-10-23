<?php

namespace App\Models;


class Mo
{
    public $gsm;
    public $sms;
    public $sc;
    public $langId;
    public $reqId;
    public $operator;
    public $op_timestamp;
    public $is_processed;
    public $renewal_by;




    public function __construct($request, $op)
    {
        $this->operator = $op;
        $this->gsm = $request->input('GSM');
        $this->sms = $request->input('MSGtxt');
        $this->sc = $request->input('SC');
        $this->langId = $request->has('langID') ? $request->input('langID') : null;
        $this->reqId = $request->has('reqID') ? $request->input('reqID') : null;
        $this->op_timestamp = $request->has('timestamp') ? \DateTime::createFromFormat('dmYHis', $request->input('timestamp')) : null;
        $this->is_processed = null;
        $this->renewal_by = $request->has('renewal_by') ? $request->input('renewal_by') : null;
    }
}

//\DateTime::createFromFormat('dmYHis', $request->input('op_timestamp'))->format('Y-m-d H:i:s')