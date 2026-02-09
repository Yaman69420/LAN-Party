# LAN-Party

Een PHP MVC project voor LAN-Party evenementen.

## Vereisten

- PHP 8.0+
- MySQL (via MAMP/XAMPP)
- phpMyAdmin voor database beheer

## Installatie

1. Clone de repository
2. Maak database `lan_party_db` aan in phpMyAdmin
3. Kopieer `admin/config/database.example.php` naar `admin/config/database.php`
4. Vul je database gegevens in
5. Voer de migrations uit (zie `migrations/README.md`)
6. Configureer je virtual host naar de `public` map

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
├── migrations/               # Database schema wijzigingen
└── .htaccess                 # URL rewriting
```

## Database Workflow (Team)

1. Maak schema wijziging → nieuw bestand in `migrations/`
2. Commit en push naar `dev` branch
3. Teamgenoten pullen en voeren nieuwe SQL uit in phpMyAdmin

## Ontwikkeling

- Website: `http://lan-party.com`
- Admin: `http://lan-party.com/admin`
