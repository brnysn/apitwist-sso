<?php

namespace Brnysn\ApiTwistSSO\Http\Controllers;

use Brnysn\ApiTwistSSO\Services\SSOService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SSOController extends Controller
{
    public function login(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));
        $query = http_build_query([
            'client_id' => config('apitwist-sso.client_id'),
            'redirect_uri' => config('apitwist-sso.redirect_url'),
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
        ]);

        return redirect()->away(config('apitwist-sso.authorize_url').'?'.$query);
    }

    public function callback(Request $request)
    {
        $state = $request->session()->pull('state');
        $request->session()->forget('state');
        if ($request->input('state') != $state) {
            return redirect()->route('sso.login')->with('error', 'Invalid state');
        }
        $http = new Client(['http_errors' => false]);
        $response = $http->post(config('apitwist-sso.api_url'), [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => config('apitwist-sso.client_id'),
                'client_secret' => config('apitwist-sso.client_secret'),
                'redirect_uri' => config('apitwist-sso.redirect_url'),
                'code' => $request->input('code'),
            ],
        ]);
        if (! $response->getStatusCode() == 200) {
            return redirect()->route('sso.login')->with('error', 'Invalid code');
        }
        $response = json_decode((string) $response->getBody(), true);

        $request->session()->put('sso_access_token', $response['access_token']);
        $request->session()->put('sso_refresh_token', $response['refresh_token']);
        $request->session()->put('sso_tokens_verified_at', now());
        $request->session()->put('sso_tokens_expires_in', $response['expires_in']);

        $expires_at = Carbon::parse($response['expires_in'] + now()->timestamp);
        $user = (new SSOService())->handle($response['access_token'], $expires_at);
        if (! $user) {
            return redirect()->route('sss.login')->with('error', 'Invalid state');
        }

        Auth::login($user);

        return redirect()->route('sso.loggedIn');
    }

    public function logout(Request $request)
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->ssoToken()->exists()) {
                $headers = [
                    'Content-Type' => 'application/json',
                    'Application' => 'application/json',
                    'Authorization' => 'Bearer '.$user->getSsoToken(),
                ];
                $client = new Client(['headers' => $headers, 'http_errors' => false]);
                $client->post(config('apitwist-sso.api_url'));

                $user->ssoToken->delete();
            }
        }
        $request->session()->forget('access_token');

        return redirect()->away(config('apitwist-sso.logout_url').'?callback='.env('APP_URL'));
    }
}
