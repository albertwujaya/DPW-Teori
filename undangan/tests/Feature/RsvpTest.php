<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Rsvp;

class RsvpTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful RSVP submission.
     */
    public function test_guest_can_submit_rsvp_form_successfully(): void
    {
        $response = $this->post('/rsvp', [
            'name' => 'Alice Margatroid',
            'attendance' => 'ya',
            'guests' => 3,
            'notes' => 'Congratulations!',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Konfirmasi kehadiran berhasil dikirim.'
                 ]);

        // Verify the database record exists
        $this->assertDatabaseHas('rsvp', [
            'nama' => 'Alice Margatroid',
            'kehadiran' => 'hadir',
            'jumlah_tamu' => 3,
            'ucapan' => 'Congratulations!',
        ]);
    }

    /**
     * Test RSVP validation fails with invalid data.
     */
    public function test_rsvp_form_validation_fails_on_invalid_data(): void
    {
        $response = $this->post('/rsvp', [
            'name' => '', // Required field
            'attendance' => 'maybe', // Invalid enum
        ]);

        $response->assertStatus(302); // Redirects back on validation failure
    }
}
