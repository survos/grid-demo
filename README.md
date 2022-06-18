Demo for survos/datatables-bundle

## Quick Start: Download and run
git clone git@github.com:survos/datatables-demo

## Recreate

```bash
symfony new datatables-demo --webapp && cd datatables-demo
composer req api symfony/webpack-encore-bundle

echo "DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db" > .env.local
composer require orm-fixtures --dev 
bin/console make:fixtures CountryFixtures

yarn add bootstrap @popperjs/core
```

From the command line, create an entity for the congress

### make the entity / repo
```bash
bin/console -a make:entity Congress
       # firstName, string, 55, yes (not nullable)

# cut and paste to skip the typing if you have sed installed
echo "firstName,string,16,yes," | sed "s/,/\n/g"  | bin/console -a make:entity Congress
echo "lastName,string,32,no," | sed "s/,/\n/g"  | bin/console make:entity Congress
echo "officialName,string,48,no," | sed "s/,/\n/g"  | bin/console make:entity Congress
echo "data,json,no," | sed "s/,/\n/g"  | bin/console make:entity Congress
echo "birthday,date_immutable,yes," | sed "s/,/\n/g"  | bin/console make:entity Congress
echo "gender,string,1,yes," | sed "s/,/\n/g"  | bin/console make:entity Congress

bin/console doctrine:schema:update --force

```


Loading the database is trivial,

```
    // CountryFixtures.php
    public function load(ObjectManager $manager)
    {
        $countries = Countries::getNames();
        foreach ($countries as $alpha2=>$name) {
            $country = new Country();
            $country
                ->setName($name)
                ->setAlpha2($alpha2);
            $manager->persist($country);
        }
        $manager->flush();
    }
```

bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json
