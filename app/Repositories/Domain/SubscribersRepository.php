<?php

namespace App\Repositories\Domain;

use App\Interfaces\Domain\ISubscribersRepository;
use App\Models\Subscriber;
use Carbon\Carbon;

class SubscribersRepository implements ISubscribersRepository
{

    public function isExist($gsm)
    {
        $subscriber = Subscriber::where('gsm', $gsm)->first();

        if (is_null($subscriber)) {
            return false;
        } else {
            return true;
        }
    }


    public function is_active($gsm)
    {
        $active_subscriber = Subscriber::where([['gsm', '=', $gsm], ['sub_status', '=', '1']])->first();
        if (is_null($active_subscriber)) {
            return false;
        } else {
            return true;
        }
    }

    public function is_not_active($gsm)
    {
        $active_subscriber = Subscriber::where([['gsm', '=', $gsm], ['sub_status', '=', '0']])->first();
        if (is_null($active_subscriber)) {
            return false;
        } else {
            return true;
        }
    }


    public function check_status($gsm)
    {

        $subscriber = Subscriber::where('gsm', $gsm)->value('sub_status');

        if ($subscriber) {
            return true;
        }

        return false;
    }

    public function add_subscriber($gsm, $operator, $score, $sc, $user)
    {
        $sub =  Subscriber::create([
            'gsm' => $gsm,
            'operator' => $operator,
            'sub_date' => Carbon::now(),
            'last_response_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'question_order' => 1,
            'short_code' => $sc,
            'score' => $score,
            'sub_status' => 1,
            'activated_by' => $user
        ]);

        return $sub;
    }



    public function cancel_subscribtion($gsm, $user)
    {
        $subscriber = Subscriber::where('gsm', $gsm)->first();
        $subscriber->sub_status = 0;
        $subscriber->question_order = 1;
        $subscriber->canceled_by = $user;
        $subscriber->last_answer = 0;
        $subscriber->last_response_date = Carbon::now();
        $subscriber->cancel_date = Carbon::now();

        $subscriber->save();
    }

    public function cancel_subscribtion_by_POS($gsm, $user)
    {
        $subscriber = Subscriber::where('gsm', $gsm)->first();
        $subscriber->sub_status = 0;
        $subscriber->question_order = 1;
        $subscriber->canceled_by = $user;
        $subscriber->last_answer = 0;
        $subscriber->last_response_date = Carbon::now();
        $subscriber->cancel_date = Carbon::now();

        $subscriber->save();
        $response = [
            'cancel_date' => $subscriber->cancel_date,
            'last_response_date' => $subscriber->last_response_date,
        ];
        return $response;
    }



    public function renewal_subscribtion($gsm, $user)
    {

        $subscriber = Subscriber::where('gsm', $gsm)->first();

        $subscriber->sub_status = 1;
        $subscriber->last_response_date = Carbon::now();
        $subscriber->last_answer = 0;
        $subscriber->question_order = 1;
        $subscriber->sub_date = Carbon::now();
        $subscriber->activated_by = $user;

        $subscriber->save();
    }

    public function update_points($subscriber_id, $score)
    {
        $subscriber = Subscriber::where('id', $subscriber_id)->first();
        $subscriber->score = $subscriber->score + $score;
        $subscriber->last_response_date = Carbon::now();
        $subscriber->save();
        return true;
    }


    public function update_question_order($subscriber_id, $question_order)
    {

        $subscriber = Subscriber::where('id', $subscriber_id)->first();
        $subscriber->question_order = $question_order;
        $subscriber->last_response_date = Carbon::now();
        $subscriber->save();
        return true;
    }


    public function update_last_answer($subscriber_id)
    {
        $subscriber = Subscriber::where('id', $subscriber_id)->first();
        $subscriber->last_answer = 1;
        $subscriber->save();
        return true;
    }


    public function get_subscriber_info($gsm)
    {
        $subscriber = Subscriber::where('gsm', $gsm)->first();
        return $subscriber;
    }
   
    public function get_all_active_mtn_subscribers()
    {
        $subscriberes = Subscriber::where([['operator', '=', 2], ['sub_status', '=', 1]])->get();
        return $subscriberes;
    }

    public function get_all_mtn_users($request)
    {
        $filter = $request->input('filter');
        $perPage = $request->input('perPage', 10);

        $operator = 2;
        $sub_status = null;

        if ($filter === 'active') {
            $sub_status = 1;
        } elseif ($filter === 'inactive') {
            $sub_status = 0;
        }

        $query = Subscriber::where('operator', $operator);

        if ($sub_status !== null) {
            $query->where('sub_status', $sub_status);
        }
        $subscribers = $query->paginate($perPage);
        return response()->json($subscribers);


        /*   $operator = 2;
        $sub_status = null;

        if ($filter->filter === 'active') {
            $sub_status = 1;
        } elseif ($filter->filter === 'inactive') {
            $sub_status = 0;
        }
        $subscriberes = Subscriber::where('operator', $operator)
            ->when($sub_status !== null, function ($query) use ($sub_status) {
                return $query->where('sub_status', $sub_status);
            })->get();

        return response()->json($subscriberes); */
    }
}