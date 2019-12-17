<?php

namespace App\Forms\Cabinet;

use App\Entity\User;
use App\Security\Authenticators\FacebookAuthenticator;
use App\Security\Authenticators\GoogleAuthenticator;
use App\Security\Authenticators\VKontakteAuthenticator;
use Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserProfileType
 * @package App\Forms\Cabinet
 */
class UserProfileType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'locales' => null,
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locales = !empty($options['locales']) ? explode('|', $options['locales']) : [];
        $user = $options['data'] ?? null;
        /** @var User $user */
        if (!$user instanceof User ) {
            throw new Exception('Bad data!');
        }

        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('locale', ChoiceType::class, [
                'choices' => $locales,
                'choice_label' => function ($a) {
                    return $a;
                },
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('googleId', SocialButtonType::class, [
                'provider' => GoogleAuthenticator::PROVIDER_NAME,
                'class' => 'btn-google',
                'data' => $user->getGoogleId(),
            ])
            ->add('fbId', SocialButtonType::class, [
                'provider' => FacebookAuthenticator::PROVIDER_NAME,
                'class' => 'btn-facebook',
                'data' => $user->getFbId(),
            ])
            ->add('vkId', SocialButtonType::class, [
                'provider' => VKontakteAuthenticator::PROVIDER_NAME,
                'class' => 'btn-vkontakte',
                'data' => $user->getVkId(),
            ])
        ;
    }
}
