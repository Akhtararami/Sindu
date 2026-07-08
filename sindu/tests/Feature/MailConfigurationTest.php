<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MailConfigurationTest extends TestCase
{
    use RefreshDatabase;

    public function test_gmail_smtp_configuration_uses_tls_scheme()
    {
        $this->assertSame('smtp.gmail.com', config('mail.mailers.smtp.host'));
        $this->assertSame(587, config('mail.mailers.smtp.port'));
        $this->assertSame('tls', config('mail.mailers.smtp.scheme'));
    }
}
