<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\Registration;
use App\Models\Unit;
use App\Models\Upload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OwnershipTest extends TestCase
{
    use RefreshDatabase;

    private function createUnit(bool $isAdmin = false): Unit
    {
        return Unit::create([
            'level' => 'Wira',
            'school_name' => fake()->company(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'postal_code' => fake()->numerify('#####'),
            'coach_name' => fake()->name(),
            'trainer_name' => fake()->name(),
            'commander_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'username' => fake()->unique()->userName(),
            'password' => bcrypt('password'),
            'status' => 'verified',
            'is_admin' => $isAdmin,
        ]);
    }

    private function createCompetition(
        string $name = 'Test Competition',
        float $fee = 100000
    ): Competition {
        return Competition::create([
            'name' => $name,
            'category' => 'Tes',
            'type' => 'cabang',
            'competition_category' => 'bounty',
            'fee' => $fee,
            'description' => 'Competition untuk pengujian ownership.',
            'team_size' => 1,
            'max_teams' => 1,
            'requires_upload' => false,
            'upload_type' => null,
        ]);
    }

    private function createPendingRegistration(
        Unit $unit,
        Competition $competition,
        string $registrationCode
    ): Registration {
        return Registration::create([
            'unit_id' => $unit->id,
            'competition_id' => $competition->id,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_type' => null,
            'amount_paid' => null,
            'registration_code' => $registrationCode,
        ]);
    }

    private function createPaidRegistration(
        Unit $unit,
        Competition $competition,
        string $registrationCode
    ): Registration {
        return Registration::create([
            'unit_id' => $unit->id,
            'competition_id' => $competition->id,
            'status' => 'paid',
            'payment_status' => 'paid',
            'payment_type' => 'lunas',
            'amount_paid' => 100000,
            'registration_code' => $registrationCode,
        ]);
    }

    private function createVerifiedDaftarUlang(Unit $unit): Upload
    {
        return Upload::create([
            'unit_id' => $unit->id,
            'registration_id' => null,
            'type' => 'daftar_ulang',
            'category' => 'Daftar Ulang',
            'file_path' => 'testing/daftar-ulang.pdf',
            'status' => 'verified',
        ]);
    }

    public function test_guest_cannot_access_participant_cards(): void
    {
        $response = $this->get(route('participant-cards.index'));

        $response->assertRedirect(route('login'));

        $response->assertSessionHas(
            'error',
            'Silakan login terlebih dahulu.'
        );
    }

    public function test_guest_cannot_access_participant_card_by_code(): void
    {
        $response = $this->get(
            route('participant-cards.show', [
                'registrationCode' => 'WGPS-999',
            ])
        );

        $response->assertRedirect(route('login'));

        $response->assertSessionHas(
            'error',
            'Silakan login terlebih dahulu.'
        );
    }

    public function test_guest_cannot_access_participant_card_png(): void
    {
        $response = $this->get(
            route('participant-cards.png', [
                'registrationCode' => 'WGPS-999',
            ])
        );

        $response->assertRedirect(route('login'));

        $response->assertSessionHas(
            'error',
            'Silakan login terlebih dahulu.'
        );
    }

    public function test_guest_cannot_access_participant_card_pdf(): void
    {
        $response = $this->get(route('participant-cards.pdf'));

        $response->assertRedirect(route('login'));

        $response->assertSessionHas(
            'error',
            'Silakan login terlebih dahulu.'
        );
    }

    public function test_guest_cannot_access_profile(): void
    {
        $response = $this->get(route('profile.index'));

        $response->assertRedirect(route('login'));

        $response->assertSessionHas(
            'error',
            'Silakan login terlebih dahulu.'
        );
    }

    public function test_guest_cannot_access_status(): void
    {
        $response = $this->get(route('status.index'));

        $response->assertRedirect(route('login'));

        $response->assertSessionHas(
            'error',
            'Silakan login terlebih dahulu.'
        );
    }

    public function test_unit_cannot_access_another_units_participant_card(): void
    {
        $unitA = $this->createUnit();
        $unitB = $this->createUnit();

        $this->createVerifiedDaftarUlang($unitA);

        $competition = $this->createCompetition();

        $registration = $this->createPaidRegistration(
            $unitB,
            $competition,
            'WOWN-001'
        );

        session([
            'unit_id' => $unitA->id,
        ]);

        $response = $this->get(
            route('participant-cards.show', [
                'registrationCode' => $registration->registration_code,
            ])
        );

        $response->assertNotFound();
    }

    public function test_unit_cannot_download_another_units_participant_card_png(): void
    {
        $unitA = $this->createUnit();
        $unitB = $this->createUnit();

        $this->createVerifiedDaftarUlang($unitA);

        $competition = $this->createCompetition();

        $registration = $this->createPaidRegistration(
            $unitB,
            $competition,
            'WOWN-002'
        );

        session([
            'unit_id' => $unitA->id,
        ]);

        $response = $this->get(
            route('participant-cards.png', [
                'registrationCode' => $registration->registration_code,
            ])
        );

        $response->assertNotFound();
    }

    public function test_unit_cannot_download_another_units_participant_card_pdf_through_profile(): void
    {
        $unitA = $this->createUnit();
        $unitB = $this->createUnit();

        $competition = $this->createCompetition();

        $registration = $this->createPaidRegistration(
            $unitB,
            $competition,
            'WOWN-003'
        );

        session([
            'unit_id' => $unitA->id,
        ]);

        $response = $this->get(
            route('card.download', [
                'registration' => $registration->id,
            ])
        );

        $response->assertNotFound();
    }

    public function test_unit_cannot_upload_lomba_to_another_units_registration(): void
    {
        $unitA = $this->createUnit();
        $unitB = $this->createUnit();

        $competition = $this->createCompetition();

        $competition->update([
            'requires_upload' => true,
            'upload_type' => 'link',
        ]);

        $registration = $this->createPaidRegistration(
            $unitB,
            $competition,
            'WOWN-004'
        );

        $registration->update([
            'payment_status' => 'verified',
        ]);

        session([
            'unit_id' => $unitA->id,
        ]);

        $response = $this->post(
            route('profile.lomba.upload', [
                'registration' => $registration->id,
            ]),
            [
                'link' => 'https://example.com/test-karya',
            ]
        );

        $response->assertNotFound();

        $this->assertDatabaseMissing('uploads', [
            'registration_id' => $registration->id,
            'unit_id' => $unitA->id,
        ]);
    }

    public function test_unit_cannot_view_another_units_document(): void
    {
        $unitA = $this->createUnit();
        $unitB = $this->createUnit();

        $upload = Upload::create([
            'unit_id' => $unitB->id,
            'registration_id' => null,
            'type' => 'daftar_ulang',
            'category' => 'Daftar Ulang',
            'file_path' => 'testing/document.pdf',
            'status' => 'verified',
        ]);

        session([
            'unit_id' => $unitA->id,
        ]);

        $response = $this->get(
            route('documents.view', [
                'upload' => $upload->id,
            ])
        );

        $response->assertForbidden();

        $response->assertSeeText(
            'Anda tidak memiliki akses ke dokumen ini.'
        );
    }

    public function test_unit_cannot_make_payment_for_another_units_registration(): void
    {
        Storage::fake('google_pembayaran');
        Mail::fake();

        $unitA = $this->createUnit();
        $unitB = $this->createUnit();

        $competitionA = $this->createCompetition(
            'Test Competition A',
            100000
        );

        $competitionB = $this->createCompetition(
            'Test Competition B',
            150000
        );

        $registrationA = $this->createPendingRegistration(
            $unitA,
            $competitionA,
            'WOWN-005'
        );

        $registrationB = $this->createPendingRegistration(
            $unitB,
            $competitionB,
            'WOWN-006'
        );

        session([
            'unit_id' => $unitA->id,
        ]);

        $file = UploadedFile::fake()->create(
            'bukti-pembayaran.pdf',
            100,
            'application/pdf'
        );

        $response = $this->post(
            route('payment.storeBatch'),
            [
                'registration_ids' => [
                    $registrationB->id,
                ],
                'payment_type' => 'lunas',
                'file' => $file,
            ]
        );

        $response->assertSessionHas(
            'success',
            'Bukti pembayaran berhasil diunggah untuk seluruh lomba yang belum dibayar. Silakan menunggu verifikasi admin.'
        );

        $this->assertDatabaseHas('registrations', [
            'id' => $registrationA->id,
            'unit_id' => $unitA->id,
            'payment_status' => 'paid',
            'payment_type' => 'lunas',
        ]);

        $this->assertDatabaseHas('registrations', [
            'id' => $registrationB->id,
            'unit_id' => $unitB->id,
            'payment_status' => 'pending',
            'payment_type' => null,
        ]);

        $this->assertDatabaseHas('uploads', [
            'unit_id' => $unitA->id,
            'registration_id' => $registrationA->id,
            'type' => 'pembayaran',
            'status' => 'pending',
        ]);

        $this->assertDatabaseMissing('uploads', [
            'unit_id' => $unitB->id,
            'registration_id' => $registrationB->id,
            'type' => 'pembayaran',
        ]);
    }

    public function test_status_only_contains_current_units_registrations(): void
    {
        $unitA = $this->createUnit();
        $unitB = $this->createUnit();

        $competitionA = $this->createCompetition(
            'Competition Unit A',
            100000
        );

        $competitionB = $this->createCompetition(
            'Competition Unit B',
            200000
        );

        $registrationA = $this->createPendingRegistration(
            $unitA,
            $competitionA,
            'WSTAT-001'
        );

        $registrationB = $this->createPendingRegistration(
            $unitB,
            $competitionB,
            'WSTAT-002'
        );

        session([
            'unit_id' => $unitA->id,
        ]);

        $response = $this->get(route('status.index'));

        $response->assertOk();

        $response->assertViewHas('unit', function ($unit) use ($unitA) {
            return $unit->id === $unitA->id;
        });

        $response->assertViewHas('registrations', function ($registrations) use (
            $registrationA,
            $registrationB
        ) {
            return $registrations->contains('id', $registrationA->id)
                && !$registrations->contains('id', $registrationB->id);
        });
    }
}