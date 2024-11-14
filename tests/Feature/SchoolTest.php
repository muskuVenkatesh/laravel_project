<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\models\Schools;

class SchoolTest extends TestCase
{
    // use RefreshDatabase;
    public function test_create_school_successfully(): void
    {

        $school = Schools::factory()->make();

        $data = [
            'name' => $school->name,
            'address' => $school->address,
            'school_code' => $school->school_code,
            'branch_dist' => $school->branch_dist,
            'branch_state' => $school->branch_state,
            'branch_pin' => $school->branch_pin,
            'branch_city' => $school->branch_city,
            'branch_name' => $school->branch_name,
            'school_id' => $school->id,
            'branch_address' => $school->branch_address,
            'branch_code' => $school->branch_code,
            'branch_phone' => $school->branch_phone,
            'branch_email' => $school->branch_email,
            'city' => $school->city,
            'dist' => $school->dist,
            'state' => $school->state,
            'pin' => $school->pin,
        ];

        $response = $this->post('/api/create_school', $data);


        $response->assertStatus(201);

        // $response->assertJson([
        //     'message' => 'School created successfully',
        //     'data' => [
        //         'name' => $data['name'],
        //         'address' => $data['address'],
        //         'school_code' => $data['school_code'],
        //         'branch_dist' => $data['branch_dist'],
        //         'branch_state' => $data['branch_state'],
        //         'branch_pin' => $data['branch_pin'],
        //         'branch_city' => $data['branch_city'],
        //         'branch_name' => $data['branch_name'],
        //         'school_id' => $data['school_id'],
        //         'branch_address' => $data['branch_address'],
        //         'branch_code' => $data['branch_code'],
        //         'branch_phone' => $data['branch_phone'],
        //         'branch_email' => $data['branch_email'],
        //         'city' => $data['city'],
        //         'dist' => $data['dist'],
        //         'state' => $data['state'],
        //         'pin' => $data['pin'],
        //     ]
        // ]);


        // $this->assertDatabaseHas('schools', [
        //     'name' => $data['name'],
        //     'address' => $data['address'],
        //     'school_code' => $data['school_code'],
        //     // Add more fields as necessary
        // ]);
    }



    public function test_get_single_school_successfully(): void
    {

        $response = $this->get('/api/edit_school/2');
        $response->assertStatus(201);

    }


    public function test_update_school_successfully(): void
    {

        $school = Schools::factory()->make();


        $data = [
            'school_id' => $school->id,
            'name' => 'Updated School Name',
            'address' => 'Updated Address',
            'school_code' => 'UPDATEDCODE',
            'branch_dist' => 'Updated District',
            'branch_state' => 'Updated State',
            'branch_pin' => 123456,
            'branch_city' => 'Updated City',
            'branch_name' => 'Updated Branch Name',
            'branch_address' => 'Updated Branch Address',
            'branch_code' => 'UPDATEDBRANCHCODE',
            'branch_phone' => 1234567890,
            'branch_email' => 'updatedbranch@example.com',
            'city' => 'Updated City',
            'dist' => 'Updated District',
            'state' => 'Updated State',
            'pin' => 654321,
        ];


        $response = $this->post("/api/update_school", $data);

        // Assert the response status
        $response->assertStatus(200);

        // Assert the response structure
        $response->assertJson([
            'message' => 'School updated successfully',
        ]);

        // Refresh the school instance to get updated data
        $school->refresh();

        // Assert the updated data in the database
        $this->assertEquals('Updated School Name', $school->name);
        $this->assertEquals('Updated Address', $school->address);
        $this->assertEquals('UPDATEDCODE', $school->school_code);
        $this->assertEquals('Updated District', $school->branch_dist);
        $this->assertEquals('Updated State', $school->branch_state);
        $this->assertEquals(123456, $school->branch_pin);
        $this->assertEquals('Updated City', $school->branch_city);
        $this->assertEquals('Updated Branch Name', $school->branch_name);
        $this->assertEquals('Updated Branch Address', $school->branch_address);
        $this->assertEquals('UPDATEDBRANCHCODE', $school->branch_code);
        $this->assertEquals(1234567890, $school->branch_phone);
        $this->assertEquals('updatedbranch@example.com', $school->branch_email);
        $this->assertEquals('Updated City', $school->city);
        $this->assertEquals('Updated District', $school->dist);
        $this->assertEquals('Updated State', $school->state);
        $this->assertEquals(654321, $school->pin);
    }



}
