<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

//vul aan als je andere invoerveld-typen wilt gebruiken in je formulier
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

//EntiteitType vervangen door b.v. KlantType
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //todo: gebruikersrollen
        $builder->add('gebruikersnaam', TextType::class);
        $builder->add('wachtwoord', PasswordType::class);
        $builder->add('rol', IntegerType::class, array('label'=>'Gebruikers rol (Inkoper = 1, Magazijn = 2, Verkoper = 3)'));
    }
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\users', //Entiteit vervangen door b.v. Klant
		));
	}
}

?>