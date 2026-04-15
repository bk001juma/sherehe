@extends('layouts.dash')

@section('template_title')
    Comments
@endsection

@section('content')
<div class="dashboard__content bg-light-4" style="background-color:#f4f6f6">
    <div class="row y-gap-20 justify-between pt-30">
        <div class="col-auto sm:w-1/1">
            <h1 class="text-30 lh-12 fw-700">User Comments</h1>
            <p class="text-15 text-dark-1">View all user feedback and comments</p>
        </div>
    </div>

    <div class="py-30 px-30 rounded-16 bg-white mt-30 shadow-2">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-20" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($comments->isEmpty())
            <div class="text-center py-50">
                <i class="icon-comment text-60 text-light-1"></i>
                <h3 class="text-18 fw-500 mt-20">No Comments Yet</h3>
                <p class="text-14 text-light-1 mt-5">User comments will appear here</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table-3 -border-bottom col-12">
                    <thead class="bg-light-2">
                        <tr>
                            <th class="text-dark-1 fw-600">#</th>
                            <th class="text-dark-1 fw-600">User</th>
                            <th class="text-dark-1 fw-600">Phone</th>
                            <th class="text-dark-1 fw-600">Comment</th>
                            <th class="text-dark-1 fw-600">Date</th>
                            <th class="text-dark-1 fw-600">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comments as $index => $comment)
                            <tr>
                                <td>{{ $comments->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex items-center">
                                        <div class="size-40 rounded-full bg-blue-1 flex-center">
                                            <span class="text-white fw-600 text-14">
                                                {{ strtoupper(substr($comment->user->first_name ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="ml-10">
                                            <div class="text-14 fw-500 text-dark-1">
                                                {{ ($comment->user->first_name ?? '') . ' ' . ($comment->user->last_name ?? '') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-14">{{ $comment->user->phone ?? 'N/A' }}</td>
                                <td class="text-14" style="max-width: 400px;">
                                    <div style="white-space: pre-wrap; word-wrap: break-word;">
                                        {{ Str::limit($comment->message, 200) }}
                                    </div>
                                </td>
                                <td class="text-14">
                                    {{ $comment->created_at->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button -sm -red-1 text-white">
                                            <i class="icon-trash mr-5"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-30">
                {{ $comments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
