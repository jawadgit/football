<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\Player;
use App\Repository\PlayerRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamBuyFormType extends AbstractType
{
    private $playerRepository;

    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('country')
            ->add('money')
            ->add('buy_players', EntityType::class, [
                'class' => Player::class,
                'choices' => $this->playerRepository->findPlayersNotInTeam($options['team_id']),
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
