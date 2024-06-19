<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name',TextType::class,[
            "label" => "Name",
            "attr" => [
                "placeholder" => "Nom de l'utilisateur"
            ]
        ] )
        ->add('email',EmailType::class,[
            "label" => "Email",
            "attr" => [
                "placeholder" => "Mail de l'utilisateur"
            ]
        ])
        ->add('roles',ChoiceType::class,[
            "label" => "Role",
            "choices" => [
                
                "Administrateur" => "ROLE_ADMIN",
                "Utilisateur" => "ROLE_USER"
            ],
            "multiple" => true,
            "expanded" => true
        ]);
            // Je souhaite ici ne pas afficher le champ password si je suis en mode update
            // En effet un admin n'est pas censé pouvoir modifier le mot de passe de quelqu'un d'autre
            // Je vais donc utiliser une option custom_option qui me permettra de savoir si je suis en mode update ou non
            // Les options customs ce definissent plus bas dans la méthode configureOptions
            // Pour les utiliser il suffit lors de la création du formulaire de passer un tableau en deuxième paramètre de la méthode createForm
            if($options["custom_option"] !== "update"){
                $builder
                // Je souhaite ici utiliser le champ repeated pour avoir deux champs password
                // https://symfony.com/doc/current/reference/forms/types/repeated.html
                    ->add('password',RepeatedType::class,[
                        "help" => "8 charactères minimum",
                        // D'ou ici l'interet de mettre une contrainte directement dans le form vu que le mot de passe s'affichera conditionnellement
                        "constraints" => [
                            new Length([
                                "min" => 8,
                                "minMessage" => "8 charactères minimum"
                            ])
                        ],
                        "type" => PasswordType::class,
                        'invalid_message' => 'les 2 mots de passe doivent être identiques',
                        'required' => true,
                        'first_options'  => ['label' => 'Votre mot de passe',"attr" => ["placeholder" => "*****"]],
                        'second_options' => ['label' => 'Répétez votre mot de passe',"attr" => ["placeholder" => "*****"]],
                    ])
        ;
        
    }
    // Je veux pouvoir modifier le commentaire d'un utilisateur
    // Je souhaite que la modification des commentaires n'apparaisse que en mode update(modification)
    // En tant qu'admin, je veux pouvoir modérer un commentaire si besoin est
    if ($options["custom_option"] !== "add") {
        $builder
        ->add('comments', TextType::class,[
            "label" => "Commentaire",
            "attr" => [
                "placeholder" => "Commentaire de l'utilisateur"
            ]
        ] );
    }
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'custom_option' => "default",
        ]);
    }
}
