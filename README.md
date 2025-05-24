# Tender Document Analyzer and Auto-Tagger

This Laravel-based project automates the analysis of tender documents (PDF/DOCX), extracts text content, matches relevant tags, and notifies users based on tag preferences.

---

## 🔧 Features

- Upload PDF or Word documents (.docx)
- Auto-extract and store textual content
- Match pre-defined tags from document content
- Store and manage tags using many-to-many relationships (tenders ↔ tags, users ↔ tags)
- Notify users via email if a tender matches their subscribed tags
- Role-based access (planned)
- Export and analytics dashboard (planned)

---

## 📁 Folder Structure

- `app/Models/`: Eloquent models like `Tender`, `Tag`, and `User`
- `app/Http/Controllers/`: Contains `TenderController` with `upload()` method
- `resources/views/`: Blade templates for forms, dashboard, etc.
- `routes/web.php`: All web routes, protected by `auth` middleware

---

## 🚀 Setup Instructions

1. **Clone the Repo**

```bash
git clone https://github.com/Shwetalig/tender-analyzer.git
cd tender-analyzer
```

2. **Install Dependencies**

```bash
composer install
npm install && npm run dev
```

3. **Environment Setup**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Migration & Seeding**

```bash
php artisan migrate --seed
```

5. **Storage Linking**

```bash
php artisan storage:link
```

6. **Start Server**

```bash
php artisan serve
```

---

## 📬 Mail Configuration

Set your SMTP credentials in `.env`:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="Tender Analyzer"
```

Make sure to enable **SSL/TLS** and use an app password if using Gmail.

---

## 📌 Summary

###  Core Functionality (✅ Done)
- [✅] Upload & store tender files
- [✅] Text extraction (PDF & DOCX)
- [✅] Tag-tender linking
- [✅] User-tag matching
- [✅] Notification system
- [✅] Manual tag editing
- [✅] Tag filtering/search
- [✅] Export tenders to Excel
- [✅] Analytics dashboard

---

## 👨‍💻 Contributing

Pull requests are welcome. For major changes, open an issue first to discuss.

---

## 📄 License

[MIT](LICENSE)
