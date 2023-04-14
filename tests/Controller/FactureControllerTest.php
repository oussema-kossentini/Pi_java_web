<?php

namespace App\Test\Controller;

use App\Entity\Facture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FactureControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/facture/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Facture::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Facture index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'facture[cin]' => 'Testing',
            'facture[nom]' => 'Testing',
            'facture[prenom]' => 'Testing',
            'facture[ville]' => 'Testing',
            'facture[date]' => 'Testing',
            'facture[prix]' => 'Testing',
            'facture[status]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Facture();
        $fixture->setCin('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setVille('My Title');
        $fixture->setDate('My Title');
        $fixture->setPrix('My Title');
        $fixture->setStatus('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Facture');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Facture();
        $fixture->setCin('Value');
        $fixture->setNom('Value');
        $fixture->setPrenom('Value');
        $fixture->setVille('Value');
        $fixture->setDate('Value');
        $fixture->setPrix('Value');
        $fixture->setStatus('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'facture[cin]' => 'Something New',
            'facture[nom]' => 'Something New',
            'facture[prenom]' => 'Something New',
            'facture[ville]' => 'Something New',
            'facture[date]' => 'Something New',
            'facture[prix]' => 'Something New',
            'facture[status]' => 'Something New',
        ]);

        self::assertResponseRedirects('/facture/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getCin());
        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getVille());
        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getPrix());
        self::assertSame('Something New', $fixture[0]->getStatus());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Facture();
        $fixture->setCin('Value');
        $fixture->setNom('Value');
        $fixture->setPrenom('Value');
        $fixture->setVille('Value');
        $fixture->setDate('Value');
        $fixture->setPrix('Value');
        $fixture->setStatus('Value');

        $$this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/facture/');
        self::assertSame(0, $this->repository->count([]));
    }
}
