<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Url;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\EntityManagerInterface;

class UrlController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/url', name: 'app_url')]
    public function index(): Response
    {
        return $this->render('url/index.html.twig', [
            'controller_name' => 'UrlController',
        ]);
    }
    /**
     * @Route("/process-csv", name="process_csv", methods={"POST"})
     */
    public function processCsv(Request $request): Response
    {
        $count = 0;
        $file = $request->files->get('csv_file');

        // Read the CSV file and store its data in an array
        $csvData = [];
        if (($handle = fopen($file->getPathname(), 'r')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $csvData[] = $data;
            }
            fclose($handle);
        }

        // Delete data that is duplicated
        // Process the CSV data and store in the database
        foreach ($csvData as $row) {
            $yourRepository = $this->entityManager->getRepository(Url::class);
            $data = $yourRepository->findOneBy(['url' => $row]);
            
            // Check if the record exists
            if (!$data) {
                // throw $this->createNotFoundException('Data not found for URL: '.$urlParameter);
            
                // Create a new entity object
                $entity = new Url();

                // Assign the CSV data to the entity properties
                $entity->setUrl($row[0]);

                $this->entityManager->persist($entity);
                $this->entityManager->flush();

                $count++;
            }
        }

        return $this->render('url/index.html.twig', [
            'recordcount' => $count,
        ]);
    }
}
