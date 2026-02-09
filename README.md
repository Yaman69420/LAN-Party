# LAN-Party

PHP OOP MVC project voor LAN-Party evenementen - SYNTRA Groepswerk

## Vereisten

- PHP 8.0+
- MySQL (via MAMP/XAMPP)
- phpMyAdmin

## Installatie

1. Clone de repository
2. Maak database `lan_party_db` aan in phpMyAdmin
3. Kopieer `config/database.example.php` naar `config/database.php`
4. Configureer virtual host naar de `public` map

## Projectstructuur

```
LAN-Party/
├── app/
│   ├── Controllers/      # Controllers
│   ├── Models/           # Models
│   ├── Repositories/     # Database queries
│   ├── Views/            # Templates
│   └── Core/             # Router, Database, helpers
├── config/               # Configuratie
├── public/               # Front controller (DocumentRoot)
├── TEAM_PLAN.md          # Teamafspraken
├── ARCHITECTURE.md       # MVC uitleg
├── TEST_LOG.md           # Testcases
└── CHANGELOG.md          # Per groepslid
```

## Team

- [Naam 1]
- [Naam 2]
