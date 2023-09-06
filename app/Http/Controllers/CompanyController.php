<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends Controller
{
    public function __construct()
    {
        //$this->authorizeResource(Company::class, 'company');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return JsonResource::collection(Company::get());
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
    public function store(Request $request)
    {
        $company = new Company($request->toArray());
        $company->save();
        return new JsonResource($company);
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Company $company)
    {
        return new JsonResource($company::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Company $company)
    {
        $company::where('id', $id)->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
