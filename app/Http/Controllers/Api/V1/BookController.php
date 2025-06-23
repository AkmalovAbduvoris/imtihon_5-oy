<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/books",
     *     summary="Get all books",
     *     tags={"Books"},
     *     @OA\Response(
     *         response=200,
     *         description="List of books"
     *     )
     * )
     */
    public function index()
    {
        $books = cache()->rememberForever('books', function () {
            return Book::all();
        });
        return response()->json($books);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/books/{id}",
     *     summary="Get a book by ID",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the book",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Single book"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Book not found"
     *     )
     * )
     */
    public function show(Book $book)
    {
        return response()->json($book);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/books",
     *     summary="Create a new book",
     *     tags={"Books"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", maxLength=255, example="My Book")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Book created"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $book = Book::create($request->all());

        return response()->json($book, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/books/{id}",
     *     summary="Update a book",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the book",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", maxLength=255, example="Updated Title")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book updated"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
        ]);

        $book->update($request->all());

        return response()->json($book);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/books/{id}",
     *     summary="Delete a book",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the book",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Delete failed"
     *     )
     * )
     */
    public function destroy(Book $book)
    {
        try {
            $book->delete();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Book could not be deleted'], 500);
        }
        return response()->json(['message' => 'Book deleted successfully']);
    }
}
