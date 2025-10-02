<?php

namespace App\Http\Controllers;

//use App\Models\Host;
//use App\Models\UserCustomList;
use Illuminate\Http\Request;

class HostController extends Controller
{
    public function index()
    {
        // Get total counts
//        $totalHosts = Host::count();
//        $totalCustomHosts = UserCustomList::count();
//
//        // Get all hosts
//        $hosts = Host::limit(10)->where('source', '<>', 'manual')->orderBy("id",'desc')->get();
//        $customHosts = UserCustomList::with('host')
//            ->limit(10)
//            ->orderBy("id",'desc')->get();

        return view('welcome');
    }
}
