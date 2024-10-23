<?php

namespace App\Interfaces\Domain;

interface IKeywordsRepository
{

    public function isExist($name);
    public function isFixed($name);
    public function activate_keyword($name);
    public function deactivate_keyword($name);
    public function add_keyword($name, $points, $type);
    public function get_keyword_data($name);
    public function get_keyword_id($name);
    public function update_points($name, $points);
    public function delete_keyword($name);
    public function get_all_fixed();
    public function get_all_golden();
}
