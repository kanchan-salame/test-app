<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\DeleteContactRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->id == 1) {
            $data['contacts'] = Contact::all();
        } else {
            $data['contacts'] = Contact::where('user_id', Auth::user()->id)->get();
        }
        return Inertia::render('Contacts/Index',[
            'contacts' => $data['contacts'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Contacts/Save');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreContactRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreContactRequest $request, Contact $contact)
    {
        if($request->file('photo')) {
            $photo = Storage::disk('local')->put('public/contacts', $request->file('photo'));
            $request['photo_path'] = explode('/',$photo)[1]. '/' . explode('/',$photo)[2];
        }
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $contact->create($data);
        return redirect(route('contact.index'));
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
    public function edit(Contact $contact)
    {
        // $this->authorize('view', Auth::user());
        // ->can('view', Contact::class);
        return Inertia::render('Contacts/Save',[
            'contact' => $contact
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreContactRequest $request, Contact $contact)
    {
        // dd($contact);
        $contact->update($request->all());
        return redirect(route('contact.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteContactRequest $request, Contact $contact)
    {
        $contact->delete();
        return redirect(route('contact.index'));
    }
}
