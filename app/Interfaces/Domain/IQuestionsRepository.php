<?php

namespace App\Interfaces\Domain;

interface IQuestionsRepository
{
	public function get_all_questions();
	public function add_question($request);
	public function add_file_questions($request);
	public function get_questions_by_date($date);
	public function update_question($request);
	public function isFinal($id);
	public function check_answer($id, $answer);

	public function get_next_question($order);
	public function get_current_question($order);
	public function delete_question($id);
}
