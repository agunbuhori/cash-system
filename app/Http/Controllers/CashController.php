<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCashRequest;
use App\Http\Requests\UpdateCashRequest;
use App\Models\Cash;

class CashController extends Controller
{
    public function report()
    {
        $cashes = Cash::whereMonth('date', request('m'))->whereYear('date', request('y'))->get();
        return view('reports.cash.monthly', compact('cashes'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCashRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Cash $cash)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cash $cash)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCashRequest $request, Cash $cash)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cash $cash)
    {
        //
    }
}
