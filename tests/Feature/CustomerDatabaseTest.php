<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Customer;
use App\Models\Book;

// Testing Done with a customer factory
// Currently every more complicated test uses the provided classes to setup it's test. This is not ideal
// Database data should be present for example without using a certain method.
// Let's see how we can change this code to achieve just that !
// Update to more controller independant error fetching !!


class CustomerDatabaseTest extends TestCase
{
    #region INIT
    use RefreshDatabase;
    /**
     * 
     * @return array
     */
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

    #endregion
    #region post-tests
    /**
     *  @test
     *  @return void
     */

    public function a_name_is_required()
     {  
        $response = $this->post('customers', $this->customerData([ 'name' => '']));       
        $response->assertSessionHasErrors('name');
     }
     
            /**
     *  @test
     *  @return void
     */

    public function an_address_is_required()
    {
        $response = $this->post('customers', $this->customerData(['address' => '']));     
        $response->assertSessionHasErrors('address');
    }
    /**
     *  @test
     *  @return void
     */

    public function an_email_is_required()
    {
        $response = $this->post('customers', $this->customerData(['email' => '']));     
        $response->assertSessionHasErrors('email');
    }
    /**
     *  @test
     *  @return void
     */

    public function an_email_has_proper_format()
    {
        $response = $this->post('customers', $this->customerData(['email' => 'qdqzdqdqzdqdqdqzdqdqzd.com']));     
        $response->assertSessionHasErrors('email');
    }
    /**
     *  @test
     *  @return void
     */

    public function a_date_of_birth_is_required()
    {
        $response = $this->post('customers', $this->customerData(['date_of_birth' => '']));       
        $response->assertSessionHasErrors('date_of_birth');
    }
                    /**
     *  @test
     *  @return void
     */
    public function a_date_of_birth_has_yyyy_mm_dd_format()
    {
        $response = $this->post('customers', $this->customerData(['date_of_birth' => '052491']));
        $response->assertSessionHasErrors('date_of_birth');
    }
    /**
     *  @test
     *  @return void
     */

    public function a_customer_can_be_added()
    {
        $this->post('customers', $this->customerData());
        $this->assertDatabaseCount('customers', 1);  
    }
        /**
     *  @test
     *  @return void
     */

    #endregion
    #region update-tests
    public function a_customer_name_can_be_updated()
    {
        $args = ['name' => 'name'];

        $customer = Customer::factory()->create();   
        
        $this->patch('customers/'.$customer->id, $args);
        $this->assertDatabaseHas('customers', $args);
    }

            /**
     *  @test
     *  @return void
     */


    public function a_customer_address_can_be_updated()
    {
        $args = ['address' => 'new_address'];

        $customer = Customer::factory()->create();   

        $this->patch('customers/'.$customer->id, $args);
        $this->assertDatabaseHas('customers', $args);
    }

                /**
     *  @test
     *  @return void
     */


    public function a_customer_email_can_be_updated()
    {
        $args = ['email' => 'new_email_address@newmail.net'];

        $customer = Customer::factory()->create();   

        $this->patch('customers/'.$customer->id, $args);
        $this->assertDatabaseHas('customers', $args);
    }
     /**
     *  @test
     *  @return void
     */


    public function a_customer_date_of_birth_can_be_updated()
    {
        $args = ['date_of_birth' => '1908-05-22'];

        $customer = Customer::factory()->create();   

        $this->patch('customers/'.$customer->id, $args);
        $this->assertDatabaseHas('customers', $args);
    }
    #endregion

    /** 
    * @test 
    * 
    */
    public function a_book_can_be_assigned_to_a_customer()
    {
        $customer = Customer::factory()->create();  
        $book = Book::factory()->create();

        $this->post('books/'.$book->id.'/'.$customer->id);

        $this->assertTrue(Customer::find($customer->id)->books->contains($book));
    }
        /** 
    * @test 
    * 
    */
    public function a_book_can_be_unassigned_from_a_customer()
    {
        $this->withoutExceptionHandling();
        $customer = Customer::factory()->has(Book::factory()->count(1))->create();  
        $book = $customer->books->first();
        
        $this->delete('books/'.$book->id.'/'.$customer->id);

        $this->assertFalse(Customer::find($customer->id)->books->contains($book));
    }
}
