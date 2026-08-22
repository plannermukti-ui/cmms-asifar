@php
    $appLogo = \App\Models\AppSetting::where('key', 'app_logo')->first()?->value;
    $appName = \App\Models\AppSetting::where('key', 'app_name')->first()?->value ?? 'CMMS AISFAR';
    $appAddress = \App\Models\AppSetting::where('key', 'app_address')->first()?->value ?? '';
    
    $logoBase64 = null;
    if ($appLogo && file_exists(public_path('storage/logos/' . $appLogo))) {
        $logoData = file_get_contents(public_path('storage/logos/' . $appLogo));
        $logoMime = mime_content_type(public_path('storage/logos/' . $appLogo));
        $logoBase64 = 'data:' . $logoMime . ';base64,' . base64_encode($logoData);
    }

    $siteDisplay = $siteName ?? ($siteCode ?? null);
@endphp

<table style="width: 100%; border-bottom: 2px solid #0f172a; padding-bottom: 8px; margin-bottom: 12px; border-collapse: collapse;">
  <tr>
    <td style="width: 65%; vertical-align: top;">
      <table style="width: 100%; border-collapse: collapse; border: none;">
        <tr>
          @if($logoBase64)
          <td style="width: 55px; vertical-align: middle; padding-right: 12px; border: none;">
            <img src="{{ $logoBase64 }}" alt="Logo" style="max-height: 48px; max-width: 55px;">
          </td>
          @endif
          <td style="vertical-align: middle; border: none;">
            <div style="font-size: 14px; font-weight: bold; text-transform: uppercase; color: #0f172a; letter-spacing: 0.5px; line-height: 1.2;">
              {{ $appName }}
            </div>
            @if($appAddress)
              <div style="font-size: 9.5px; color: #475569; margin-top: 2px; line-height: 1.3;">
                {{ $appAddress }}
              </div>
            @endif
            @if($siteDisplay)
              <div style="font-size: 9.5px; font-weight: bold; color: #2563eb; margin-top: 1px;">
                Site: {{ $siteDisplay }}
              </div>
            @endif
          </td>
        </tr>
      </table>
    </td>
    <td style="width: 35%; text-align: right; vertical-align: middle;">
      @if(!empty($docTitle))
        <div style="font-size: 12px; font-weight: bold; color: #1e40af; text-transform: uppercase;">
          {{ $docTitle }}
        </div>
      @endif
      @if(!empty($docNumber))
        <div style="font-family: monospace; font-size: 10.5px; font-weight: bold; color: #0f172a; margin-top: 2px;">
          No: {{ $docNumber }}
        </div>
      @endif
      @if(!empty($docDate))
        <div style="font-size: 9px; color: #64748b; margin-top: 1px;">
          Tanggal: {{ $docDate }}
        </div>
      @endif
    </td>
  </tr>
</table>
