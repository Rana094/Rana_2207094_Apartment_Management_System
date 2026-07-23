<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentFileAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_uploaded_word_document_can_be_downloaded_by_resident_and_manager(): void
    {
        Storage::fake('private_uploads');

        $resident = $this->approvedUser('resident', 'resident@example.com');
        $manager = $this->approvedUser('manager', 'manager@example.com');
        $otherResident = $this->approvedUser('resident', 'other@example.com');

        $file = UploadedFile::fake()->create(
            'lease.docx',
            12,
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        );

        $this->actingAs($resident)
            ->post(route('resident.documents.store'), [
                'title' => 'Lease Agreement',
                'type' => 'lease_agreement',
                'document_file' => $file,
            ])
            ->assertRedirect(route('resident.documents'));

        $document = Document::firstOrFail();

        Storage::disk('private_uploads')->assertExists($document->file_path);

        $this->actingAs($resident)
            ->get(route('files.documents.show', $document))
            ->assertOk()
            ->assertDownload('Lease Agreement.docx');

        $this->actingAs($manager)
            ->get(route('files.documents.show', $document))
            ->assertOk()
            ->assertDownload('Lease Agreement.docx');

        $this->actingAs($otherResident)
            ->get(route('files.documents.show', $document))
            ->assertForbidden();
    }

    private function approvedUser(string $role, string $email): User
    {
        return User::create([
            'name' => ucfirst($role),
            'email' => $email,
            'phone' => '+880 1700 000000',
            'password' => Hash::make('password'),
            'role' => $role,
            'status' => 'approved',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);
    }
}
