<?php
namespace App\Services;

use App\Entity\Offre;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Margin\Margin;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;

class QrcodeService
{
    /**
     * @var BuilderInterface
     */
    protected $builder;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(BuilderInterface $builder, EntityManagerInterface $entityManager)
    {
        $this->builder = $builder;
        $this->entityManager = $entityManager;
    }

    public function qrcode($idOffre)
    {
        // Retrieve the Offre entity from the database using Doctrine
        $offre = $this->entityManager->getRepository(Offre::class)->find($idOffre);

        // Set QR code data to the properties of the Offre entity
        $data = "Remise : " . $offre->getRemise() . "%\n";
        $data .= "Date début : " . $offre->getDateDebut()->format('d-m-Y') . "\n";
        $data .= "Date fin : " . $offre->getDateFin()->format('d-m-Y');

        $url = 'http://127.0.0.1:8000/offre/';
        $query = $offre->getIdOffre();

        $path = dirname(__DIR__, 2).'/public/Assets/';

        // Set up QR code builder
        $result = $this->builder
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(210)
            ->margin(1)
            ->labelText($url.$query)
            ->labelAlignment(new LabelAlignmentCenter())
            ->labelMargin(new Margin(15, 5, 5, 5))
            ->backgroundColor(new Color(255, 255, 255))
            ->build()
        ;

        // Generate unique name for the QR code image file
        $namePng = uniqid('', '') . '.png';

        // Save QR code image to file
        $result->saveToFile($path.'qr-code/'.$namePng);

        // Return the QR code image data as a data URI
        return $result->getDataUri();
    }
}
