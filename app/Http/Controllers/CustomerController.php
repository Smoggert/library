<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Book;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Customer::create($this->validateCreateRequest($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $customer->update($this->validateUpdateRequest($request));
    }

    public function associate(Customer $customer, Book $book)
    {
        $book->customer()->associate($customer);
        $book->save();
    }

    public function dissociate(Customer $customer, Book $book)
    {
        $book->customer()->dissociate();
        $book->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
    }

    protected function validateCreateRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'date_of_birth' => 'required|date'
        ]);
    }

    protected function validateUpdateRequest(Request $request)
    {
        return $request->validate([
            'name' => 'nullable',
            'address' => 'nullable',
            'email' => 'nullable|email',
            'date_of_birth' => 'nullable|date'
        ]);
    }
}
