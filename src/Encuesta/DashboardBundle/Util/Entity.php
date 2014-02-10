<?php
namespace Encuesta\DashboardBundle\Util;

use Doctrine\Common\Persistence\ObjectManager;

class Entity
{
    protected $manager;

    public function __construct(ObjectManager $om)
    {
        $this->manager = $om;
    }

    public function persistEntityTranslations($entity, $array_translations = array())
    {
        $need_flush = false;
        $repository = $this->manager->getRepository('Gedmo\Translatable\Entity\Translation');
        $translationsDB = $this->getEntityTranslations($entity);

        foreach($array_translations as $key_translation => $translations) {
            foreach($translations as $locale => $translation) {
                if($translation != null && strlen(trim($translation)) > 0) {
                    $repository->translate($entity, $key_translation, $locale, trim($translation));
                    $this->manager->persist($entity);
                    $need_flush = true;
                }
                elseif(isset($translationsDB[$locale][$key_translation])) {
                    $o = $repository->findOneBy(array(
                        'field' => $key_translation,
                        'locale' => $locale,
                        'objectClass' => $this->manager->getClassMetadata(get_class($entity))->name,
                        'foreignKey' => $entity->getId()
                    ));
                    $this->manager->remove($o);
                    $need_flush = true;
                }
            }
        }

        return $need_flush;
    }

    public function getEntityTranslation($entity, $locale)
    {
        $translations= $this->getEntityTranslations($entity);

        return isset($translations[$locale]) ? $translations[$locale] : false;
    }

    public function getEntityTranslations($entity)
    {
        $repository = $this->manager->getRepository('Gedmo\Translatable\Entity\Translation');
        $translations = $repository->findTranslations($entity);

        return $translations;
    }
}