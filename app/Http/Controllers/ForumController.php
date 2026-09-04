<?php

namespace App\Http\Controllers;

use App\Models\ForumComment;
use App\Models\ForumPost;
use App\Models\ForumReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ForumController extends Controller
{
    /**
     * Show all forum posts.
     */
    public function index()
    {
        $posts = ForumPost::with([
            'user',
            'reactions',
        ])
            ->withCount('comments')
            ->latest()
            ->paginate(10);

        return view('forum.index', compact('posts'));
    }

    /**
     * Show the create-post form.
     */
    public function create()
    {
        return view('forum.create');
    }

    /**
     * Store a new forum post.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'content' => [
                'required',
                'string',
                'max:10000',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store(
                'forum',
                'public'
            );
        }

        ForumPost::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('forum.index')
            ->with(
                'success',
                'Your post has been published successfully.'
            );
    }

    /**
     * Show a single forum post.
     */
    public function show(ForumPost $forumPost)
    {
        $forumPost->load([
            'user',
            'reactions.user',
            'comments.user',
        ]);

        return view(
            'forum.show',
            compact('forumPost')
        );
    }

    /**
     * Add a comment to a post.
     */
    public function comment(
        Request $request,
        ForumPost $forumPost
    ) {
        $validated = $request->validate([
            'content' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        ForumComment::create([
            'forum_post_id' => $forumPost->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        return back()->with(
            'success',
            'Your comment has been added.'
        );
    }

    /**
     * Add or change a reaction.
     *
     * Each user can have only one reaction
     * per post.
     */
    public function react(
        Request $request,
        ForumPost $forumPost
    ) {
        $validated = $request->validate([
            'reaction' => [
                'required',
                'string',
                Rule::in([
                    'like',
                    'laugh',
                    'heart',
                    'sad',
                ]),
            ],
        ]);

        ForumReaction::updateOrCreate(
            [
                'forum_post_id' => $forumPost->id,
                'user_id' => Auth::id(),
            ],
            [
                'reaction' => $validated['reaction'],
            ]
        );

        return back()->with(
            'success',
            'Your reaction has been updated.'
        );
    }

    /**
     * Delete the authenticated user's own post.
     */
    public function destroy(ForumPost $forumPost)
    {
        abort_unless(
            $forumPost->user_id === Auth::id(),
            403
        );

        if ($forumPost->image) {
            Storage::disk('public')->delete(
                $forumPost->image
            );
        }

        $forumPost->delete();

        return redirect()
            ->route('forum.index')
            ->with(
                'success',
                'Your post has been deleted.'
            );
    }

    /**
     * Delete the authenticated user's own comment.
     */
    public function destroyComment(
        ForumComment $comment
    ) {
        abort_unless(
            $comment->user_id === Auth::id(),
            403
        );

        $comment->delete();

        return back()->with(
            'success',
            'Your comment has been deleted.'
        );
    }
}