<?php

namespace App\Http\Controllers;
use App\Models\NguoiDung;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MicrosoftAuthController extends Controller
{
    public function microsoftOAuthLogin(Request $request)
    {
        $clientId = env('CLIENT_ID');
        $redirectUri = env('REDIRECT_URI');
        $scope = 'openid profile offline_access'; // Thêm scope 'offline_access' để nhận refresh_token

        $loginUrl = "https://login.microsoftonline.com/common/oauth2/v2.0/authorize";

        $params = [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code', // Sử dụng 'code' cho Authorization Code Grant
            'scope' => $scope,
            'state' => Str::random(40), // Tạo state ngẫu nhiên
            'nonce' => Str::random(40), // Tạo nonce ngẫu nhiên
        ];

        // Redirect người dùng đến URL đăng nhập
        return redirect()->away($loginUrl . '?' . http_build_query($params));
    }

    public function microsoftOAuthCallback(Request $request)
    {
        if ($request->filled('code')) {
            $code = $request->input('code');
            // Gọi API để trao đổi code lấy access token và id token
            $tokenEndpoint = "https://login.microsoftonline.com/common/oauth2/v2.0/token";
            $tokenResponse = Http::asForm()->post($tokenEndpoint, [
                'client_id' => env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET'),
                'redirect_uri' => env('REDIRECT_URI'),
                'code' => $code,
                'grant_type' => 'authorization_code',
                'scope' => 'openid profile', // Scope yêu cầu cho token
            ]);
            if ($tokenResponse->successful()) {
                $tokens = $tokenResponse->json();
                // Lưu token vào session
                $microsoftToken = $tokens['access_token'];
                $request->session()->put('microsoft_token', $microsoftToken);
                $idToken = $tokens['id_token'];
                // Giải mã id_token để lấy thông tin người dùng
                $decodedToken = base64_decode(explode('.', $idToken)[1]);
                $tokenData = json_decode($decodedToken, true);
                // Kiểm tra email có đúng định dạng @vanlanguni.vn không
                $userEmail = $tokenData['preferred_username'];
                $userName = $tokenData['name'];
                $domain = '@vanlanguni.vn';
                if (Str::endsWith($userEmail, $domain)) {
                    // Lưu thông tin người dùng vào session
                    session(['user' => $tokenData]);
                    $userName = $tokenData['name'];
                    // Chuyển hướng đến trang chào mừng và truyền thông tin người dùng
                    return redirect()->route('handle-dang-nhap-van-lang', ['userEmail' => $userEmail, 'userName' => $userName]);
                } else {
                    // Quay trở lại trang đăng nhập vì email không hợp lệ
                    return redirect()->route('/')->withErrors('Only @vanlanguni.vn accounts are allowed.');
                }
            }
        }
        // Xử lý khi đăng nhập không thành công
        return redirect()->route('/')->withErrors('Authentication failed.');
    }


    public function microsoftLogout(Request $request){
        // Trích xuất thông tin người dùng từ session
        $userData = session('user');
        if ($userData) {
            // Lấy thông tin người dùng từ cơ sở dữ liệu
            $nguoiDung = NguoiDung::where('email', $userData['preferred_username'])->first();
            
            if ($nguoiDung && $nguoiDung->session_id) {
                $sessions = json_decode($nguoiDung->session_id, true) ?? [];
                
                foreach ($sessions as $session) {
                    if (isset($session['session_id'])) {
                        // Đóng phiên làm việc của session trước đó
                        Session::setId($session['session_id']);
                        Session::invalidate();
                    }
                }
                
                // Xóa session_id của người dùng từ bảng NguoiDung
                $nguoiDung->session_id = null;
                $nguoiDung->save();
            }

            // Xóa session của người dùng
            $request->session()->forget('user');
        }

        // URL đăng xuất của Microsoft
        $logoutUrl = "https://login.microsoftonline.com/common/oauth2/v2.0/logout?post_logout_redirect_uri=" . urlencode(env('APP_URL'));

        // Chuyển hướng đến trang đăng xuất của Microsoft
        return redirect($logoutUrl);
    }


    

}
