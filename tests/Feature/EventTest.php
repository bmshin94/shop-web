<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 이벤트 목록 페이지 로드 테스트
     */
    public function test_event_list_page_displays_correctly(): void
    {
        $now = now();

        // 진행 중인 이벤트
        Event::factory()->create([
            'title' => '진행중 이벤트',
            'start_at' => $now->copy()->subDay(),
            'end_at' => $now->copy()->addDay(),
        ]);

        // 진행 예정 이벤트
        Event::factory()->create([
            'title' => '진행예정 이벤트',
            'start_at' => $now->copy()->addDay(),
        ]);

        // 종료된 이벤트
        Event::factory()->create([
            'title' => '종료된 이벤트',
            'start_at' => $now->copy()->subDays(10),
            'end_at' => $now->copy()->subDay(),
        ]);

        $response = $this->get(route('event.index'));

        $response->assertStatus(200);
        $response->assertSee('진행중 이벤트');
        $response->assertSee('진행예정 이벤트');
        $response->assertSee('종료된 이벤트');
        $response->assertViewHasAll(['ongoingEvents', 'upcomingEvents', 'endedEvents', 'winnerEvents', 'heroEvents', 'eventsData']);
    }

    /**
     * 응모형 이벤트 참여 성공 테스트
     */
    public function test_member_can_participate_in_application_event(): void
    {
        $member = Member::factory()->create();
        $event = Event::factory()->create([
            'type' => Event::TYPE_PARTICIPATION,
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
        ]);

        $response = $this->actingAs($member)
            ->postJson(route('event.participate.submit', $event));

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('event_participations', [
            'event_id' => $event->id,
            'member_id' => $member->id,
        ]);
    }

    /**
     * 중복 응모 방지 테스트
     */
    public function test_member_cannot_participate_twice_in_same_event(): void
    {
        $member = Member::factory()->create();
        $event = Event::factory()->create([
            'type' => Event::TYPE_PARTICIPATION,
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
        ]);

        // 첫 번째 응모
        $event->participations()->create(['member_id' => $member->id]);

        // 두 번째 응모 시도
        $response = $this->actingAs($member)
            ->postJson(route('event.participate.submit', $event));

        $response->assertStatus(400)
            ->assertJson(['message' => '이벤트 응모 완료']);
    }

    /**
     * 이벤트 응모 취소 테스트
     */
    public function test_member_can_cancel_participation(): void
    {
        $member = Member::factory()->create();
        $event = Event::factory()->create([
            'type' => Event::TYPE_PARTICIPATION,
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
        ]);

        // 응모 기록 생성
        $event->participations()->create(['member_id' => $member->id]);

        $response = $this->actingAs($member)
            ->deleteJson(route('event.participate.cancel', $event));

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('event_participations', [
            'event_id' => $event->id,
            'member_id' => $member->id,
        ]);
    }

    /**
     * 비로그인 사용자 응모 차단 테스트
     */
    public function test_guest_cannot_participate_in_event(): void
    {
        $event = Event::factory()->create(['type' => Event::TYPE_PARTICIPATION]);

        $response = $this->postJson(route('event.participate.submit', $event));

        $response->assertStatus(401);
    }

    /**
     * 일반형 이벤트 응모 차단 테스트
     */
    public function test_cannot_participate_in_general_type_event(): void
    {
        $member = Member::factory()->create();
        $event = Event::factory()->create(['type' => Event::TYPE_GENERAL]);

        $response = $this->actingAs($member)
            ->postJson(route('event.participate.submit', $event));

        $response->assertStatus(400)
            ->assertJson(['message' => '응모 가능한 이벤트 유형이 아닙니다.']);
    }
}
