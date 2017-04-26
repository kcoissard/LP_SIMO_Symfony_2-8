<?php

namespace sil14\VitrineBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use sil14\VitrineBundle\Entity\Categorie;

class RechercheFiltreArticles extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('Categories', 'entity', 
                array('class' => 'VitrineBundle:Categorie', 'property' => 'libelle', 'empty_value' => 'Tout', 'required' => false));
    }

    public function getName(){
       return 'formulaire_filtrage_articles';
    }
}

