<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactTest extends TestCase
{

    public function test_contact_page_loads(): void
    {
        $this->get(route('contact'))->assertOk();
    }

    public function test_contact_submit_stores_message(): void
    {
        $this->post(route('contact.submit'), [
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'subject' => 'Help needed',
            'message' => 'I need assistance with my order.',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'alice@example.com',
            'subject' => 'Help needed',
        ]);
    }

    public function test_contact_submit_validates_required_fields(): void
    {
        $this->post(route('contact.submit'), [])
            ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }

    public function test_contact_submit_validates_email_format(): void
    {
        $this->post(route('contact.submit'), [
            'name' => 'Bob',
            'email' => 'not-an-email',
            'subject' => 'Test',
            'message' => 'Test message body',
        ])->assertSessionHasErrors('email');
    }

    public function test_contact_submit_validates_message_max_length(): void
    {
        $this->post(route('contact.submit'), [
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'subject' => 'Test',
            'message' => str_repeat('x', 5001),
        ])->assertSessionHasErrors('message');
    }
}
