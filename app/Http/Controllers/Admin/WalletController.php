<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletVoucher;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use Symfony\Component\HttpFoundation\Response;

class WalletController extends Controller
{
    public function create_wallet($id_user) {
        $wallet = new Wallet();
        $wallet->id_user = $id_user;
        $wallet->save();
        if ($wallet->id) {
            return response()->json($wallet);
        }
        return response()->json(
            ["error_message" => "Không thể tạo ví người dùng"]
        , Response::HTTP_BAD_REQUEST);
    }

    public function topup_coin($id_user, $quantity_coin) {
        if (!isset($quantity_coin) || !is_int($quantity_coin) || $quantity_coin <= 0) {
            return response()->json([
                "error_message" => "Tham số coin không hợp lệ"
            ], Response::HTTP_BAD_REQUEST);
        }
        $wallet = get_wallet_via_user($id_user);
        $wallet->coin += $quantity_coin;
        $wallet->save();
        return $wallet;
    }

    public function topup_coin_api(Request $request) {
        $coin = $request->coin;
        $id_user = $request->id_user;
        if (!isset($id_user) || !is_int($id_user)) {
            return response()->json([
                "error_message" => "Tham số id_user không hợp lệ"
            ], Response::HTTP_BAD_REQUEST);
        }
        if (!isset($coin) || !is_int($coin) || $coin <= 0) {
            return response()->json([
                "error_message" => "Tham số coin không hợp lệ"
            ], Response::HTTP_BAD_REQUEST);
        }
        $wallet = $this->topup_coin($id_user, $coin);
        return response()->json($wallet);
    }

    public function add_voucher_to_wallet(Request $request, string $id_voucher) {
        $user = $request->id_user;
        if (is_array($user)) {
            foreach ($user as $user_item) {
                $wallet = get_wallet_via_user($user_item);
                $check_voucher = $this->check_voucher_in_wallet($wallet->id, $id_voucher);
                if ($check_voucher) {
                    continue;
                }
                $data = [
                    "id_wallet" => $wallet->id,
                    "id_voucher" => $id_voucher
                ];
                WalletVoucher::query()->create($data);
            }
            return response()->json(
                ["message" => "Thêm voucher thành công"]
            );
        }

        if (is_int($user)) {
            $wallet = get_wallet_via_user($user);
            $data = [
                "id_wallet" => $wallet->id,
                "id_voucher" => $id_voucher
            ];
            $check_voucher = $this->check_voucher_in_wallet($wallet->id, $id_voucher);
            if ($check_voucher) {
                return response()->json(
                    ["error_message" => "User đã có voucher trong ví"]
                );
            }
            WalletVoucher::query()->create($data);
            return response()->json(
                ["message" => "Thêm voucher thành công"]
            );
        }

        return response()->json(
            ["error_message" => "Id của user không hợp lệ"]
        , Response::HTTP_BAD_REQUEST);
    }

    public function check_voucher_in_wallet($id_wallet, $id_voucher) {
        $list_voucher = WalletVoucher::query()
            ->select('*')
            ->where('id_wallet', $id_wallet)
            ->get();
        return collect($list_voucher)->contains('id', $id_voucher);
    }
}
