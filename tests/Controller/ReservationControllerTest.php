<?php

namespace App\Test\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ReservationRepository $repository;
    private string $path = '/reservation/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Reservation::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'reservation[cin]' => 'Testing',
            'reservation[nom]' => 'Testing',
            'reservation[prenom]' => 'Testing',
            'reservation[ville]' => 'Testing',
            'reservation[num_tel]' => 'Testing',
            'reservation[adresse_m]' => 'Testing',
            'reservation[date_livraison]' => 'Testing',
            'reservation[type_produit]' => 'Testing',
            'reservation[lieu_depart]' => 'Testing',
            'reservation[lieu_arrivee]' => 'Testing',
            'reservation[poids]' => 'Testing',
        ]);

        self::assertResponseRedirects('/reservation/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setCin('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setVille('My Title');
        $fixture->setNum_tel('My Title');
        $fixture->setAdresse_m('My Title');
        $fixture->setDate_livraison('My Title');
        $fixture->setType_produit('My Title');
        $fixture->setLieu_depart('My Title');
        $fixture->setLieu_arrivee('My Title');
        $fixture->setPoids('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setCin('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setVille('My Title');
        $fixture->setNum_tel('My Title');
        $fixture->setAdresse_m('My Title');
        $fixture->setDate_livraison('My Title');
        $fixture->setType_produit('My Title');
        $fixture->setLieu_depart('My Title');
        $fixture->setLieu_arrivee('My Title');
        $fixture->setPoids('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'reservation[cin]' => 'Something New',
            'reservation[nom]' => 'Something New',
            'reservation[prenom]' => 'Something New',
            'reservation[ville]' => 'Something New',
            'reservation[num_tel]' => 'Something New',
            'reservation[adresse_m]' => 'Something New',
            'reservation[date_livraison]' => 'Something New',
            'reservation[type_produit]' => 'Something New',
            'reservation[lieu_depart]' => 'Something New',
            'reservation[lieu_arrivee]' => 'Something New',
            'reservation[poids]' => 'Something New',
        ]);

        self::assertResponseRedirects('/reservation/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getCin());
        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getVille());
        self::assertSame('Something New', $fixture[0]->getNum_tel());
        self::assertSame('Something New', $fixture[0]->getAdresse_m());
        self::assertSame('Something New', $fixture[0]->getDate_livraison());
        self::assertSame('Something New', $fixture[0]->getType_produit());
        self::assertSame('Something New', $fixture[0]->getLieu_depart());
        self::assertSame('Something New', $fixture[0]->getLieu_arrivee());
        self::assertSame('Something New', $fixture[0]->getPoids());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Reservation();
        $fixture->setCin('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setVille('My Title');
        $fixture->setNum_tel('My Title');
        $fixture->setAdresse_m('My Title');
        $fixture->setDate_livraison('My Title');
        $fixture->setType_produit('My Title');
        $fixture->setLieu_depart('My Title');
        $fixture->setLieu_arrivee('My Title');
        $fixture->setPoids('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/reservation/');
    }
}
