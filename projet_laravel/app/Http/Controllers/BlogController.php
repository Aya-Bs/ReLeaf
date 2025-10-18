<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    // Affiche tous les blogs (public)
    public function index()
    {
        $blogs = Blog::latest()->get();
        return view('blogs.index', compact('blogs'));
    }

    // Vue des blogs sous forme de cartes (publique)
    public function cards()
    {
        $blogs = Blog::latest()->get();
        return view('blogs.cards', compact('blogs'));
    }

    // Affiche les blogs de l’utilisateur connecté
    public function myBlogs()
    {
        $user = auth()->user();
        $blogs = Blog::where('user_id', $user->id)->get();
        return view('blogs.myblogs', compact('blogs'));
    }

    // Formulaire de création (organizer uniquement)
    public function create()
    {
        if (Auth::user()->role !== 'organizer') {
            abort(403, 'Vous n’êtes pas autorisé à créer un blog.');
        }
        return view('blogs.create');
    }

    // ✅ Stocke un nouveau blog avec validation stricte
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'organizer') {
            abort(403, 'Vous n’êtes pas autorisé à créer un blog.');
        }

        // 🔍 Validation avec messages personnalisés
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:20',
            'tags' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'Le titre est obligatoire.',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'content.required' => 'Le contenu est obligatoire.',
            'content.min' => 'Le contenu doit contenir au moins 20 caractères.',
            'image.required' => 'L’image est obligatoire.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'Les formats acceptés sont : jpeg, png, jpg, gif.',
                        'image.max' => 'La taille de l’image ne doit pas dépasser 2 Mo.',
        ]);

        // ✅ Ajout des champs automatiques
        $validated['user_id'] = Auth::id();
        $validated['date_posted'] = now();

        // ✅ Sauvegarde de l’image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
            $validated['image_url'] = '/storage/' . $imagePath;
        }

        // ✅ Création du blog
        Blog::create($validated);

        return redirect()->route('blogs.myblogs')->with('success', 'Blog créé avec succès !');
    }

    // Formulaire d’édition
    public function edit(Blog $blog)
    {
        if (Auth::id() !== $blog->user_id && Auth::user()->role !== 'organizer') {
            abort(403);
        }
        return view('blogs.edit', compact('blog'));
    }

    // ✅ Mise à jour du blog avec validation
    public function update(Request $request, Blog $blog)
    {
        if (Auth::id() !== $blog->user_id && Auth::user()->role !== 'organizer') {
            abort(403);
        }

        // Validation stricte + messages personnalisés
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:20',
            'tags' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'Le titre est obligatoire.',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'content.required' => 'Le contenu est obligatoire.',
            'content.min' => 'Le contenu doit contenir au moins 20 caractères.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'Les formats acceptés sont : jpeg, png, jpg, gif.',
            'image.max' => 'La taille de l image ne doit pas dépasser 2 Mo.',
            'tags.regex' => 'Les tags doivent être séparés par des virgules et ne contenir que des lettres, chiffres ou tirets.',

        ]);

        // ✅ Gestion de la nouvelle image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
            $validated['image_url'] = '/storage/' . $imagePath;
        }

        // ✅ Mise à jour du blog
        $blog->update($validated);

        return redirect()->route('blogs.myblogs')->with('success', 'Blog mis à jour avec succès !');
    }

    // Suppression
    public function destroy(Blog $blog)
    {
        if (Auth::id() !== $blog->user_id && Auth::user()->role !== 'organizer') {
            abort(403);
        }

        $blog->delete();
        return redirect()->route('blogs.myblogs')->with('success', 'Blog supprimé avec succès !');
    }

    // Affiche les détails d’un blog
    public function show(Blog $blog)
    {
        return view('blogs.show', compact('blog'));
    }
}

