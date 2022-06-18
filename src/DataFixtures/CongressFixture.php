<?php

namespace App\DataFixtures;

use App\Entity\Congress;
use App\Entity\Term;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CacheInterface;

class CongressFixture extends Fixture
{
    public function __construct(private CacheInterface $cache)
    {

    }

    public function load(ObjectManager $manager): void
    {
        $url = 'https://theunitedstates.io/congress-legislators/legislators-current.json';
        $json = $this->cache->get(md5($url), fn(CacheItem $cacheItem) => file_get_contents($url));

        foreach (json_decode($json, true) as $record) {
            $name = (object)$record['name'];
            $bio = (object)$record['bio'];
            $c = (new Congress())
                ->setBirthday(new \DateTimeImmutable($bio->birthday))
                ->setGender($bio->gender)
                ->setData($record)
                ->setFirstName($name->first)
                ->setLastName($name->last)
                ->setOfficialName($name->official_full ?? "$name->first $name->last");
            $manager->persist($c);

//                foreach ($record->terms as $t) {
//                    $term = (new Term())
//                        ->setType($t->type)
//                        ->setStateAbbreviation($t->state)
//                        ->setParty($t->party ?? null)
//                        ->setDistrict($t->district ?? null)
//                        ->setStartDate(new \DateTime($t->start))
//                        ->setEndDate(new \DateTime($t->end));;
//                    $c->addTerm($term);
//                }
        }
        $manager->flush();
    }
}
