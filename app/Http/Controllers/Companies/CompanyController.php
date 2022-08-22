<?php

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Company\StoreCompanyRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['companies'] = Company::all();

        return Inertia::render('Companies/Index', [
            'companies' => $data['companies']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Companies/Save');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreCompanyRequest  $request
     * @return \Illuminate\Http\Response
     * @param Company $company
     */
    public function store(StoreCompanyRequest $request, Company $company)
    {
        $input = $request->all();
        // $input['current_team_id'] = 1;

        DB::transaction(function () use ($input, $company) {
            $created_user = User::create([
                'name' => $input['company_name'],
                'email' => $input['company_email'],
                'password' => Hash::make($input['company_password']),
                // 'current_team_id' => $input['current_team_id'],
            ]);
            $input['user_id'] = $created_user->id;
            $company->create($input);
        });


        return redirect(route('companies.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        return Inertia::render('Companies/Save',[
            'company' => $company
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
    }
}
