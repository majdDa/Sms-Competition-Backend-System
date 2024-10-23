<?php

namespace App\Repositories\Domain;

use App\Interfaces\Domain\ITeasersRepository;
use App\Models\Teaser;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeasersRepository implements ITeasersRepository
{
    public function add($request)
    {


        if (is_numeric($request['edit_op_id'])) {

            $request['op_id'] = $request['edit_op_id'];
        }

        Teaser::create([
            'mtxt' => $request['mtxt'],
            'sending_date' => $request['sending_date'],
            'ctg' => '90_min',
            'status_mtn' => 'not send',
            'status_syriatel' => 'not send',
            'ip' => "user name",  // must be set "username  for user"
            'op_id' => $request['op_id'],
        ]);



        $myRequest = new Request();
        if ($request['op_id'] == 3 && is_numeric($request['pre_op_id'])) {
            $request['op_id'] = $request['pre_op_id'];
        }
        $myRequest->merge(['op_id' => $request['op_id']]);
        return $this->get_all($myRequest);
    }

    public function get_all($request)
    {
        $op_id = 3;
        if (is_numeric($request)) {
            $op_id = $request;
        } else {
            $op_id = $request->op_id;
        }
        $t = Teaser::where('op_id', "=", $op_id)->orwhere('op_id', "=", 3)->orderByDesc('sending_date')->get();
        return response()->json($t);
    }

    public function update($request)
    {
        $date = date("Y-m-d h:i:sa");
        $teser = Teaser::where('id', $request->id)->first();
        if ($teser->sending_date < $date) {
            return response()->json('can`t update teaser from last date, must be greater than ' . $date . ' .');
        }

        if ($teser->op_id == 3) {
            $teser->op_id = 3 - $request->edit_op_id;
            $teser->save();

            $request->op_id = $request->edit_op_id;

            $this->add($request);
        } else {

            $teser->mtxt = $request['mtxt'];
            $teser->ctg = $request['ctg'];
            $teser->status_mtn = $request['status_mtn'];
            $teser->status_syriatel = $request['status_syriatel'];
            $teser->sending_date = $request['sending_date'];
            $teser->ip = $request['ip'];
            $teser->op_id = $request['op_id'];
            $teser->save();
        }



        return $this->get_all($request->edit_op_id);
    }


    public function delete($request)
    {

        $del_op_id = $request->del_op_id;
        $t = Teaser::where('id', $request->id)->first();

        $date = date("Y-m-d h:i:sa");

        if ($t->sending_date < $date) {
            return response()->json('can`t delete teaser from last date, must be greater than ' . $date . ' .');
        }

        if ($t->op_id == 3) {
            $t->op_id = 3 - $del_op_id;
            $t->save();
            // $this->add($request);
        } else {

            $t->delete();
        }

        return $this->get_all($del_op_id);
    }

    public function search($request)
    {
        $t = Teaser::where([['op_id', "=", $request->op_id], ['mtxt', 'like', '%' . $request->text . '%']])
            ->orwhere([['op_id', "=", 3], ['mtxt', 'like', '%' . $request->text . '%']])

            ->orwhere([['op_id', "=", $request->op_id], ['sending_date', 'like', '%' . $request->text . '%']])
            ->orwhere([['op_id', "=", 3], ['sending_date', 'like', '%' . $request->text . '%']])->get();

        return response()->json($t);
    }

    public function get_teaser_to_send()
    {
        $teaser = Teaser::where('sending_date', '<=', Carbon::now())
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('status_mtn', '=', 'not send')
                        ->whereIn('op_id', [2, 3]);
                })
                    ->orWhere(function ($query) {
                        $query->where('status_syriatel', '=', 'not send')
                            ->whereIn('op_id', [1, 3]);
                    });
            })
            ->get();

        return $teaser;
    }
}
