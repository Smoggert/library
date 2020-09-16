<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;

// Testing Done without a factory

class BookDatabaseTest extends TestCase
{
    use RefreshDatabase;

     /** 
      * @test 
      * 
     */
     public function a_title_is_required()
     {
        $response = $this->post('/books', [
            'title' => '',
            'author' => 'test_author',
        ]);
        
        $response->assertSessionHasErrors('title');
     }

    /** 
      * @test 
      * 
     */
    public function an_author_is_required()
    {
       $response = $this->post('/books', [
           'title' => 'test_title',
           'author' => '',
       ]);
       
       $response->assertSessionHasErrors('author');
    }
    /** 
    * @test 
    * 
    */
      public function a_book_can_be_added()
      {
          $this->withoutExceptionHandling();
          $response = $this->post('/books', [
              'title' => 'test_title',
              'author' => 'test_author',
          ]);
          
          $response->assertOK();
          $this->assertDatabaseCount('books', 1);  
      }
    /** 
      * @test 
      * 
     */
    public function a_book_title_and_author_can_be_updated()
    {
        // Create an instance of 'book'
        $this->post('books', [
            'title' => 'test_title',
            'author' => 'test_author',
        ]);
        
        // Fetch ID of created instance
        $book = Book::first();
        
        // Update the author to foo
        $this->patch('books/'.$book->id,[
            'author' => 'different_test_author',
        ]);
        // Check if author actually changed
        $this->assertDatabaseHas('books', [
            'title' => 'test_title',
            'author' => 'different_test_author',

        ]);
        // update the title to foo
        $this->patch('books/'.$book->id,[
            'title' => 'different_test_title',
        ]);
        // check if title actually changed
        $this->assertDatabaseHas('books', [
            'title' => 'different_test_title',
            'author' => 'different_test_author',

        ]);
    }
        
    /** 
      * @test 
      * 
     */
    public function can_delete_book()
    {
        // Create an instance of 'book'
        $this->post('books', [
            'title' => 'test_title',
            'author' => 'test_author',
        ]);  
        // Fetch ID of created instance
        $book = Book::first();

        // Delete the book

        $this->delete('books/'.$book->id);
        $this->assertDeleted($book);
    }
}
