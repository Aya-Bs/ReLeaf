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
        // Vérifier que l'utilisateur est l'auteur
        if (Auth::id() !== $blog->author_id) {
            abort(403);
        }
        $reviews = $blog->reviews()->latest()->get();
        return view('reviews.index', compact('blog', 'reviews'));
    }

    // Afficher un commentaire
    public function show($id)
    {
        $review = Review::findOrFail($id);
        return view('reviews.show', compact('review'));
    }

    // Formulaire de création de commentaire
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
            'comment' => 'required|string|max:1000',
        ]);
        $review = new Review([
            'user_name' => Auth::user()->name,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'date_posted' => now(),
            'blog_id' => $blog->id,
        ]);
        $review->save();
        // Rediriger vers la page du blog après ajout
return redirect()->route('blogs.show', $blog->id)->with('success', 'Commentaire ajouté !');
    }

    // Formulaire d'édition
    public function edit($id)
    {
        $review = Review::findOrFail($id);
        // Seul l'auteur du commentaire peut éditer
        if (Auth::id() !== $review->user_id) {
            abort(403);
        }
        return view('backend.reviews.edit', compact('review'));
    }

    // Mettre à jour un commentaire
    public function update(Request $request, $id)
{
    $review = Review::findOrFail($id);

    if (auth()->user()->name !== $review->user_name) {
        abort(403, 'Action non autorisée');
    }

    $request->validate([
        'comment' => 'required|string|max:500',
        'rating' => 'required|integer|min:1|max:5',
    ]);

    $review->update([
        'comment' => $request->comment,
        'rating' => $request->rating,
    ]);

    return redirect()->back()->with('success', 'Commentaire modifié avec succès.');
}

public function destroy($id)
{
    $review = Review::findOrFail($id);

    if (auth()->user()->name !== $review->user_name) {
        abort(403, 'Action non autorisée');
    }

    $review->delete();

    return redirect()->back()->with('success', 'Commentaire supprimé avec succès.');
}



}