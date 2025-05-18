<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Str;

class PostController extends Controller
{
    use Authorizable;

    public function index(Request $request, ?Topic $topic = null)
    {
        if ($request->query('query')) {
            $posts = Post::search($request->query('query'))
                ->query(fn (Builder $query) => $query->with(['user', 'topic']))
                ->when($topic, fn (\Laravel\Scout\Builder $query) => $query->where('topic_id', $topic->id));
        } else {
            $posts = Post::includeUserAndTopic()
                ->when($topic, fn (Builder $query) => $query->whereBelongsTo($topic))
                ->latest()
                ->latest('id');
        }

        return inertia('Posts/PostIndex', [
            'posts' => fn () => PostResource::collection($posts->paginate(config('forum.pagination_per_page'))
                ->withQueryString()),
            'selectedTopic' => fn () => $topic ? TopicResource::make($topic) : null,
            'topics' => fn () => TopicResource::collection(Topic::all()),
            'query' => $request->query('query'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'topic_id' => ['required', 'exists:topics,id'],
            'title' => ['required', 'string', 'min:10', 'max:120'],
            'body' => ['required', 'string', 'min:100', 'max:10000'],
        ]);

        $post = Post::create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return redirect()->to($post->showRoute())
            ->with('success', 'Post created successfully.');
    }

    public function create(?Post $post)
    {
        Gate::authorize('create', $post);

        return inertia()->modal('Posts/AddEditPost', [
            'topics' => fn () => TopicResource::collection(Topic::all()),
        ])->baseRoute('posts.index');
    }

    public function edit(Post $post)
    {
        $post->load(['user']);

        return inertia()->modal('Posts/AddEditPost', [
            'post' => fn () => $post,
            'topics' => fn () => TopicResource::collection(Topic::all()),
        ])->baseRoute('posts.index');
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'min:10', 'max:120'],
            'body' => ['required', 'string', 'min:10', 'max:10000'],
            'topic_id' => ['required', 'exists:topics,id'],
        ]);

        $post->update($data);

        return redirect()->route('posts.show',
            ['post' => $post->id, 'slug' => $post->slug(), 'page' => request()->query('page')])
            ->with('success', 'Post updated successfully.');
    }

    public function show(Request $request, Post $post)
    {
        if (! Str::endsWith($post->showRoute(request()->query()), $request->getRequestUri())) {
            return redirect()->to($post->showRoute($request->query()), status: 301);
        }

        $post->load(['user', 'topic']);

        return inertia('Posts/PostShow', [
            'post' => fn () => PostResource::make($post)
                ->withLikePermission()
                ->withAddEditPermission(),
            'comments' => function () use ($post) {
                $commentResource = CommentResource::collection($post->comments()->with('user')->latest()->latest('id')->paginate(config('forum.pagination_per_page')));
                $commentResource->collection->transform(fn ($comment) => $comment->withLikePermission());

                return $commentResource;
            },
        ]);
    }

    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return to_route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    public function delete(Post $post)
    {
        return inertia()->modal('DeletePost', [
            'post' => fn () => $post,
        ])->baseRoute('posts.show', ['post' => $post->id, 'slug' => $post->slug(), 'page' => request()->query('page')]);
    }
}
