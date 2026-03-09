<?php

namespace Tests\Feature\Admin;

use App\Models\Operator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OperatorManagementTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function admin_can_filter_operators_by_search_and_status(): void
    {
        $activeStatus = Operator::STATUSES[0] ?? '활성';
        $suspendedStatus = Operator::STATUSES[2] ?? '정지';

        $targetOperator = Operator::factory()->create([
            'name' => 'Operator Target',
            'email' => 'operator-filter-1@example.com',
            'status' => $activeStatus,
        ]);

        $otherOperator = Operator::factory()->create([
            'name' => 'Operator Hidden',
            'email' => 'operator-filter-2@example.com',
            'status' => $suspendedStatus,
        ]);

        $response = $this->get(route('admin.operators.index', [
            'search' => 'Operator Target',
            'status' => $activeStatus,
        ]));

        $response->assertOk();
        $response->assertSee($targetOperator->email);
        $response->assertDontSee($otherOperator->email);
    }

    /** @test */
    public function admin_can_view_operator_create_page(): void
    {
        $response = $this->get(route('admin.operators.create'));

        $response->assertOk();
        $response->assertSee('운영자 등록');
    }

    /** @test */
    public function admin_can_create_operator_with_menu_permissions(): void
    {
        $activeStatus = Operator::STATUSES[0] ?? '활성';

        $response = $this->post(route('admin.operators.store'), [
            'name' => 'New Operator',
            'email' => 'new-operator@example.com',
            'phone' => '010-1234-5678',
            'status' => $activeStatus,
            'menu_permissions_submitted' => '1',
            'menu_permissions' => ['dashboard', 'events'],
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ]);

        $operator = Operator::query()->where('email', 'new-operator@example.com')->first();

        $this->assertNotNull($operator);
        $this->assertTrue(Hash::check('password1234', (string) $operator->password));
        $this->assertSame(['dashboard', 'events'], $operator->menu_permissions);
        $response->assertRedirect(route('admin.operators.show', $operator));
    }

    /** @test */
    public function admin_can_view_operator_detail(): void
    {
        $operator = Operator::factory()->create();

        $response = $this->get(route('admin.operators.show', $operator));

        $response->assertOk();
        $response->assertSee($operator->email);
    }

    /** @test */
    public function admin_can_update_operator_information_password_and_menu_permissions(): void
    {
        $dormantStatus = Operator::STATUSES[1] ?? '휴면';

        $operator = Operator::factory()->create([
            'status' => Operator::STATUSES[0] ?? '활성',
            'phone' => '010-0000-0000',
            'password' => Hash::make('old-password'),
            'menu_permissions' => ['dashboard', 'members'],
        ]);

        $response = $this->patch(route('admin.operators.update', $operator), [
            'name' => 'Updated Operator',
            'email' => 'operator-updated@example.com',
            'phone' => '010-9999-8888',
            'status' => $dormantStatus,
            'menu_permissions_submitted' => '1',
            'menu_permissions' => ['dashboard', 'orders'],
            'password' => 'new-password-1234',
            'password_confirmation' => 'new-password-1234',
        ]);

        $response->assertRedirect(route('admin.operators.show', $operator));
        $this->assertDatabaseHas('operators', [
            'id' => $operator->id,
            'name' => 'Updated Operator',
            'email' => 'operator-updated@example.com',
            'phone' => '010-9999-8888',
            'status' => $dormantStatus,
        ]);

        $operator->refresh();
        $this->assertTrue(Hash::check('new-password-1234', (string) $operator->password));
        $this->assertSame(['dashboard', 'orders'], $operator->menu_permissions);
    }

    /** @test */
    public function permission_middleware_blocks_disallowed_admin_menu(): void
    {
        $operator = Operator::factory()->create([
            'menu_permissions' => ['dashboard'],
        ]);

        $response = $this->withSession([
            'admin_operator_id' => $operator->id,
        ])->get(route('admin.events.index'));

        $response->assertForbidden();
    }

    /** @test */
    public function permission_middleware_allows_allowed_admin_menu(): void
    {
        $operator = Operator::factory()->create([
            'menu_permissions' => ['dashboard', 'events'],
        ]);

        $response = $this->withSession([
            'admin_operator_id' => $operator->id,
        ])->get(route('admin.events.index'));

        $response->assertOk();
    }

    /** @test */
    public function admin_can_soft_delete_operator(): void
    {
        $operator = Operator::factory()->create([
            'email' => 'operator-soft-delete@example.com',
        ]);

        $response = $this->delete(route('admin.operators.destroy', $operator));

        $response->assertRedirect(route('admin.operators.index'));
        $this->assertSoftDeleted('operators', [
            'id' => $operator->id,
        ]);
        $this->assertNull(Operator::query()->find($operator->id));
        $this->assertNotNull(Operator::query()->withTrashed()->find($operator->id));
    }

    /** @test */
    public function trash_page_only_shows_soft_deleted_operators(): void
    {
        $activeOperator = Operator::factory()->create([
            'email' => 'operator-active@example.com',
        ]);

        $trashedOperator = Operator::factory()->create([
            'email' => 'operator-trashed@example.com',
        ]);
        $trashedOperator->delete();

        $response = $this->get(route('admin.operators.trash'));

        $response->assertOk();
        $response->assertSee($trashedOperator->email);
        $response->assertDontSee($activeOperator->email);
    }

    /** @test */
    public function admin_can_restore_soft_deleted_operator(): void
    {
        $operator = Operator::factory()->create([
            'email' => 'operator-restore@example.com',
        ]);
        $operator->delete();

        $response = $this->patch(route('admin.operators.restore', $operator));

        $response->assertRedirect(route('admin.operators.trash'));
        $this->assertDatabaseHas('operators', [
            'id' => $operator->id,
            'deleted_at' => null,
        ]);
        $this->assertNotNull(Operator::query()->find($operator->id));
    }

    /** @test */
    public function admin_can_force_delete_soft_deleted_operator(): void
    {
        $operator = Operator::factory()->create([
            'email' => 'operator-force-delete@example.com',
        ]);
        $operator->delete();

        $response = $this->delete(route('admin.operators.force-destroy', $operator));

        $response->assertRedirect(route('admin.operators.trash'));
        $this->assertDatabaseMissing('operators', [
            'id' => $operator->id,
        ]);
        $this->assertNull(Operator::query()->withTrashed()->find($operator->id));
    }
}
