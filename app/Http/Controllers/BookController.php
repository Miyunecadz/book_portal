<?php

namespace App\Http\Controllers;
use App\Models\Author;
use App\Helpers\NameHelper;
use App\Imports\BooksImport;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    public function index()
    {
        return view('book.index', [
            'books' => Book::paginate(10),
            'bookSearch' => Book::all(),
            'authors' => Author::all(),
        ]);
    }

    public function search(Request $request)
    {
        
        $book = Book::where('title', $request->title)->paginate(10);
      
        if($request->title == 'all') {
            return redirect(route('book.index'));
        }else{
            return view('book.index', [
                'bookSearch' => Book::all(),
                'books' => $book,
                'authors' => Author :: all(),
            ]);
        }
       
        if ($request->author == 'all') {
            return view('book.index', [
                'books' => Book::paginate(10),
               'bookSearch' => Book::all(),
                'authors' => Author::all(),
         ]);
       }
       $author = Book::where('author_id', $request->author)->paginate(10);
      return view('book.index', [
             'books' => $author,
           // 'bookSearch' =>Book::where('author_id', $request->author)->paginate(10),
            'authors' => Author::where('id' , $request->author)->get(),
        ]);
       
    }

    public function importPage()
    {
        return view('book.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);
        ini_set('max_execution_time', -1);
        Excel::import(new BooksImport, $request->file('file')->store('temp'));
        ini_set('max_execution_time', 60);
        return back()->with('success', 'Successfully imported data');
    }

    public function create()
    {
        return view('book.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'title' => 'required|unique:books',
        ]);

        Book::create($request->all());

        return redirect(route('book.create'))->with('success', 'Book successfully added to database');
    }

    public function edit(Book $book)
    {
        $authors = Author::all();
        return view('book.edit', compact('book')) ->with('authors', $authors);
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'isbn' => 'required',
            'product_id' => 'required',
            'author'=>'required',
            'title' => 'required|' . Rule::unique('books')->ignore($book->id),
        ]);
        $book->update([
            'author_id' => $request->author,
            'title' => $request->title,
            'isbn' => $request->isbn,
            'product_id' => $request->product_id
        ]);

        return redirect()->route('book.edit', ['book' => $book])->with('success', 'Book successfully updated to the database');
    }

    public function delete(Book $book)
    {
        $book->delete();

        return redirect()->route('book.index')->with('success', 'Book has been successfully deleted from the database');
    }
}
