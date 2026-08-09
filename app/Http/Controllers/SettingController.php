<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use App\Models\AppSetting;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_settings')->only(['index']);
        $this->middleware('permission:edit_settings')->only(['update', 'testEmail']);
    }

    public function index()
    {
        $settings = AppSetting::pluck('value', 'key')->toArray();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name'       => 'nullable|string|max:255',
            'app_address'    => 'nullable|string',
            'app_logo'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'mail_host'      => 'nullable|string|max:255',
            'mail_port'      => 'nullable|integer',
            'mail_username'  => 'nullable|string|max:255',
            'mail_password'  => 'nullable|string|max:255',
            'mail_encryption'=> 'nullable|in:tls,ssl,starttls',
            'mail_from_name' => 'nullable|string|max:255',
            'mail_from_address' => 'nullable|email|max:255',
        ]);

        $textFields = ['app_name', 'app_address', 'mail_host', 'mail_port', 'mail_username',
                       'mail_password', 'mail_encryption', 'mail_from_name', 'mail_from_address'];

        foreach ($textFields as $field) {
            if ($request->has($field)) {
                AppSetting::updateOrCreate(['key' => $field], ['value' => $request->$field]);
            }
        }

        if ($request->hasFile('app_logo')) {
            $imageName = time() . '.' . $request->app_logo->extension();
            $request->app_logo->storeAs('public/logos', $imageName);
            AppSetting::updateOrCreate(['key' => 'app_logo'], ['value' => $imageName]);
        }

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function testEmail(Request $request)
    {
        $request->validate(['test_email' => 'required|email']);

        // Load SMTP dari DB
        $settings = AppSetting::whereIn('key', [
            'mail_host', 'mail_port', 'mail_username', 'mail_password',
            'mail_encryption', 'mail_from_name', 'mail_from_address'
        ])->pluck('value', 'key')->toArray();

        Config::set('mail.mailers.smtp.host', $settings['mail_host'] ?? env('MAIL_HOST'));
        Config::set('mail.mailers.smtp.port', $settings['mail_port'] ?? env('MAIL_PORT'));
        Config::set('mail.mailers.smtp.username', $settings['mail_username'] ?? env('MAIL_USERNAME'));
        Config::set('mail.mailers.smtp.password', $settings['mail_password'] ?? env('MAIL_PASSWORD'));
        Config::set('mail.mailers.smtp.encryption', $settings['mail_encryption'] ?? env('MAIL_ENCRYPTION'));
        Config::set('mail.from.address', $settings['mail_from_address'] ?? env('MAIL_FROM_ADDRESS'));
        Config::set('mail.from.name', $settings['mail_from_name'] ?? env('MAIL_FROM_NAME'));

        try {
            Mail::raw('Ini adalah email uji coba dari CMMS Aisfar. Konfigurasi SMTP Anda berhasil!', function ($message) use ($request) {
                $message->to($request->test_email)->subject('Test Email - CMMS Aisfar');
            });
            return redirect()->route('settings.index')->with('success', 'Email uji coba berhasil dikirim ke ' . $request->test_email);
        } catch (\Exception $e) {
            return redirect()->route('settings.index')->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}
