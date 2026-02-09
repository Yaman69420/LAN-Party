<?php
declare(strict_types=1);

// Kopieer dit bestand naar database.php en vul je Supabase gegevens in.
// Je vindt deze in je Supabase dashboard onder: Settings > Database > Connection string

return [
    'host' => 'db.xxxxxxxxxxxx.supabase.co',  // Jouw Supabase host
    'port' => 5432,                            // PostgreSQL port (standaard 5432, of 6543 voor pooler)
    'dbname' => 'lan_party_db',
    'user' => 'postgres',                      // Of je eigen database user
    'pass' => 'jouw_database_wachtwoord',
    'charset' => 'utf8',
    'driver' => 'pgsql',                       // PostgreSQL driver
];