<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
 /**
     * @OA\Info(title="Libarary Managment", version="0.1")
     */
class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *     path="/api/book",
     *     summary="Get all books",
     *     @OA\Parameter(
     *         name="author_name",
     *         in="query",
     *         description="Filter books by author name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category_name",
     *         in="query",
     *         description="Filter books by category name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="location",
     *         in="query",
     *         description="Filter books by location",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="A list of books with authors and categories",
     *         @OA\JsonContent(
     *             type="array", 
     *             @OA\Items(
     *                 allOf={
     *                     @OA\Schema(ref="#/components/schemas/Book"),
     *                     @OA\Schema(
     *                         type="object",
     *                         @OA\Property(property="authors", type="array", @OA\Items(ref="#/components/schemas/Author")),
     *                         @OA\Property(property="categories", type="array", @OA\Items(ref="#/components/schemas/Category"))
     *                     )
     *                 }
     *             )
     *         )
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $books = Book::with(['authors', 'categories'])
            ->when($request->author_name, function ($query) use ($request) {
                $query->whereHas('authors', function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->author_name . '%');
                });
            })
            ->when($request->category_name, function ($query) use ($request) {
                $query->whereHas('categories', function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->category_name . '%');
                });
            })
            ->when($request->location, function ($query) use ($request) {
                $query->where('location', 'LIKE', '%' . $request->location . '%');
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
    /**
     * @OA\Post(
     *     path="/api/book",
     *     summary="Create a new book",
     *     operationId="storeBook",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "location", "status", "author_ids", "category_ids"},
     *             @OA\Property(property="name", type="string", description="Book name"),
     *             @OA\Property(property="location", type="string", description="Book location"),
     *             @OA\Property(property="status", type="string", description="Book status", enum={"Available", "Booked"}),
     *             @OA\Property(property="author_ids", type="array", items=@OA\Items(type="integer"), description="Array of author IDs"),
     *             @OA\Property(property="category_ids", type="array", items=@OA\Items(type="integer"), description="Array of category IDs")
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Book created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Book created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Book")
     *         )
     *     )
     * )
     */
    public function store(BookRequest $request)
    {
        $book = Book::create($request->validated());

        $book->authors()->attach($request->author_ids);
        $book->categories()->attach($request->category_ids);

        return response()->json([
            'message' => 'Book created successfully',
            'data' => $book->load('authors', 'categories'),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *     path="/api/book/{id}",
     *     summary="Get a single book by ID",
     *     operationId="showBook",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the book to fetch",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Book details fetched successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Book fetched successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/Book")
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Book not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Book not found")
     *         )
     *     )
     * )
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
    /**
     * @OA\Put(
     *     path="/api/book/{id}",
     *     summary="Update an existing book",
     *     operationId="updateBook",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the book to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "location", "status", "author_ids", "category_ids"},
     *             @OA\Property(property="name", type="string", description="Book name"),
     *             @OA\Property(property="location", type="string", description="Book location"),
     *             @OA\Property(property="status", type="string", description="Book status", enum={"Available", "Booked"}),
     *             @OA\Property(property="author_ids", type="array", items=@OA\Items(type="integer"), description="Array of author IDs"),
     *             @OA\Property(property="category_ids", type="array", items=@OA\Items(type="integer"), description="Array of category IDs")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Book updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Book updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Book")
     *         )
     *     )
     * )
     */
    public function update(BookRequest $request, Book $book)
    {
        $book->update($request->validated());

        $book->authors()->sync($request->author_ids);
        $book->categories()->sync($request->category_ids);

        return response()->json([
            'message' => 'Book updated successfully',
            'data' => $book->load('authors', 'categories'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Delete(
     *     path="/api/book/{id}",
     *     summary="Delete a book by ID",
     *     operationId="destroyBook",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the book to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Book deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Book deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Book not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Book not found")
     *         )
     *     )
     * )
     */
    public function destroy(Book $book)
    {
        $book->authors()->detach();
        $book->categories()->detach();
        $book->delete();
        return response()->noContent();
    }
}
