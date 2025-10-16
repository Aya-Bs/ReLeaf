<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Affiche les blogs sous forme de cards (AllBlogs)
     */
    public function cards()
    {
        $current = Auth::user();
        if ($current && $current->role === 'user') {
            $blogs = Blog::latest()->get();
        } else {
            $blogs = Blog::where('author_id', Auth::id())->latest()->get();
        }

        return view('backend.blogs.cards', compact('blogs'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Recherche par titre (affiche seulement les blogs de l'auteur connecté)
        // Eager load author + profile to éviter N+1 et afficher prénom/nom
        $query = Blog::with(['author.profile'])
            ->where('author_id', Auth::id());
        if (request()->filled('search')) {
            $search = request('search');
            $query->where('title', 'like', "%{$search}%");
        }
        $blogs = $query->get();

        return view('backend.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Affiche le formulaire de création
        return view('backend.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des champs
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'Le titre est obligatoire.',
            'content.required' => 'Le contenu est obligatoire.',
            'tags.required' => 'Les tags sont obligatoires.',
            'image.required' => 'L\'image est obligatoire.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être de type jpeg, png, jpg ou gif.',
            'image.max' => 'L\'image ne doit pas dépasser 2Mo.',
        ]);

        $data = $validated;
        $data['author_id'] = Auth::id();
        $data['date_posted'] = now();

        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
            $data['image_url'] = '/storage/'.$imagePath;
        }

        $blog = Blog::create($data);

        return redirect()->route('auteur.blogs.index')->with('success', 'Blog créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        // Affiche le détail d'un blog
        return view('backend.blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        // Affiche le formulaire d'édition
        return view('backend.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        // Met à jour le blog
        $blog->update($request->all());

        return redirect()->route('auteur.blogs.index')->with('success', 'Blog mis à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        // Supprime le blog
        $blog->delete();

        return redirect()->route('auteur.blogs.index')->with('success', 'Blog supprimé');
    }

    // NbrBl : retourne le nombre total de blogs
    public function nbrBl()
    {
        return response()->json(['count' => Blog::count()]);
    }
}
