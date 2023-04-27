<?php

namespace App\Test\Controller;

use App\Entity\Offre;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OffreControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/offre/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Offre::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Offre index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

   
    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'offre[dateDebut]' => 'Testing',
            'offre[dateFin]' => 'Testing',
            'offre[remise]' => 'Testing',
            'offre[idSponsor]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Offre();
        $fixture->setDateDebut('My Title');
        $fixture->setDateFin('My Title');
        $fixture->setRemise('My Title');
        $fixture->setIdSponsor('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Offre');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Offre();
        $fixture->setDateDebut('Value');
        $fixture->setDateFin('Value');
        $fixture->setRemise('Value');
        $fixture->setIdSponsor('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'offre[dateDebut]' => 'Something New',
            'offre[dateFin]' => 'Something New',
            'offre[remise]' => 'Something New',
            'offre[idSponsor]' => 'Something New',
        ]);

        self::assertResponseRedirects('/offre/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDateDebut());
        self::assertSame('Something New', $fixture[0]->getDateFin());
        self::assertSame('Something New', $fixture[0]->getRemise());
        self::assertSame('Something New', $fixture[0]->getIdSponsor());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Offre();
        $fixture->setDateDebut('Value');
        $fixture->setDateFin('Value');
        $fixture->setRemise('Value');
        $fixture->setIdSponsor('Value');

        $$this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/offre/');
        self::assertSame(0, $this->repository->count([]));
    }
}
