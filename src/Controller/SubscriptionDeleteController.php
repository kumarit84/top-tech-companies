<?php

namespace App\Controller;

use App\Entity\Subscribe;
use App\Form\Type\SubscribeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionDeleteController extends AbstractController
{
    /**
     * @Route("/subscription/delete/{id}", name="subscription_delete")
     * @param Request $request
     * @return Response
     */
    public function deleteSubscribe(Request $request, $id): Response
    {

        $rowdelete = 0;
        $res  = array();
        if($id)
        {
            //Search for the row based on the Id
            if (($handle = fopen("file.csv", "r+")) !== FALSE) {
                $row = 0;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    for ($c = 0; $c < $num; $c++) {
                        if($c==4 && $data[$c] == $id){
                            $rowdelete = $row;
                        }
                        $res[$row][$c] = $data[$c];
                    }
                    $row++;
                }
                fclose($handle);
            }

            //Delete the row and add the remaining to the csv
            unset($res[$rowdelete]);
            $fp = fopen('file.csv', 'w+');
            foreach ($res as $fields) {
                fputcsv($fp, $fields);
            }
            fclose($fp);

        }
        return $this->redirectToRoute('list');

    }
}