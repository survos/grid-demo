Demo for survos/datatables-bundle

## Quick Start: Download and run
git clone git@github.com:survos/datatables-demo

## Recreate

```bash
symfony new datatables-demo --webapp && cd datatables-demo
composer req api symfony/webpack-encore-bundle

echo "DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db" > .env.local
composer require orm-fixtures --dev 

yarn add bootstrap @popperjs/core
```

From the command line, create an entity for the elected official (congressperson or senator)

### make the entity / repo
```bash
bin/console doctrine:database:drop --force 
git clean -f src
echo "firstName,string,16,yes," | sed "s/,/\n/g"  | bin/console -a make:entity Official
echo "lastName,string,32,no," | sed "s/,/\n/g"  | bin/console make:entity Official
echo "officialName,string,48,no," | sed "s/,/\n/g"  | bin/console make:entity Official
echo "data,object,no," | sed "s/,/\n/g"  | bin/console make:entity Official
echo "birthday,date_immutable,yes," | sed "s/,/\n/g"  | bin/console make:entity Official
echo "gender,string,1,yes," | sed "s/,/\n/g"  | bin/console make:entity Official

# terms 
echo "offical,ManyToOne,Official,no,yes,terms,yes," | sed "s/,/\n/g"  | bin/console -a make:entity Term -a
echo "type,string,16,yes," | sed "s/,/\n/g"  | bin/console make:entity Term
echo "stateAbbreviation,string,2,yes," | sed "s/,/\n/g"  | bin/console make:entity Term
echo "party,string,8,yes," | sed "s/,/\n/g"  | bin/console make:entity Term
echo "district,string,8,yes," | sed "s/,/\n/g"  | bin/console make:entity Term
echo "startDate,date_immutable,yes," | sed "s/,/\n/g"  | bin/console make:entity Term
echo "endDate,date_immutable,yes," | sed "s/,/\n/g"  | bin/console make:entity Term

bin/console doctrine:schema:update --force

```

Load the database in fixtures, but could easily be in a controller or command.
```
bin/console make:fixtures CongressFixtures
```

```php

```

bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json

