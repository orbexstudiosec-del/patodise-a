<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:160',
            'subject' => 'nullable|string|max:160',
            'message' => 'required|string|max:2000',
        ]);

        // TODO: enviar email real. Por ahora solo log para demo.
        logger()->info('Contacto recibido', $request->only(['name', 'email', 'subject', 'message']));

        return redirect()->route('contact')->with('status', '¡Gracias! Te responderé pronto.');
    }
}
