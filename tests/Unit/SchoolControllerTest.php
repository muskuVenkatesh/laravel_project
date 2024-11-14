<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Schools;
use App\Models\Branches;
class SchoolControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_school()
    {
        // Define the data for a new school
        $schoolData = [
            'name' => 'Test School',
            'address' => '123 Test St.',
            'school_code' => 'TESTSD',
            'city' => 'HYD',
            'dist'=> 'Telengaana',
            'state'=> 'test_state',
            'pin' => '713325',

            'branch_code'=> 'TESTBRNH',
            'branch_name' => 'Test School Branch',
            'branch_address' => '123 Test St.',
            'branch_email' => 'test@school.com',
            'branch_phone' => '1234567890',
            'branch_city' => 'HYD',
            'branch_dist'=> 'Telengaana',
            'branch_state'=> 'test_state',
            'branch_pin' => '713325'
        ];
        $school = Schools::create([
            'name' => $schoolData['name'],
            'address' => $schoolData['address'],
            'school_code' => $schoolData['school_code'],
            'city' => $schoolData['city'],
            'dist' => $schoolData['dist'],
            'state' => $schoolData['state'],
            'pin' => $schoolData['pin']
        ]);

        // Create the branch
        $branch = Branches::create([
            'school_id' => $school->id,
            'code' => $schoolData['branch_code'],
            'name' => $schoolData['branch_name'],
            'address' => $schoolData['branch_address'],
            'email' => $schoolData['branch_email'],
            'phone' => $schoolData['branch_phone'],
            'city' => $schoolData['branch_city'],
            'dist' => $schoolData['branch_dist'],
            'state' => $schoolData['branch_state'],
            'pin' => $schoolData['branch_pin']
        ]);

        $this->assertDatabaseHas('schools', $schoolData);
    }
}
