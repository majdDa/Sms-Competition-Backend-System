<?php

namespace App\Interfaces\Domain;

interface ITeasersRepository
{
	public function get_all($request);
	public function add($request);
	public function update($request);
	public function search($request);

	public function delete($request);
	public function get_teaser_to_send();
}
