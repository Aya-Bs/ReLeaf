<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    // Affiche tous les blogs (public) avec pagination et filtre par titre
  public function index(Request $request)
{
    $query = Blog::query();

    if ($request->filled('title')) {
        $query->where('title', 'like', '%' . $request->title . '%');
    }

    // Pagination : 6 blogs par page
    $blogs = $query->latest()->paginate(6);

    return view('blogs.index', compact('blogs'));
}


    // Vue des blogs sous forme de cartes (publique) avec pagination
    public function cards(Request $request)
    {
        $query = Blog::query();

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        $blogs = $query->latest()->paginate(6);

        return view('blogs.cards', compact('blogs'));
    }

    // Affiche les blogs de l’utilisateur connecté avec pagination
    public function myBlogs(Request $request)
    {
        $user = auth()->user();

        // Organizers voient leurs propres blogs, les autres utilisateurs voient tous les blogs
        if ($user->role === 'organizer') {
            $query = Blog::where('user_id', $user->id);
        } else {
            $query = Blog::query();
        }

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        $blogs = $query->latest()->paginate(6);

        return view('blogs.myblogs', compact('blogs'));
    }

    // Filtre AJAX par titre (live search)
    public function filter(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'organizer') {
            $query = Blog::where('user_id', $user->id);
        } else {
            $query = Blog::query();
        }

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        $blogs = $query->latest()->paginate(6);

        if ($blogs->count() > 0) {
            return view('blogs.partials.blogs_list', compact('blogs'))->render();
        }

        return '<div class="alert alert-info">Aucun blog trouvé.</div>';
    }

    // Formulaire de création (organizer uniquement)
    public function create()
    {
        if (Auth::user()->role !== 'organizer') {
            abort(403, 'Vous n’êtes pas autorisé à créer un blog.');
        }
        return view('blogs.create');
    }

    // Stocke un nouveau blog avec validation
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'organizer') {
            abort(403, 'Vous n’êtes pas autorisé à créer un blog.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:20',
            'tags' => ['nullable', 'string', 'max:255', 'regex:/^[\w\s,-]+$/'],
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
            'tags.regex' => 'Les tags doivent être séparés par des virgules et ne contenir que lettres, chiffres ou tirets.',
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

    // Formulaire d’édition
    public function edit(Blog $blog)
    {
        if (Auth::id() !== $blog->user_id && Auth::user()->role !== 'organizer') {
            abort(403);
        }
        return view('blogs.edit', compact('blog'));
    }

    // Mise à jour du blog avec validation
    public function update(Request $request, Blog $blog)
    {
        if (Auth::id() !== $blog->user_id && Auth::user()->role !== 'organizer') {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:20',
            'tags' => ['nullable', 'string', 'max:255', 'regex:/^[\w\s,-]+$/'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'Le titre est obligatoire.',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'content.required' => 'Le contenu est obligatoire.',
            'content.min' => 'Le contenu doit contenir au moins 20 caractères.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'Les formats acceptés sont : jpeg, png, jpg, gif.',
            'image.max' => 'La taille de l’image ne doit pas dépasser 2 Mo.',
           //'tags.regex' => 'Les tags doivent être séparés par des virgules et ne contenir que lettres, chiffres ou tirets.',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
            $validated['image_url'] = '/storage/' . $imagePath;
        }

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
