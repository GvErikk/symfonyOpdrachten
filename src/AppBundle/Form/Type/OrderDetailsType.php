<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

//vul aan als je andere invoerveld-typen wilt gebruiken in je formulier
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

//EntiteitType vervangen door b.v. KlantType
class OrderDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->ordernummer = $options['ordernummer'];

        //todo: gebruikersrollen
        $builder->add('orderId', HiddenType::class, array('data' => $this->ordernummer,));
        $builder->add('Artikelnummer', EntityType::class, array('class' => 'AppBundle:artikel','choice_label' => 'artikelnummer'));
        $builder->add('Aantal', IntegerType::class);
    }
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\orderdetails',
            'ordernummer' => null,
		));
	}
}

?>