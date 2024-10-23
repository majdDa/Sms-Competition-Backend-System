<?php

namespace App\Interfaces\Domain;

interface IMessagesRepository
{
    public function get_mt($category);
/* 
    public function get_help_message($category);
    public function get_balance_message($category);
    public function get_keyword_message($category);
    public function get_true_answer_message($category);
    public function get_false_answer_message($category);
    public function get_activation_message($category);
    public function get_pending_act_message($category);
    public function get_pending_deact_message($category);
    public function get_renewal_message($category);
    public function get_invalid_message($category);
    public function get_deact_message($category);
    public function get_final_message($category);
    public function get_invalid_last_answer_message($category); */
}