<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $books = Book::with(['authors', 'categories'])
            ->when($request->author_id, function ($query) use ($request) {
                $query->whereHas('authors', function ($q) use ($request) {
                    $q->where('authors.id', $request->author_id);
                });
            })
            ->when($request->category_id, function ($query) use ($request) {
                $query->whereHas('categories', function ($q) use ($request) {
                    $q->where('categories.id', $request->category_id);
                });
            })
            ->when($request->location, function ($query) use ($request) {
                $query->where('location', 'LIKE', "%{$request->location}%");
            })
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Books fetched successfully.',
            'data' => $books,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        $book = Book::updateOrCreate(
            ['id' => $request->id],
            $request->validated()
        );

        $book->authors()->sync($request->author_ids);
        $book->categories()->sync($request->category_ids);

        return response()->json([
            'message' => $request->id ? 'Book updated successfully' : 'Book created successfully',
            'data' => $book->load('authors', 'categories'),
        ], $request->id ? 200 : 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return response()->json([
            'status' => true,
            'message' => 'Book fetched successfully.',
            'data' => $book->load('authors', 'categories'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, Book $book)
    {
        //update
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->authors()->detach();
        $book->categories()->detach();
        $book->delete();
        return response()->noContent();
    }
}
