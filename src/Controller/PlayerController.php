<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerFormType;
use App\Controller\Team;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Persistence\ManagerRegistry;

class PlayerController extends AbstractController
{

    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Route("/player", name="player")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $data = $this->managerRegistry->getRepository(Player::class)->findAll();
        $playerData = $paginator->paginate(
            // Doctrine Query, not results
            $data,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('player/index.html.twig', [
            'list' => $playerData,
        ]);
    }

    /**
     * @Route("/player/create", name="playerCreate")
     */
    public function playerCreate(Request $request){

        $player = new Player();
        $form = $this->createForm(PlayerFormType::class, $player);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();

            $this->addFlash('notice', 'record added successfully');
            return $this->redirectToRoute('player');
        };
        return $this->render('player/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

        /**
     * @Route("/player/update/{id}", name="playerUpdate")
     */
    public function playerUpdate(Request $request, $id){
        $player = $this->getDoctrine()->getRepository(Player::class)->find($id);
        $form = $this->createForm(PlayerFormType::class, $player);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();

            $this->addFlash('notice', 'record updated successfully');
            return $this->redirectToRoute('player');
        };
        return $this->render('player/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/player/delete/{id}", name="playerDelete")
     */
    public function playerDelete($id){
        $player = $this->getDoctrine()->getRepository(Player::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($player);
        $em->flush();

        $this->addFlash('notice', 'record deleted successfully');
        return $this->redirectToRoute('player');
    }

}
