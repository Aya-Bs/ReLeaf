<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Afficher tous les commentaires d'un blog (pour l'auteur)
    public function index($blogId)
    {
        $blog = Blog::findOrFail($blogId);

        if (Auth::id() !== $blog->author_id) {
            abort(403);
        }

        $reviews = $blog->reviews()->latest()->get();
        return view('reviews.index', compact('blog', 'reviews'));
    }

    // Afficher un commentaire spécifique
    public function show($id)
    {
        $review = Review::findOrFail($id);
        return view('reviews.show', compact('review'));
    }

    // Formulaire de création d'un commentaire
    public function create($blogId)
    {
        $blog = Blog::findOrFail($blogId);
        return view('backend.reviews.create', compact('blog'));
    }

    // Enregistrer un commentaire
    public function store(Request $request, $blogId)
    {
        $blog = Blog::findOrFail($blogId);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => [
                'required',
                'string',
                'min:5',
                'max:500',
                'regex:/^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s.,!?\'"()-]+$/',
                function ($attribute, $value, $fail) {
                    $banned = ['idiot', 'stupide', 'nul', 'mauvais', 'imbécile'];
                    foreach ($banned as $word) {
                        if (stripos($value, $word) !== false) {
                            $fail('Le commentaire contient un mot inapproprié : "' . $word . '"');
                        }
                    }
                },
            ],
        ], [
            'comment.required' => 'Le commentaire est obligatoire.',
            'comment.min' => 'Le commentaire doit contenir au moins 5 caractères.',
            'comment.max' => 'Le commentaire ne doit pas dépasser 500 caractères.',
            'comment.regex' => 'Le commentaire contient des caractères non autorisés.',
            'rating.required' => 'La note est obligatoire.',
            'rating.min' => 'La note doit être au minimum 1.',
            'rating.max' => 'La note ne peut pas dépasser 5.',
        ]);

        Review::create([
            'user_name' => Auth::user()->name,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'date_posted' => now(),
            'blog_id' => $blog->id,
        ]);

        return redirect()->route('blogs.show', $blog->id)
            ->with('success', 'Commentaire ajouté avec succès !');
    }

    // Formulaire d'édition
    public function edit($id)
    {
        $review = Review::findOrFail($id);

        if (Auth::id() !== $review->user_id) {
            abort(403);
        }

        return view('backend.reviews.edit', compact('review'));
    }

    // Mettre à jour un commentaire
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        if (Auth::id() !== $review->user_id) {
            abort(403, 'Action non autorisée');
        }

        $request->validate([
            'comment' => [
                'required',
                'string',
                'min:5',
                'max:500',
                'regex:/^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s.,!?\'"()-]+$/',
                function ($attribute, $value, $fail) {
                    $banned = ['idiot', 'stupide', 'nul', 'mauvais', 'imbécile'];
                    foreach ($banned as $word) {
                        if (stripos($value, $word) !== false) {
                            $fail('Le commentaire contient un mot inapproprié : "' . $word . '"');
                        }
                    }
                },
            ],
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review->update([
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        return redirect()->back()->with('success', 'Commentaire modifié avec succès.');
    }

    // Supprimer un commentaire
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        if (Auth::id() !== $review->user_id) {
            abort(403, 'Action non autorisée');
        }

        $review->delete();

        return redirect()->back()->with('success', 'Commentaire supprimé avec succès.');
    }
}
