<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                "label" => "Email de l'utilisateur",
                "attr" => [
                    "placeholder" => "exemple@gmail.com"
                ]
            ])
            ->add('roles', ChoiceType::class,[
                "choices" => [
                    "Administrateur" => "ROLE_ADMIN",
                    "Licencié" => "ROLE_USER"
                ],
                "multiple" => true,
                "expanded" => true,
                "label" => "Privilèges"
            ])
            ->add('licenceNumber', TextType::class, [
                "label" => "Numéro de licence"
            ])
            ->add('firstname', TextType::class, [
                "label" => "Prénom"
            ])
            ->add('lastname', TextType::class, [
                "label" => "Nom"
            ])
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'choice',
                'input' => 'datetime_immutable',
                'years' => range(date('Y') - 100, date('Y') + 10),
                'format' => 'dd MM yyyy',
                "label" => "Date de naissance"
            ])
            ->add('subscription', ChoiceType::class,[
                "choices" => [
                    "Payée" => true,
                    "Non payée" => false
                ],
 
                "label" => "Cotisation"
            ])
            ->add('position', TextType::class, [
                "label" => "Fonction au sein du Club",
                "attr" => [
                    "placeholder" => "Président, Trésorier etc..."
                ],
                'required'   => false
            ])
            ->add('courses', EntityType::class, [
                'class' => Course::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.name', 'ASC');
                },
                "label" => "Cours"

            ]);
            if($options["custom_option"] !== "edit"){
                $builder
                ->add('password',RepeatedType::class,[
                    "type" => PasswordType::class,
                    "first_options" => ["label" => "Rentrez un mot de passe","help" => "Le mot de passe doit contenir :
                    plus de 6 caractères,
                    au moins une lettre majuscule,
                    au moins une lettre minuscule,
                    au moins un chiffre, 
                    aucun espace."],
                    "second_options" => ["label" => "Confirmez le mot de passe"],
                    "invalid_message" => "Les champs doivent être identiques",
                ]);
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            "custom_option" => "default"
        ]);
    }
}
