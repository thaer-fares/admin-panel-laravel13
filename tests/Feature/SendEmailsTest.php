<?php

namespace Tests\Feature;

use App\Livewire\Emails\Index as EmailsIndex;
use App\Mail\AdminMessageMail;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class SendEmailsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Admin');
    }

    public function test_admin_sees_send_emails_link_in_sidebar(): void
    {
        $this->actingAs($this->admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee(route('emails.index'));
    }

    public function test_users_without_permission_cannot_open_the_page(): void
    {
        $viewer = User::factory()->create();
        $viewer->assignRole('Viewer');

        $this->actingAs($viewer)->get(route('emails.index'))->assertForbidden();
        $this->actingAs($viewer)->get(route('dashboard'))->assertDontSee(route('emails.index'));
    }

    public function test_admin_can_send_email_to_all_active_users(): void
    {
        Mail::fake();

        $active = User::factory()->create();
        $inactive = User::factory()->create(['is_active' => false]);

        $this->actingAs($this->admin)->get(route('emails.index'))->assertOk();

        Livewire::actingAs($this->admin)
            ->test(EmailsIndex::class)
            ->set('subject', 'Hello')
            ->set('body', 'Body text')
            ->call('send')
            ->assertHasNoErrors();

        Mail::assertSent(AdminMessageMail::class, fn ($m) => $m->hasTo($active->email));
        Mail::assertSent(AdminMessageMail::class, fn ($m) => $m->hasTo($this->admin->email));
        Mail::assertNotSent(AdminMessageMail::class, fn ($m) => $m->hasTo($inactive->email));
        $this->assertDatabaseHas('sent_emails', ['subject' => 'Hello', 'recipients_count' => 2, 'failed_count' => 0]);
    }

    public function test_admin_can_send_email_to_a_role_or_specific_users(): void
    {
        Mail::fake();

        $editor = User::factory()->create();
        $editor->assignRole('Editor');
        $other = User::factory()->create();

        Livewire::actingAs($this->admin)
            ->test(EmailsIndex::class)
            ->set('audience', 'role')
            ->set('audienceRole', 'Editor')
            ->set('subject', 'To editors')
            ->set('body', 'x')
            ->call('send')
            ->assertHasNoErrors();

        Mail::assertSent(AdminMessageMail::class, 1);
        Mail::assertSent(AdminMessageMail::class, fn ($m) => $m->hasTo($editor->email));

        Livewire::actingAs($this->admin)
            ->test(EmailsIndex::class)
            ->set('audience', 'users')
            ->set('selectedUsers', [$other->id])
            ->set('subject', 'Just you')
            ->set('body', 'x')
            ->call('send')
            ->assertHasNoErrors();

        Mail::assertSent(AdminMessageMail::class, fn ($m) => $m->hasTo($other->email) && $m->mailSubject === 'Just you');
    }

    public function test_validation_requires_subject_and_body(): void
    {
        Livewire::actingAs($this->admin)
            ->test(EmailsIndex::class)
            ->call('send')
            ->assertHasErrors(['subject', 'body']);
    }

    public function test_mail_renders(): void
    {
        $html = (new AdminMessageMail($this->admin, 'S', "line1\nline2"))->render();

        $this->assertStringContainsString('line1', $html);
    }
}
