# Migrations

Deze map bevat alle database schema wijzigingen.

## Hoe het werkt

1. **Nummering**: Bestanden beginnen met een nummer (000, 001, 002, ...)
2. **Volgorde**: Voer migrations uit in numerieke volgorde
3. **Eenmalig**: Elke migration wordt maar 1x uitgevoerd

## Nieuwe migration maken

```sql
-- migrations/002_beschrijving.sql
CREATE TABLE ... 
```

## Workflow voor team

1. Jij maakt een nieuwe tabel → maak `00X_beschrijving.sql`
2. `git add migrations/ && git commit -m "add: users table"`
3. Push naar GitHub
4. Teamgenoot pulled en voert nieuwe SQL uit in phpMyAdmin

## Huidige migrations

| # | Bestand | Beschrijving |
|---|---------|--------------|
| 000 | `000_init.sql` | Database setup + migrations tracking |
| 001 | `001_create_users_table.sql` | Users tabel |
