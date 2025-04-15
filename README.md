
# 🧩 Laravel Modüler CMS (Laravel 12)

Laravel 12 ile geliştirilmiş, çok dilli destekli, modüler, sürükle-bırak yapıda özelleştirilebilir bir içerik yönetim sistemi (CMS).

Amacı, Laravel öğrenen geliştiricilere veya kendi CMS yapısını kurmak isteyenlere rehber olmasıdır.

## 🚀 Özellikler

- ✅ Laravel 12 + PHP 8.3 uyumlu
- 🌐 Çok dilli yapı desteği
- 📦 Modül tabanlı sayfa içerikleri
- 🔀 Sürükle-bırak ile sayfa düzenleme
- 🔐 Rol ve yetki yönetimi
- 🧾 Sayfa bazlı yetkilendirme
- 🕒 Cron tabanlı queue ve schedule sistemi (Redis gerekmez)
- ☁️ Paylaşımlı hosting uyumlu

## 🧰 Kullanılan Paketler

```bash
barryvdh/laravel-debugbar
opcodesio/log-viewer
anhskohbo/no-captcha
spatie/laravel-permission
intervention/image
propaganistas/laravel-phone
unisharp/laravel-filemanager
spatie/laravel-sitemap
```

---

## ⚙️ Kurulum

### 1. Projeyi klonla ve yapılandır:

```bash
git clone https://github.com/sercan-sever/kurumsal-cms-public.git
cd kurumsal-cms-public

composer install
cp .env.example .env
php artisan key:generate
```

### 2. `.env` dosyasını düzenle:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=veritabani_adi
DB_USERNAME=kullanici_adi
DB_PASSWORD=sifre
```

### 3. Veritabanı migrasyonlarını ve başlangıç verilerini çalıştır:

```bash
php artisan migrate --seed
```

### 4. Storage link ve dosya izinleri:

```bash
php artisan storage:link
```

---

## 🕒 Cron Ayarları

Queue ve schedule işlemleri Redis gibi bağımlılıklar olmadan `cron` ile çalışacak şekilde yapılandırılmıştır.

### crontab -e komutunu çalıştırarak aşağıdaki satırları ekleyin:

```cron
* * * * * /usr/bin/php /home/kullaniciadi/proje-dizini/artisan queue:work --sleep=3 --tries=3 --timeout=60 >> /home/kullaniciadi/proje-dizini/storage/logs/queue.log 2>&1

* * * * * /usr/bin/php /home/kullaniciadi/proje-dizini/artisan schedule:run >> /dev/null 2>&1

* * * * * /usr/bin/php /home/kullaniciadi/proje-dizini/artisan queue:restart
```

> 📌 **Not:** PHP yolu (`/usr/bin/php`) ve proje dizini (`/home/kullaniciadi/proje-dizini`) kendi sunucu ya da local yapınıza göre düzenlenmelidir.  
PHP yolunu öğrenmek için terminalde `which php` komutunu çalıştırabilirsiniz.

---

## Sistemi Kullanmadan Önce

- Kullanıma başlamadan önce dil ekli olmadığı için ilk önce dil eklenmeli. 
- Dil ekleme ve sistemin hatasız kullanımı için bootstrap/app.php içerisindeki BeforeTranslationMiddleware::class alanı yorum satırına çevirilmelidir.
- Daha sonra ilk dil ekleme ve dil içeriklerinin sorunsuz şekilde veritabanına eklendiğinden emin olun ve sonrasında yorum satırını kaldırıp kullanıma başlayabilirsiniz.

---

## 🔐 Admin Girişi (Örnek)

🔗 [Demo Site](https://demo.localkod.com/tr)  
🔗 [Demo Admin Panel](https://demo.localkod.com/lk-admin)

```txt
Demo Kullanıcı Mail : demo@localkod.com
Demo Kullanıcı Şifre : demo123456
```

---

## 🧪 Geliştirme Ortamı

- Laravel 12
- PHP 8.3
- MySQL 8+

> Local geliştirme için [Laravel Herd](https://herd.laravel.com/), Valet, XAMPP veya Docker kullanılabilir.

---

## 📬 İletişim

Her türlü geri bildirim veya katkı için LinkedIn üzerinden ulaşabilirsiniz:  
🔗 [localkod.com](https://localkod.com/tr)  
🔗 [linkedin.com/in/sercan-sever](https://www.linkedin.com/in/sercan-sever/)  
✉️ [sercan.sever35@gmail.com](mailto:sercan.sever35@gmail.com)
