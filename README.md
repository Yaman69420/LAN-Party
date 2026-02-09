# LAN-Party

Een PHP MVC project voor LAN-Party evenementen.

## Vereisten

- PHP 8.0+ (met pdo_pgsql extensie)
- Supabase account (PostgreSQL database)
- MAMP/XAMPP of vergelijkbare lokale server

## Installatie

1. Clone de repository
2. Kopieer `admin/config/database.example.php` naar `admin/config/database.php`
3. Vul je Supabase database gegevens in (te vinden in Supabase Dashboard > Settings > Database)
4. Database naam: `lan_party_db`
5. Configureer je virtual host naar de `public` map

## Projectstructuur

```
LAN-Party/
├── admin/                    # Backend (admin panel)
│   ├── classes/Admin/        # PHP classes
│   │   ├── Controllers/      # Controllers
│   │   ├── Models/           # Database models
│   │   ├── Services/         # Business logic
│   │   └── Middleware/       # Request middleware
│   ├── config/               # Configuratie bestanden
│   ├── includes/             # Helpers en utilities
│   └── views/                # Admin views
├── public/                   # Publieke website (DocumentRoot)
│   ├── assets/               # CSS, JS, afbeeldingen
│   ├── uploads/              # User uploads
│   └── views/                # Publieke views
└── .htaccess                 # URL rewriting
```

## Ontwikkeling

- Publieke website: `http://lan-party.com`
- Admin panel: `http://lan-party.com/admin`
