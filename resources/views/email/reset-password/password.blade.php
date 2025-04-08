<p><strong style="font-size: 16px; color: black;">Kullanıcı Adı:</strong> {{ $user?->name }}</p>
<p><strong style="font-size: 16px; color: black;">Kullanıcı E-Mail:</strong> {{ $user?->email }}</p>
<p><strong style="font-size: 16px; color: black;">Kullanıcı Şifre:</strong> {{ $password ?? '' }}</p>

<br>

<p>*****<small> E-Mail ve Şifreniz ile ' Giriş Yap ' butona tıklayarak giriş yapabilirsiniz. </small>*****</p>
