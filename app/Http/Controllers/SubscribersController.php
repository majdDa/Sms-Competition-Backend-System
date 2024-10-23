<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\IActivationMTNRepository;


class SubscribersController extends Controller
{
    private IActivationMTNRepository $activaitonRepo;

    public function __construct(IActivationMTNRepository $activaitonRepo)
    {
        $this->activaitonRepo = $activaitonRepo;
    }


}
