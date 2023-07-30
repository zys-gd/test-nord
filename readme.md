# HOWTO

1. Build project `docker compose up -d`
2. run container `docker compose run --rm php bash`
3. Run command `bin/console app:bike-share CITY_NAME`, e.g.:
   1. `bin/console app:bike-share Kraków`
   2. `bin/console app:bike-share Bergamo`

    Note, that city must be with capital letter and all specific symbols for native language (Kraków, Křinec, Würzburg etc.)

Result https://prnt.sc/OqP4QHhSfmUk
