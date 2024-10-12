<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;

class UploadController extends Controller
{
   /**
     * Mostra la lista degli upload dell'utente.
     */
    public function index()
    {
        $uploads = Upload::where('user_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        return view('uploads.index', compact('uploads'));
    }

    /**
     * Mostra il form per creare un nuovo upload.
     */
    public function create()
    {
        return view('uploads.create');
    }

    /**
     * Gestisce il salvataggio del nuovo upload.
     */
    public function store(Request $request)
    {
        // Validazione dei dati in ingresso
        $request->validate([
            'file' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,pdf,doc,docx,txt',
            'text' => 'nullable|string|max:5000',
        ]);

        $upload = new Upload();
        $upload->user_id = auth()->id();

        // Gestione del file caricato
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('uploads', 'public'); // Salva nella cartella 'uploads' del disco 'public'
            $upload->file_path = $path;
            $upload->file_name = $request->file('file')->getClientOriginalName(); // Salva il nome originale del file
        }

        // Gestione del testo incollato
        if ($request->filled('text')) {
            $upload->text_content = $request->input('text');
        }

        $upload->save();

        return redirect()->route('uploads.index')->with('success', 'Upload effettuato con successo!');
    }

    /**
     * Scarica il file caricato.
     */
    public function download($id)
    {
        $upload = Upload::findOrFail($id);

        // Controlla che l'utente abbia accesso all'upload
        if ($upload->user_id !== auth()->id()) {
            abort(403);
        }

        if ($upload->file_path && Storage::disk('public')->exists($upload->file_path)) {
            return Storage::disk('public')->download($upload->file_path, $upload->file_name);
        }

        return redirect()->back()->with('error', 'File non trovato.');
    }
}
