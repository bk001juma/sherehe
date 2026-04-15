<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * Display all comments/feedbacks
     */
    public function index()
    {
        $comments = Feedback::with('user:id,first_name,last_name,phone')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('sherehe.dash.comments.index', compact('comments'));
    }

    /**
     * Delete a comment
     */
    public function destroy($id)
    {
        $comment = Feedback::findOrFail($id);
        $comment->delete();

        return redirect()->route('comments.index')
            ->with('success', 'Comment deleted successfully!');
    }
}
