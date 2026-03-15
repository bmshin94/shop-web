<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Ootd;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OotdStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 회원이 AJAX를 통해 OOTD를 성공적으로 등록할 수 있는지 테스트
     */
    public function test_member_can_store_ootd_via_ajax(): void
    {
        // 1. 준비: 스토리지 페이크 및 회원 생성 
        Storage::fake('public');
        $member = Member::factory()->create();
        
        // GD 라이브러리 없이도 가능한 일반 파일 생성 방식으로 변경! ️
        $file = UploadedFile::fake()->create('style.jpg', 100, 'image/jpeg');
        $content = '테스트용 스타일 설명입니다. #ActiveWomen';
        $instaUrl = 'https://www.instagram.com/p/DV576ndD844/';

        // 2. 실행: AJAX POST 요청 전송 
        $response = $this->actingAs($member)->postJson(route('ootd.store'), [
            'image_file' => $file,
            'content' => $content,
            'instagram_url' => $instaUrl,
        ]);

        // 3. 검증: 응답 및 DB 확인 
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => '등록되었습니다.',
            ]);

        $this->assertDatabaseHas('ootds', [
            'member_id' => $member->id,
            'content' => $content,
            'instagram_url' => $instaUrl,
        ]);

        // 파일이 실제로 저장되었는지 확인! 
        $ootd = Ootd::first();
        $storedPath = str_replace('/storage/', '', $ootd->image_url);
        Storage::disk('public')->assertExists($storedPath);
    }

    /**
     * 게스트는 OOTD를 등록할 수 없는지 테스트
     */
    public function test_guest_cannot_store_ootd(): void
    {
        // GD 없이 파일 생성 
        $file = UploadedFile::fake()->create('guest.jpg', 100, 'image/jpeg');

        $response = $this->postJson(route('ootd.store'), [
            'image_file' => $file,
            'content' => '게스트 글쓰기 시도',
        ]);

        $response->assertStatus(401); // Unauthorized
        $this->assertDatabaseCount('ootds', 0);
    }

    /**
     * 필수 입력값 누락 시 유효성 검사 테스트
     */
    public function test_ootd_store_validation(): void
    {
        $member = Member::factory()->create();

        // 이미지 없이 요청 시도 
        $response = $this->actingAs($member)->postJson(route('ootd.store'), [
            'content' => '이미지 없는 게시물',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image_file']);
    }
}
