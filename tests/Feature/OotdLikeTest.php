<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Ootd;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OotdLikeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 회원이 OOTD에 좋아요를 토글할 수 있는지 테스트
     */
    public function test_member_can_toggle_like_on_ootd(): void
    {
        // 1. 회원과 OOTD 생성
        $member = Member::factory()->create();
        $ootd = Ootd::create([
            'member_id' => $member->id,
            'image_url' => 'https://example.com/test.jpg',
            'content' => '테스트 게시물',
            'likes_count' => 0,
            'is_visible' => true,
        ]);

        // 2. 좋아요 누르기 (Attached)
        $response = $this->actingAs($member)->postJson(route('ootd.like', $ootd));

        $response->assertStatus(200)
            ->assertJson(['liked' => true, 'likes_count' => 1]);
        
        $this->assertDatabaseHas('ootd_likes', [
            'member_id' => $member->id,
            'ootd_id' => $ootd->id,
        ]);
        $this->assertEquals(1, $ootd->refresh()->likes_count);

        // 3. 좋아요 취소하기 (Detached)
        $response = $this->actingAs($member)->postJson(route('ootd.like', $ootd));

        $response->assertStatus(200)
            ->assertJson(['liked' => false, 'likes_count' => 0]);
            
        $this->assertDatabaseMissing('ootd_likes', [
            'member_id' => $member->id,
            'ootd_id' => $ootd->id,
        ]);
        $this->assertEquals(0, $ootd->refresh()->likes_count);
    }

    /**
     * 게스트(비로그인)는 좋아요를 누를 수 없는지 테스트
     */
    public function test_guest_cannot_like_ootd(): void
    {
        $member = Member::factory()->create();
        $ootd = Ootd::create([
            'member_id' => $member->id,
            'image_url' => 'https://example.com/test.jpg',
            'content' => '테스트 게시물',
            'likes_count' => 0,
            'is_visible' => true,
        ]);

        $response = $this->postJson(route('ootd.like', $ootd));

        $response->assertStatus(401);
        $this->assertDatabaseCount('ootd_likes', 0);
    }
}
