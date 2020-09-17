<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Customer;

// Testing Done without a factory

class BookDatabaseTest extends TestCase
{
    use RefreshDatabase;

    private function customerData(array $args = null)
    {
        $customer = Customer::factory()->make($args);
        return $customer->attributesToArray();
    }

    private function bookData(array $args = null)
    {
        $book = Book::factory()->make($args);
        return $book->attributesToArray();
    }  
     /** 
      * @test 
      * 
     */
     public function a_title_is_required()
     {
        $response = $this->post('books',$this->bookData([ 'title' => '']));
        
        $response->assertSessionHasErrors('title');
     }

    /** 
      * @test 
      * 
     */
    public function an_author_is_required()
    {
        $response = $this->post('books',$this->bookData([ 'author' => '']));
       
       $response->assertSessionHasErrors('author');
    }
    /** 
    * @test 
    * 
    */
      public function a_book_can_be_added()
      {
        $response = $this->post('books',$this->bookData());          
        $response->assertOK();
        $this->assertDatabaseCount('books', 1);  
      }
    /** 
      * @test 
      * 
     */
    public function a_book_title_can_be_updated()
    {
        $bookData = $this->bookData();
        $this->post('books', $bookData);


        $book = Book::first(); 
        $this->patch('books/'.$book->id, [ 'title' => 'new title']);
        $bookData['title'] = 'new title';

        $this->assertDatabaseHas('books', $bookData);       
    }

    /** 
    * @test 
    * 
    */
    public function a_book_author_can_be_updated()
    {
        $bookData = $this->bookData();
        $this->post('books', $bookData);


        $book = Book::first(); 
        $this->patch('books/'.$book->id, [ 'author' => 'new author']);
        $bookData['author'] = 'new author';

        $this->assertDatabaseHas('books', $bookData);       
    }
    
    /** 
      * @test 
      * 
     */
    public function can_delete_book()
    {
        // Create an instance of 'book'
        $this->post('books',$this->bookData());  
        // Fetch ID of created instance
        $book = Book::first();

        // Delete the book

        $this->delete('books/'.$book->id);
        $this->assertDeleted($book);
    }

    public function cannot_directly_affect_book_relationship()
    {
        //This is a security check, if you would be able to pass the customer_id freely with a random request, you could in theory use an online form API 
        //to unasign all books you are lending. Expected behaviour is using the specific unassign functionality.
        $bookData = $this->bookData();
        $this->post('customers', $this->customerData());
        $this->post('books', $bookData); 

        $customer = Customer::first();
        $book = Book::first();

        $this->post('customers/'.$customer->id.'/books/'.$book->id);

        // All steps taken to asign a customer to a book.
        // Try and null 'customer_id'

        $this->update('books', [ 'customer_id' => '']);
        //update data should get verified and filter out customer_id
        $this->assertDatabaseHas('books', $bookData);   
    }
}
