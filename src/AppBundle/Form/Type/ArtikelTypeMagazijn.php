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
class ArtikelTypeMagazijn extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //todo: gebruikersrollen
        $builder
            ->add('artikelnummer', TextType::class, array(
        'disabled'   => true,
        ));

        $builder
            ->add('omschrijving', TextType::class, array(
                'disabled'   => true,
        ));
        $builder
            ->add('technishe_specificaties', TextType::class, array(
                'disabled'   => true,
            ));
        $builder
            ->add('inkoopprijs', MoneyType::class, array(
                'disabled'   => true,
            ));
        $builder
            ->add('vervangend_artikel', IntegerType::class, array(
                'disabled'   => true,
            ));
        $builder
            ->add('magazijnlocatie', TextType::class, array(
                'disabled'   => false,
                'label' => 'Magazijn loactie (00-99\A-Z00-99)',
                'data' => '05\AB\10',
            ));
        $builder
            ->add('minimum_voorraad', IntegerType::class, array(
                'disabled'   => true,
            ));
        $builder
            ->add('vooraad', IntegerType::class, array(
                'disabled'   => false,
            ));
        $builder
            ->add('bestelserie', TextType::class, array(
                'disabled'   => true,
            ));
    }
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\artikel', //Entiteit vervangen door b.v. Klant
		));
	}
}

?>