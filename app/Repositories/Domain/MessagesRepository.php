<?php

namespace App\Repositories\Domain;

use App\Interfaces\Domain\IMessagesRepository;
use App\Interfaces\Application\ISyriatelRepository;
use App\Interfaces\Application\IMTNRepository;
use App\Models\Messages;
use Carbon\Carbon;


class MessagesRepository implements IMessagesRepository
{

    private $messages = array(
        "1" => "true_Answer",
        "2" => "false_Answer",
        "3" => "keyword",
        "4" => "help",
        "5" => "balance",
        "6" => "welcoming",
        "7" => "unsubscribe",
        "8" => "pending_activation",
        "9" => "pending_deActivation",
        "10" => "renewal",
        "11" => "invalid",
        "12" => "cancelation",
        "13" => "final_keyword",
        "14" => "invalid_last_answer",

    );


    public function  get_mt($category)
    {
        $id = array_search($category, $this->messages);
        $message = Messages::where('id', $id)->first();
        return $message->message;
    }



    /*     public function  get_renewal_message($category)
    {
        $message = Messages::where('id', array_search($category, $this->messages))->first();
        return $message->message;
    }

    public function get_help_message($category)
    {
        $message = Messages::where('id', array_search($category, $this->messages))->first();
        return $message->message;
    }


    public function get_balance_message($category)
    {
        $message = Messages::where('id', array_search($category, $this->messages))->first();
        return $message->message;
    }

    public function get_keyword_message($category)
    {
        $message = Messages::where('id', array_search($category, $this->messages))->first();
        return $message->message;
    }

    public function get_true_answer_message($category)
    {
        $message = Messages::where('id', array_search($category, $this->messages))->first();
        return $message->message;
    }

    public function get_false_answer_message($category)
    {
        $message = Messages::where('id', array_search($category, $this->messages))->first();
        return $message->message;
    }

    public function get_activation_message($category)
    {
        $message = Messages::where('id', array_search($category, $this->messages))->first();
        return $message->message;
    }

    public function  get_pending_act_message($category)
    {
        $message = Messages::where('id', array_search($category, $this->messages))->first();
        return $message->message;
    }

    public function  get_pending_deact_message($category)
    {
        $message = Messages::where('id', array_search($category, $this->messages))->first();
        return $message->message;
    }

    public function  get_invalid_message($category)
    {
        $message = Messages::where('id', array_search($category, $this->messages))->first();
        return $message->message;
    }


    public function  get_deact_message($category)
    {
        $message = Messages::where('id', array_search($category, $this->messages))->first();
        return $message->message;
    }

    public function  get_final_message($category)
    {
        $message = Messages::where('id', array_search($category, $this->messages))->first();
        return $message->message;
    }

    public function  get_invalid_last_answer_message($category)
    {
        $message = Messages::where('id', array_search($category, $this->messages))->first();
        return $message->message;
    } */
}