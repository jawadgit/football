<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\Player;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamSellFormType extends AbstractType
{
    private $playerRepository, $teamRepository;

    public function __construct(PlayerRepository $playerRepository, TeamRepository $teamRepository)
    {
        $this->playerRepository = $playerRepository;
        $this->teamRepository = $teamRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('country')
            ->add('money')
            ->add('teams', EntityType::class, [
                'class' => Team::class,
                'choices' => $this->teamRepository->findAllExceptId($options['team_id']),
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
                'required' => false,
                'mapped' => false,
                'label' => 'Select Team you want to sell players '
            ])
            ->add('sell_players', EntityType::class, [
                'class' => Player::class,
                'choices' => $this->playerRepository->findPlayersInTeam($options['team_id']),
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'mapped' => false,
                'label' => 'Select Players from below list'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
            'team_id' => null,
        ]);
    }
}
