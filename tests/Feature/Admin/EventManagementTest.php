<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\Operator;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // 관리자 인증 처리
        $operator = Operator::factory()->create();
        $this->actingAs($operator, 'admin');
    }

    /**
     * 날짜 기반 가상 상태 검색 테스트
     */
    public function test_admin_can_filter_events_by_date_based_status(): void
    {
        $now = now();

        // 진행중인 이벤트
        $activeEvent = Event::factory()->create([
            'title' => '진행중 이벤트',
            'start_at' => $now->copy()->subDay(),
            'end_at' => $now->copy()->addDay(),
        ]);

        // 종료된 이벤트
        $endedEvent = Event::factory()->create([
            'title' => '종료된 이벤트',
            'end_at' => $now->copy()->subDay(),
        ]);

        // 1. '진행중' 필터링 테스트
        $response = $this->get(route('admin.events.index', ['status' => '진행중']));
        $response->assertOk();
        $response->assertSee($activeEvent->title);
        $response->assertDontSee($endedEvent->title);

        // 2. '종료' 필터링 테스트
        $response = $this->get(route('admin.events.index', ['status' => '종료']));
        $response->assertOk();
        $response->assertSee($endedEvent->title);
        $response->assertDontSee($activeEvent->title);
    }

    /**
     * 이벤트 등록 및 유형 저장 테스트
     */
    public function test_admin_can_create_event_with_type(): void
    {
        $response = $this->post(route('admin.events.store'), [
            'title' => '신규 응모 이벤트',
            'slug' => 'new-apply-event',
            'type' => Event::TYPE_PARTICIPATION, // 응모형
            'summary' => '이벤트 요약',
            'start_at' => now()->toDateTimeString(),
            'end_at' => now()->addDays(7)->toDateTimeString(),
        ]);

        $event = Event::query()->where('slug', 'new-apply-event')->first();

        $this->assertNotNull($event);
        $this->assertSame(Event::TYPE_PARTICIPATION, $event->type);
        $response->assertRedirect(route('admin.events.edit', $event));
    }

    /**
     * 응모자 중에서 당첨자 선정 토글 테스트
     */
    public function test_admin_can_toggle_participant_winner_status(): void
    {
        $event = Event::factory()->create(['type' => Event::TYPE_PARTICIPATION]);
        $member = Member::factory()->create();
        
        // 1. 당첨자로 선정
        $response = $this->patchJson(route('admin.events.participants.toggle-winner', [$event, $member]), [
            'is_winner' => true
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertDatabaseHas('event_winners', [
            'event_id' => $event->id,
            'member_id' => $member->id,
        ]);

        // 2. 당첨 취소
        $response = $this->patchJson(route('admin.events.participants.toggle-winner', [$event, $member]), [
            'is_winner' => false
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertDatabaseMissing('event_winners', [
            'event_id' => $event->id,
            'member_id' => $member->id,
        ]);
    }

    /**
     * 히어로 노출 토글(AJAX) 테스트
     */
    public function test_admin_can_toggle_hero_status(): void
    {
        $event = Event::factory()->create(['is_hero' => false]);

        $response = $this->patchJson(route('admin.events.toggle-hero', $event), [
            'is_hero' => true
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertTrue($event->fresh()->is_hero);
    }

    /**
     * 소프트 삭제 테스트
     */
    public function test_admin_can_soft_delete_event(): void
    {
        $event = Event::factory()->create();

        $response = $this->delete(route('admin.events.destroy', $event));

        $response->assertRedirect(route('admin.events.index'));
        $this->assertSoftDeleted('events', ['id' => $event->id]);
    }
}
