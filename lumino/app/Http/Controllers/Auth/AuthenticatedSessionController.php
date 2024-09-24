<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            // Xác thực thông tin đăng nhập
            $credentials = $request->only('email', 'password');

            // Kiểm tra thông tin đăng nhập
            if (!Auth::attempt($credentials)) {
                return response()->json(['error' => 'Tài khoản hoặc mật khẩu không đúng.'], 401);
            }
            // Xác thực thành công
            $user = Auth::user();
            $token = $user->createToken('YourAppName')->plainTextToken;
            return response()->json(['message' => 'Đăng nhập thành công.','user'=>$user, 'token' => $token], 200)
            ->cookie('token', $token, 60 * 24, '/', null, true, true);

        } catch (ValidationException $e) {
            return response()->json(['error' => 'Dữ liệu đầu vào không hợp lệ.'], 422);
        }catch (\Exception $e) {
            return response()->json(['error' => 'Đã xảy ra lỗi, vui lòng thử lại sau.'], 500);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        Auth::logout();
        return response()->json(['message'=>'Đăng xuất thành công']);
    }
    public function user(){
        $user=Auth::user();
        return response()->json($user);
    }
}
