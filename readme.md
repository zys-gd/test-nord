# HOWTO

1. Build project `docker compose build`
2. run container `docker compose run --rm php bash`
3. Run command `bin/console app:bike-share CITY_NAME`, e.g.:
   1. `bin/console app:bike-share Kraków`
   2. `bin/console app:bike-share Bergamo`

    Note, that city must be with capital letter and all specific symbols for native language (Kraków, Křinec, Würzburg etc.)
4. Command can receive path to the csv file as second parameter, e.g. `bin/console app:bike-share Kraków https://raw.githubusercontent.com/NordLocker/php-candidate-task/main/bikers.csv`

Result: 

![](img.png)

# ABOUT
This is simple console application for calculating distance to the closest bike station in the chosen city. Data about stations provided by [CityBikes API](http://api.citybik.es/v2/). Data about bikers received from `.csv` _publicly available_ file.
