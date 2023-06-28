<?php

namespace App\Http\Controllers;
use App\Models\Pensioner;
use Illuminate\Http\Request;

class PensionerController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $pensioners = Pensioner::orderBy('id', 'desc')->get();
        return view('pensioners.index', compact('pensioners'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('pensioners.create');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'birth_date' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'dni' => 'required',
            'cip' => 'required',
            'institution' => 'required',
        ]);

        Pensioner::create($request->post());

        return redirect()->route('pensioners.index')
                ->with('success', 'El registro de pensionista ha sido creado correctamente.');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Pensioner  $pensioner
    * @return \Illuminate\Http\Response
    */
    public function show(Pensioner $pensioner)
    {
        return view('pensioners.show', compact('pensioner'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Pensioner  $pensioner
    * @return \Illuminate\Http\Response
    */
    public function edit(Pensioner $pensioner)
    {
        return view('pensioners.edit', compact('pensioner'));
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Pensioner  $pensioner
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Pensioner $pensioner)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'birth_date' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'dni' => 'required',
            'cip' => 'required',
            'institution' => 'required',
        ]);

        $pensioner->fill($request->post())->save();

        return redirect()->route('pensioners.index')
                ->with('success', 'El registro del pensionista ha sido actualizado correctamente.');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Pensioner  $pensioner
    * @return \Illuminate\Http\Response
    */
    public function destroy(Pensioner $pensioner)
    {
        $pensioner->delete();
        return redirect()->route('pensioners.index')
                ->with('success', 'El registro del pensionista ha sido eliminado.');
    }
}
