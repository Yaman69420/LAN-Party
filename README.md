# LAN-Party

Een PHP MVC project voor LAN-Party evenementen.

## Vereisten

- PHP 8.0+
- MySQL (via MAMP/XAMPP)
- phpMyAdmin voor database beheer

## Installatie

1. Clone de repository
2. Maak database `lan_party_db` aan in phpMyAdmin
3. Importeer `database/lan_party_db.sql` (indien aanwezig)
4. Kopieer `admin/config/database.example.php` naar `admin/config/database.php`
5. Configureer je virtual host naar de `public` map

## Projectstructuur

```
LAN-Party/
├── admin/                    # Backend (admin panel)
│   ├── classes/Admin/        # PHP classes
│   ├── config/               # Configuratie bestanden
│   └── views/                # Admin views
├── public/                   # Publieke website (DocumentRoot)
│   ├── assets/               # CSS, JS, afbeeldingen
│   └── views/                # Publieke views
├── database/                 # Database exports
└── .htaccess                 # URL rewriting
```

## Database Workflow (Team)

1. Export database in phpMyAdmin → zet in `database/lan_party_db.sql`
2. Commit en push naar `dev` branch
3. Teamgenoten pullen en importeren in hun phpMyAdmin

## Ontwikkeling

- Website: `http://lan-party.com`
- Admin: `http://lan-party.com/admin`
