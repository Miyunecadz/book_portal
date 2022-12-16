<?php

namespace App\Imports;

use App\Helpers\HumanNameFormatterHelper;
use App\Helpers\NameHelper;
use App\Models\Author;
use App\Models\Book;
use App\Models\EbookTransaction;
use App\Models\RejectedAuthor;
use App\Models\RejectedEbookTransaction;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EbookTransactionsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $name = $row['productauthors'];
        $name = (new HumanNameFormatterHelper)->parse($name);

        $author = Author::where('firstname', 'LIKE', NameHelper::normalize($name->FIRSTNAME) . "%")->where('lastname', 'LIKE', NameHelper::normalize($name->LASTNAME) . "%")->first();
    
        $date = Carbon::parse(Date::excelToDateTimeObject($row['transactiondatetime']));

        if (!empty($author)) {
            $book = Book::where('title', $row['producttitle'])->first();
            $book_id = 0;
            if (empty($book)) {
                $createbook = Book::create([
                    'title' => $row['producttitle'],
                    'isbn' =>   $row['mainproductid'] ,
                    'author_id'=>  $author->id,
                ]);

                $book_id = $createbook->id;
            }

            $ebookTransaction = EbookTransaction::where('author_id', $author->id)
                ->where('book_id', (empty($book_id) ? $book->id : $book_id))
                ->where('line_item_no', $row['lineitemid'])
                ->where('class_of_trade', $row['classoftradesale'])
                ->where('quantity', $row['netsoldquantity'])
                ->where('price', $row['unitprice'])
                ->where('proceeds', $row['proceedsofsaleduepublisher'])
                ->where('royalty', ($row['proceedsofsaleduepublisher'] / 2))
                ->where('month', $date->month)
                ->where('year', $date->year)->first();

           if (empty($ebookTransaction)) {
                return new EbookTransaction([
                    'author_id' => $author->id,
                    'book_id' => (empty($book_id) ? $book->id : $book_id),
                    'year' => $date->year,
                    'month' => $date->month,
                    'class_of_trade' => $row['classoftradesale'],
                    'line_item_no' => $row['lineitemid'],
                    'quantity' => $row['netsoldquantity'],
                    'price' => $row['unitprice'],
                    'proceeds' => $row['proceedsofsaleduepublisher'],
                    'royalty' => $row['proceedsofsaleduepublisher'] /2,
                ]);
           }

        } else {
            RejectedEbookTransaction::create([
                'author_name' => $row['productauthors'],
                'book_title' => $row['producttitle'],
                'year' => $date->year,
                'month' => $date->month,
                'class_of_trade' => $row['classoftradesale'],
                'line_item_no' => $row['lineitemid'],
                'quantity' => $row['netsoldquantity'],
                'price' => $row['unitprice'],
                'proceeds' => $row['proceedsofsaleduepublisher'],
                'royalty' => $row['proceedsofsaleduepublisher'] /2
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}
