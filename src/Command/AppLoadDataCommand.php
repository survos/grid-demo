<?php

namespace App\Command;

use App\Entity\Official;
use App\Entity\Term;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Zenstruck\Console\Attribute\Option;
use Zenstruck\Console\ConfigureWithAttributes;
use Zenstruck\Console\InvokableServiceCommand;
use Zenstruck\Console\IO;
use Zenstruck\Console\RunsCommands;
use Zenstruck\Console\RunsProcesses;

#[AsCommand('app:load-data', 'Load the congressional data')]
final class AppLoadDataCommand extends InvokableServiceCommand
{
    use ConfigureWithAttributes;
    use RunsCommands;
    use RunsProcesses;

    public function __construct(
        private ValidatorInterface $validator,
        private CacheInterface $cache,
        string $name = null)
    {
        parent::__construct($name);
    }

    public function __invoke(
        IO   $io,
        EntityManagerInterface $manager,
        SerializerInterface $serializer,
        #[Option(description: 'reload the json even if already in the cache')] bool $refresh = false,
    ): void
    {

        $url = 'https://theunitedstates.io/congress-legislators/legislators-current.json';
        $json = $this->cache->get(md5($url), fn(CacheItem $cacheItem) => file_get_contents($url));
//        dd($json);

        foreach (json_decode($json) as $record) {

//            $official = $serializer->denormalize(
//                $record,
//                Official::class,
//                null
//            );
//            dd($official);
//
//
//
//            $normalizers = [new ObjectNormalizer()];
//            $serializer = new Serializer($normalizers, []);

            $name = $record->name;
            $bio = $record->bio;
            $official = (new Official())
                ->setBirthday(new \DateTimeImmutable($bio->birthday))
                ->setGender($bio->gender)
                ->setFirstName($name->first)
                ->setLastName($name->last)
                ->setOfficialName($name->official_full ?? "$name->first $name->last");
            $manager->persist($official);

            foreach ($record->terms as $t) {
                $term = (new Term())
                    ->setType($t->type)
                    ->setStateAbbreviation($t->state)
                    ->setParty($t->party ?? null)
                    ->setDistrict($t->district ?? null)
                    ->setStartDate(new \DateTimeImmutable($t->start))
                    ->setEndDate(new \DateTimeImmutable($t->end));
                $manager->persist($term);
                $official->addTerm($term);
                $errors = $this->validator->validate($term);
                if (count($errors)) {
                    dd($errors);
                }
            }
        }
        $manager->flush();
        $io->success('app:load-data success.');
    }
}
