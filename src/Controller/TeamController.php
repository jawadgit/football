<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\Player;
use App\Form\TeamFormType;
use App\Form\TeamBuyFormType;
use App\Form\TeamSellFormType;
use App\Form\PlayerFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class TeamController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Route("/", name="team")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $data = $this->managerRegistry->getRepository(Team::class)->findAll();
         // Paginate the results of the query
         $teamData = $paginator->paginate(
            // Doctrine Query, not results
            $data,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('team/index.html.twig', [
            'list' => $teamData,
        ]);
    }

    /**
     * @Route("/team/create", name="teamCreate")
     */
    public function teamCreate(Request $request){
        $team = new Team();
        $form = $this->createForm(TeamFormType::class, $team);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();

            $this->addFlash('notice', 'record added successfully');
            return $this->redirectToRoute('team');
        };
        return $this->render('team/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/team/update/{id}", name="teamUpdate")
     */
    public function teamUpdate(Request $request, $id){
        $team = $this->getDoctrine()->getRepository(Team::class)->find($id);
        $form = $this->createForm(TeamFormType::class, $team);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();

            $this->addFlash('notice', 'record updated successfully');
            return $this->redirectToRoute('team');
        };
        return $this->render('team/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/team/delete/{id}", name="teamDelete")
     */
    public function teamDelete($id){
        $team = $this->getDoctrine()->getRepository(Team::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($team);
        $em->flush();

        $this->addFlash('notice', 'record deleted successfully');
        return $this->redirectToRoute('team');
    }

    /**
     * @Route("/team/buy/{id}", name="teamBuy")
     */
    public function teamBuy(Request $request, $id){
        $team = $this->getDoctrine()->getRepository(Team::class)->find($id);
        $players = $this->getDoctrine()->getRepository(Player::class)->findPlayersNotInTeam($id);
        $form = $this->createForm(TeamBuyFormType::class, $team, ['team_id' => $id]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $ddd = $form->get('buy_players')->getData();
            $selectedPlayers = $form->get('buy_players')->getData();

            // Update the players' team based on the selection
            foreach ($selectedPlayers as $playerId) {
                $entityManager = $this->getDoctrine()->getManager();
                $player = $entityManager->getRepository(Player::class)->find($playerId);
                $player->setTeam($team);
                $entityManager->persist($player);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();

            $this->addFlash('notice', 'record updated successfully');
            return $this->redirectToRoute('team');
        };
        return $this->render('team/buy.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/team/sell/{id}", name="teamSell")
     */
    public function teamSell(Request $request, $id){
        $team = $this->getDoctrine()->getRepository(Team::class)->find($id);
        $form = $this->createForm(TeamSellFormType::class, $team, ['team_id' => $id]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $ddd = $form->get('sell_players')->getData();
            $selectedPlayers = $form->get('sell_players')->getData();
            $selectedTeam = $form->get('teams')->getData();

            // Update the players' team based on the selection
            foreach ($selectedPlayers as $playerId) {
                $entityManager = $this->getDoctrine()->getManager();
                $player = $entityManager->getRepository(Player::class)->find($playerId);
                $player->setTeam($selectedTeam);
                $entityManager->persist($player);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();

            $this->addFlash('notice', 'record updated successfully');
            return $this->redirectToRoute('team');
        };
        return $this->render('team/sell.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
}
