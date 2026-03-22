<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromotionMenuTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_event_management_page_is_accessible(): void
    {
        $response = $this->get(route('admin.events.index'));

        $response->assertOk();
        $response->assertSee('이벤트 관리');
    }

    /** @test */
    public function admin_exhibition_management_page_is_accessible(): void
    {
        $response = $this->get(route('admin.exhibitions.index'));

        $response->assertOk();
        $response->assertSee('기획전 관리');
    }

    /** @test */
    public function admin_member_management_page_is_accessible(): void
    {
        $response = $this->get(route('admin.members.index'));

        $response->assertOk();
        $response->assertSee('회원 관리');
    }

    /** @test */
    public function admin_operator_management_page_is_accessible(): void
    {
        $response = $this->get(route('admin.operators.index'));

        $response->assertOk();
        $response->assertSee('운영자 관리');
    }

    /** @test */
    public function admin_settings_page_is_accessible(): void
    {
        $response = $this->get(route('admin.settings.index'));

        $response->assertOk();
        $response->assertSee('기본 설정');
    }
}
