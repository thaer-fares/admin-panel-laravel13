<?php

namespace Tests\Feature;

use App\Livewire\Profile\UpdateProfile;
use App\Livewire\Users\Index as UsersIndex;
use App\Models\User;
use App\Notifications\SystemNotification;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class UserEmailNotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->admin = User::factory()->create(['locale' => 'ar']);
        $this->admin->assignRole('Admin');
    }

    public function test_system_notification_is_sent_by_mail_and_database(): void
    {
        $channels = (new SystemNotification('t', 'b'))->via($this->admin);

        $this->assertSame(['database', 'mail'], $channels);
    }

    public function test_creating_a_user_emails_the_new_user_and_the_admins(): void
    {
        Notification::fake();

        Livewire::actingAs($this->admin)
            ->test(UsersIndex::class)
            ->call('openCreateModal')
            ->set('name', 'New Person')
            ->set('email', 'new.person@example.com')
            ->set('password', 'secret-pass')
            ->call('save')
            ->assertHasNoErrors();

        $newUser = User::where('email', 'new.person@example.com')->firstOrFail();

        Notification::assertSentTo($newUser, SystemNotification::class, function ($n, $channels) {
            return $n->title === 'Welcome to the control panel' && in_array('mail', $channels);
        });

        Notification::assertSentTo($this->admin, SystemNotification::class, function ($n, $channels) {
            return $n->title === 'New user created' && in_array('mail', $channels);
        });
    }

    public function test_mail_is_rendered_in_the_recipient_language(): void
    {
        // نفس طريقة Laravel: يبني الإيميل بلغة المستخدم المفضلة
        $this->app->setLocale($this->admin->preferredLocale());
        $mail = (new SystemNotification('Welcome to the control panel', 'Test email'))->toMail($this->admin);

        $this->assertSame('أهلاً بك في لوحة التحكم', $mail->subject);
    }

    public function test_user_is_still_saved_when_mail_sending_fails(): void
    {
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => '127.0.0.1',
            'mail.mailers.smtp.port' => 1, // لا يوجد سيرفر هنا
        ]);

        Livewire::actingAs($this->admin)
            ->test(UsersIndex::class)
            ->call('openCreateModal')
            ->set('name', 'Offline Person')
            ->set('email', 'offline@example.com')
            ->set('password', 'secret-pass')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee(__('The user was saved, but the email could not be sent. Check the mail settings (MAIL_*) in .env.'));

        $this->assertDatabaseHas('users', ['email' => 'offline@example.com']);
    }

    public function test_profile_test_email_button_sends_mail_to_current_user(): void
    {
        Notification::fake();

        Livewire::actingAs($this->admin)
            ->test(UpdateProfile::class)
            ->call('sendTestEmail');

        Notification::assertSentTo($this->admin, SystemNotification::class, function ($n, $channels) {
            return $n->title === 'Test email' && in_array('mail', $channels);
        });
    }
}
