<?php

namespace Tests\Feature\Admin;

use App\Models\Member;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MemberManagementTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function admin_can_filter_members_by_search_and_status(): void
    {
        $activeStatus = Member::STATUSES[0] ?? '활성';
        $suspendedStatus = Member::STATUSES[2] ?? '정지';

        $targetMember = Member::factory()->create([
            'name' => 'Filter Target',
            'email' => 'member-filter-1@example.com',
            'status' => $activeStatus,
        ]);

        $otherMember = Member::factory()->create([
            'name' => 'Filter Hidden',
            'email' => 'member-filter-2@example.com',
            'status' => $suspendedStatus,
        ]);

        $response = $this->get(route('admin.members.index', [
            'search' => 'Filter Target',
            'status' => $activeStatus,
        ]));

        $response->assertOk();
        $response->assertSee($targetMember->email);
        $response->assertDontSee($otherMember->email);
    }

    /** @test */
    public function admin_can_view_member_detail(): void
    {
        $member = Member::factory()->create();

        $response = $this->get(route('admin.members.show', $member));

        $response->assertOk();
        $response->assertSee($member->email);
    }

    /** @test */
    public function admin_can_update_member_information(): void
    {
        $dormantStatus = Member::STATUSES[1] ?? '휴면';

        $member = Member::factory()->create([
            'status' => Member::STATUSES[0] ?? '활성',
            'phone' => '010-0000-0000',
        ]);

        $response = $this->patch(route('admin.members.update', $member), [
            'name' => 'Updated Member',
            'email' => 'member-updated@example.com',
            'phone' => '010-9999-8888',
            'status' => $dormantStatus,
        ]);

        $response->assertRedirect(route('admin.members.show', $member));
        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'name' => 'Updated Member',
            'email' => 'member-updated@example.com',
            'phone' => '010-9999-8888',
            'status' => $dormantStatus,
        ]);
    }

    /** @test */
    public function admin_can_soft_delete_member(): void
    {
        $member = Member::factory()->create([
            'email' => 'member-soft-delete@example.com',
        ]);

        $response = $this->delete(route('admin.members.destroy', $member));

        $response->assertRedirect(route('admin.members.index'));
        $this->assertSoftDeleted('members', [
            'id' => $member->id,
        ]);
        $this->assertNull(Member::query()->find($member->id));
        $this->assertNotNull(Member::query()->withTrashed()->find($member->id));
    }

    /** @test */
    public function trash_page_only_shows_soft_deleted_members(): void
    {
        $activeMember = Member::factory()->create([
            'email' => 'member-active@example.com',
        ]);

        $trashedMember = Member::factory()->create([
            'email' => 'member-trashed@example.com',
        ]);
        $trashedMember->delete();

        $response = $this->get(route('admin.members.trash'));

        $response->assertOk();
        $response->assertSee($trashedMember->email);
        $response->assertDontSee($activeMember->email);
    }

    /** @test */
    public function admin_can_restore_soft_deleted_member(): void
    {
        $member = Member::factory()->create([
            'email' => 'member-restore@example.com',
        ]);
        $member->delete();

        $response = $this->patch(route('admin.members.restore', $member));

        $response->assertRedirect(route('admin.members.trash'));
        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'deleted_at' => null,
        ]);
        $this->assertNotNull(Member::query()->find($member->id));
    }

    /** @test */
    public function admin_can_force_delete_soft_deleted_member(): void
    {
        $member = Member::factory()->create([
            'email' => 'member-force-delete@example.com',
        ]);
        $member->delete();

        $response = $this->delete(route('admin.members.force-destroy', $member));

        $response->assertRedirect(route('admin.members.trash'));
        $this->assertDatabaseMissing('members', [
            'id' => $member->id,
        ]);
        $this->assertNull(Member::query()->withTrashed()->find($member->id));
    }
}
