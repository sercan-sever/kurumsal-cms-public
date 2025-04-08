@if (!empty($loginDate))
    <p><strong style="font-size: 16px; color: black;">Giriş Tarih:</strong> {{ $loginDate }}</p>
@endif

@if (!empty($logoutDate))
    <p><strong style="font-size: 16px; color: black;">Çıkış Tarih:</strong> {{ $logoutDate }}</p>
@endif

<p><strong style="font-size: 16px; color: black;">IP Adres:</strong> {{ $ip }}</p>
<p><strong style="font-size: 16px; color: black;">Ad Soyad:</strong> {{ $name }}</p>
<p><strong style="font-size: 16px; color: black;">E-Mail:</strong> {{ $email }}</p>
