<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactResource;
use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ContactResource::collection(User::paginate(10));
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
        $request->validate([
            'name' => 'required:max:50',
            'country_code' => 'required|max:5',
            'number' => 'required|max:10|unique:users,number',
        ]);

        $contact = new User(request()->only(['name', 'country_code', 'number']));
        $contact->save();

        return  response()->json([
            'message' => 'Contact saved successfully!',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id' => 'required|max:10',
            'name' => 'required:max:50',
            'country_code' => 'required|max:5',
            'number' => 'required|max:10|unique:users,number,' . $id,
        ]);

        $contact =  User::find($id);
        $contact->update(request()->only(['name', 'country_code', 'number']));

        return  response()->json([
            'message' => 'Contact updated successfully!',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact =  User::find($id);
        $contact->delete();

        return  response()->json([
            'message' => 'Contact deleted successfully!',
        ], 200);
    }

    public function importXML(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xml'
        ]);

        $xmlFile = $request->file('file');
        $xmlContent = simplexml_load_file($xmlFile->getPathname());

        foreach ($xmlContent->contact as $contact) {
            User::create([
                'name' => (string)$contact->name,
                'country_code' => (string)$contact->country_code,
                'number' => (string)$contact->number,
            ]);
        }

        return response()->json([
            'message' => 'Contacts imported successfully from XML',
        ], 200);
    }
}
