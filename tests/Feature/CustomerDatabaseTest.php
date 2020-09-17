<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Customer;
use App\Models\Book;

// Testing Done with a customer factory

class CustomerDatabaseTest extends TestCase
{
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


    public function a_customer_name_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $customerData = $this->customerData();
        $this->post('customers', $customerData);


        $customer = Customer::first(); 
        $this->patch('customers/'.$customer->id, [ 'name' => 'new_name']);

        $customerData['name'] = 'new_name';

        $this->assertDatabaseHas('customers', $customerData);
    }

            /**
     *  @test
     *  @return void
     */


    public function a_customer_address_can_be_updated()
    {
        $customerData = $this->customerData();
        $this->post('customers', $customerData);


        $customer = Customer::first(); 
        $this->patch('customers/'.$customer->id, [ 'address' => 'new_address']);

        $customerData['address'] = 'new_address';

        $this->assertDatabaseHas('customers', $customerData);
    }

                /**
     *  @test
     *  @return void
     */


    public function a_customer_email_can_be_updated()
    {
        $customerData = $this->customerData();
        $this->post('customers', $customerData);


        $customer = Customer::first(); 
        $this->patch('customers/'.$customer->id, [ 'email' => 'new_email@email.com']);

        $customerData['email'] = 'new_email@email.com';

        $this->assertDatabaseHas('customers', $customerData);
    }
     /**
     *  @test
     *  @return void
     */


    public function a_customer_date_of_birth_can_be_updated()
    {
        $customerData = $this->customerData();
        $this->post('customers', $customerData);


        $customer = Customer::first(); 
        $this->patch('customers/'.$customer->id, [ 'date_of_birth' => '1901-01-01']);

        $customerData['date_of_birth'] = '1901-01-01';

        $this->assertDatabaseHas('customers', $customerData);
    }

    /** 
    * @test 
    * 
    */
    public function a_book_can_be_assigned_to_a_customer()
    {
        $this->post('customers', $this->customerData());

        $customer = Customer::first();
        $this->post('books', $this->bookData());        
        $book = Book::first();

        $this->post('customers/'.$customer->id.'/books/'.$book->id);

        $this->assertTrue($customer->books->contains($book));
    }
}
