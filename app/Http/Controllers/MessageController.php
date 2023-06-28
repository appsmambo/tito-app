<?php

namespace App\Http\Controllers;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $messages = Message::orderBy('order', 'asc')->get();
        return view('messages.index', compact('messages'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('messages.create');
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
            'title' => 'required',
            'description' => 'required',
        ]);

        Message::create($request->post());

        return redirect()->route('messages.index')
                ->with('success', 'El mensaje ha sido creado correctamente.');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Message  $message
    * @return \Illuminate\Http\Response
    */
    public function show(Message $message)
    {
        return view('messages.show', compact('message'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Message  $message
    * @return \Illuminate\Http\Response
    */
    public function edit(Message $message)
    {
        return view('messages.edit', compact('message'));
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Message  $message
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Message $message)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $message->fill($request->post())->save();

        return redirect()->route('messages.index')
                ->with('success', 'El mensaje ha sido actualizado correctamente.');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Message  $message
    * @return \Illuminate\Http\Response
    */
    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->route('messages.index')
                ->with('success', 'El mensaje ha sido eliminado.');
    }
}
