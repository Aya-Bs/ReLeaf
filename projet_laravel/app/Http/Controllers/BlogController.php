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

    // Affiche les blogs sous forme de cartes
    public function cards()
{
    // Tous les blogs pour tous les utilisateurs
    $blogs = Blog::latest()->get();
    return view('blogs.cards', compact('blogs')); // <-- nouvelle vue pour utilisateurs
}
    // Affiche les blogs de l'utilisateur connecté
    public function myBlogs()
    {
        $user = auth()->user();

        if ($user->role === 'organizer') {
            // L'organizer voit tous ses blogs
            $blogs = Blog::where('user_id', $user->id)->get();
        } else {
            // L'utilisateur normal voit ses propres blogs
            $blogs = Blog::where('user_id', $user->id)->get();
        }

        return view('blogs.myblogs', compact('blogs'));
    }

    // Formulaire création (seulement organizer)
    public function create()
    {
        if (Auth::user()->role !== 'organizer') {
            abort(403, 'Vous n'êtes pas autorisé à créer un blog.');
        }
        return view('blogs.create');
    }

    // Stocke le blog (seulement organizer)
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'organizer') {
            abort(403, 'Vous n'êtes pas autorisé à créer un blog.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['date_posted'] = now();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
            $validated['image_url'] = '/storage/' . $imagePath;
        }

        Blog::create($validated);

        return redirect()->route('blogs.myblogs')->with('success', 'Blog créé avec succès !');
    }

    // Formulaire édition
    public function edit(Blog $blog)
    {
        if (Auth::id() !== $blog->user_id && Auth::user()->role !== 'organizer') {
            abort(403);
        }
        return view('blogs.edit', compact('blog'));
    }

    // Met à jour le blog
    public function update(Request $request, Blog $blog)
    {
        if (Auth::id() !== $blog->user_id && Auth::user()->role !== 'organizer') {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
            $validated['image_url'] = '/storage/' . $imagePath;
        }

        $blog->update($validated);

        return redirect()->route('blogs.myblogs')->with('success', 'Blog mis à jour !');
    }

    // Supprimer un blog
    public function destroy(Blog $blog)
    {
        if (Auth::id() !== $blog->user_id && Auth::user()->role !== 'organizer') {
            abort(403);
        }

        $blog->delete();
        return redirect()->route('blogs.myblogs')->with('success', 'Blog supprimé !');
    }

    // Affiche le détail d'un blog
    public function show(Blog $blog)
    {
        return view('blogs.show', compact('blog'));
    }


}
