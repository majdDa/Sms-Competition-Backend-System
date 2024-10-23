<?php

namespace App\Repositories\Domain;

use App\Interfaces\Domain\IKeywordsRepository;
use App\Models\Command;
use App\Models\Inbox;
use App\Models\Keyword;
use PhpOffice\PhpSpreadsheet\Helper\Size;

class KeywordsRepository implements IKeywordsRepository
{

    public function isExist($name)
    {
        $keyword = Keyword::where('name', $name)->first();

        if (is_null($keyword)) {
            return false;
        } else {
            return true;
        }
    }

    // -----
    public function activate_keyword($name)
    {

        $keyword = Keyword::where('name', $name)->first();

        $keyword->is_active = true;

        $keyword->save();

        return $keyword->is_active;
    }

    // ---
    public function isFixed($name)
    {

        $keyword = Keyword::where('name', $name)->first();

        return $keyword->is_fix;
    }

    // ----
    public function deactivate_keyword($name)
    {

        $keyword = Keyword::where('name', $name)->first();

        $keyword->is_active = false;

        $keyword->save();

        return !$keyword->is_active;
    }

    //------------- ADD
    public function add_keyword($name, $points, $type)
    {
        /* chech name is not in commands */
        $com = Command::where('name', $name)->get();
        if (count($com) != 0) {
            return false;
        }

        $keyword = Keyword::where('name', '=', $name)->where('type', 'golden')->first();

        if ($keyword) {
            $this->activate_keyword($keyword['name']);
            $this->update_points($keyword['name'], $points);
        } else {
            $keyword = Keyword::create([
                'name' => $name,
                'points' => $points,
                'type' => $type
            ]);
        }



        return $this->get_all_golden();
    }

    // ---
    public function get_keyword_data($name)
    {

        $keyword = Keyword::where('name', $name)->first();

        return $keyword;
    }

    public function get_keyword_id($name)
    {

        $keyword = Keyword::where('name', $name)->value('id');

        return $keyword;
    }


    // ---
    public function update_points($name, $points)
    {

        $keyword = Keyword::where('name', $name)->where('type', 'golden')->first();

        if (!$keyword) {
            return response()->json("can`t edit fixed keyword ");
        }

        $keyword->points = $points;

        $keyword->save();

        return $this->get_all_golden();
    }

    //---
    public function delete_keyword($name)
    {
        $keyword = Keyword::where('name', '=', $name)->where('type', 'golden')->first();
        if (!$keyword) {
            return response()->json("can`t delete keyword ");
        }

        $checkInInbox = Inbox::where('keyword_id', $keyword->id)->first();
        if ($checkInInbox == null) {
            $keyword->delete();
        } else {
            $this->deactivate_keyword($keyword['name']);
        }

        return $this->get_all_golden();
    }

    //-----
    public function get_all_golden()
    {
        $keyword = Keyword::where('type', 'golden')->where('is_active', true)->get();
        return $keyword;
    }

    //---
    public function get_all_fixed()
    {
        $keyword = Keyword::where('type', 'fixed')->where('is_active', true)->get();
        return $keyword;
    }
}
