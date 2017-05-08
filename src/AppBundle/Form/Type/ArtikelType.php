<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

//vul aan als je andere invoerveld-typen wilt gebruiken in je formulier
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

//EntiteitType vervangen door b.v. KlantType
class ArtikelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		//gebruiken wat je nodig hebt, de id hoeft er niet bij als deze auto increment is
        $builder
            ->add('artikelnummer', TextType::class) //naam is b.v. een attribuut of variabele van klant
        ;
        $builder
            ->add('omschrijving', TextType::class) //naam is b.v. een attribuut of variabele van klant
        ;
        $builder
            ->add('technishe_specificaties', TextType::class) //naam is b.v. een attribuut of variabele van klant
        ;
        $builder
            ->add('magazijnlocatie', IntegerType::class) //naam is b.v. een attribuut of variabele van klant
        ;
        $builder
            ->add('inkoopprijs', MoneyType::class) //naam is b.v. een attribuut of variabele van klant
        ;
        $builder
            ->add('vervangend_artikel', IntegerType::class) //naam is b.v. een attribuut of variabele van klant
        ;
        $builder
            ->add('minimum_voorraad', IntegerType::class) //naam is b.v. een attribuut of variabele van klant
        ;
        $builder
            ->add('vooraad', IntegerType::class) //naam is b.v. een attribuut of variabele van klant
        ;
        $builder
            ->add('bestelserie', IntegerType::class) //naam is b.v. een attribuut of variabele van klant
        ;
		//zie
		//http://symfony.com/doc/current/forms.html#built-in-field-types
		//voor meer typen invoer
    }
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\artikel', //Entiteit vervangen door b.v. Klant
		));
	}
}

?>