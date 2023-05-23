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
        // $csvFile = $request->files->get('csv_file');
        // if ($csvFile) {

        //     // Process the file
        //     $fileObject = new File($csvFile->getPathname());

        //     // Get the content of the file
        //     $content = file_get_contents($fileObject->getPathname());
        //     $csv = preg_split('/\s+/', $content);
            
        // }
        
        // // Iterate over the CSV records and save them into the database
        // foreach ($csv as $record) {
        //     // Process and save the record into the database using Doctrine
            
        //     $url = new Url();
        //     $url->setUrl($record);

        //     $entityManager = $this->getDoctrine()->getManager();
            
        //     $entityManager->persist($url);
        //     $entityManager->flush();
        // }
        $file = $request->files->get('csv_file');

        // Read the CSV file and store its data in an array
        $csvData = [];
        if (($handle = fopen($file->getPathname(), 'r')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $csvData[] = $data;
            }
            fclose($handle);
        }

        // Process the CSV data and store in the database
        foreach ($csvData as $row) {
            // Create a new entity object
            $entity = new Url();

            // Assign the CSV data to the entity properties
            $entity->setUrl($row[0]);
            // ...

            // Persist the entity
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($entity);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }

        // Flush the changes to the database
        // $entityManager->flush();


        return $this->render('url/index.html.twig');
    }
}
