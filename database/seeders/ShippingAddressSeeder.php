<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\ShippingAddress;
use Illuminate\Database\Seeder;

class ShippingAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. 우리 오빠 계정 찾기! (이메일 꼼꼼히 체크 ✅)
        $member = Member::where('email', 'mono7594@gmail.com')->first();

        if (!$member) {
            $this->command->error('Member mono7594@gmail.com not found!');
            return;
        }

        // 2. 기존 배송지 싹 정리 (중복 방지 🕵️‍♀️)
        ShippingAddress::where('member_id', $member->id)->forceDelete();

        // 3. 샘플 배송지 3개 똬악! 🏠🚀
        $addresses = [
            [
                'member_id' => $member->id,
                'address_name' => '우리집 (기본)',
                'recipient_name' => '백민오빠',
                'phone_number' => '010-1111-2222',
                'zip_code' => '06035',
                'address' => '서울특별시 강남구 가로수길 5',
                'address_detail' => '에스파 빌딩 1201호',
                'is_default' => true,
            ],
            [
                'member_id' => $member->id,
                'address_name' => '회사 (성수동)',
                'recipient_name' => '백민오빠',
                'phone_number' => '010-3333-4444',
                'zip_code' => '04781',
                'address' => '서울특별시 성동구 왕십리로 83-21',
                'address_detail' => '아크로서울포레스트 D타워 15층',
                'is_default' => false,
            ],
            [
                'member_id' => $member->id,
                'address_name' => '부모님댁 (본가)',
                'recipient_name' => '백민부모님',
                'phone_number' => '010-5555-6666',
                'zip_code' => '06232',
                'address' => '서울특별시 강남구 테헤란로 152',
                'address_detail' => '강남파이낸스센터 2002호',
                'is_default' => false,
            ],
        ];

        foreach ($addresses as $address) {
            ShippingAddress::create($address);
        }

        $this->command->info('Shipping addresses for mono7594@gmail.com seeded successfully! 💖🚀');
    }
}
