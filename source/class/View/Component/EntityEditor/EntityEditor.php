<?php

namespace Planck\Extension\EntityEditor\View\Component;


use Phi\HTML\Element\Button;
use Phi\HTML\Element\Form;
use Phi\HTML\Element\Input;
use Phi\HTML\Element\Table;
use Phi\HTML\Element\Td;
use Phi\HTML\Element\Textarea;
use Phi\HTML\Element\Th;
use Phi\HTML\Element\Tr;
use Planck\Helper\StringUtil;
use Planck\Model\Entity;
use Planck\Model\FieldDescriptor;
use Planck\View\Component;

class EntityEditor extends Component
{

    /**
     * @var Entity
     */
    private $entity;

    public function __construct($entity = null)
    {
        parent::__construct('div');

        if($entity) {
            $this->entity = $entity;
        }
    }

    public function loadEntityByFingerPrint($fingerPrint)
    {
        $this->entity = $this->getApplication()->getModelInstanceByFingerPrint($fingerPrint);
        return $this;
    }


    public function loadEntityByAttributes($entityType, $attributes)
    {
        $entityType = StringUtil::separatedToClassName($entityType, '.');

        $this->entity = $this->getApplication()->getModelEntity($entityType);
        $this->entity->loadBy($attributes);
        return $this;
    }



    public function build()
    {

        parent::build();

        $this->dom->addClass('plk-entity-editor-container');

        $form = new Form();
        $form->setMethod('post');
        $form->setAction('?/@extension/planck-extension-entity_editor/entity/api[save]');   //&redirect=?


        $fingerprint = new Input();
        $fingerprint->setValue($this->entity->getFingerPrint());
        $fingerprint->setName('entity[_fingerprint]');
        $fingerprint->css('display', 'none');

        $form->append($fingerprint);


        $table = new Table();
        $table->addClass('plk-entity-editor');
        $table->addClass('plk-box');
        $table->setHeaders(array(
            'Propriété',
            'Valeur'
        ));

        $form->append($table);

        $button = new Button();
        $button->setAttribute('type', 'submit');
        $button->setLabel('Enregistrer');
        $form->append($button);



        foreach ($this->entity->getDescriptor()->getFields() as $fieldDescriptor) {

            $tr = $table->addRow();

            $tr->addClass('plk-field-container');

                $tr->th->label->html(
                    $this->getLabelFromFieldDescriptor($fieldDescriptor)
                );


                if($fieldDescriptor->isPrimaryKey()) {
                    $tr->th->label->addClass('is-primary-key');
                }


                $input = $this->getInputFromFieldDescriptor($fieldDescriptor);

                $tr->td->html($input);
        }

        $this->dom->append($form);
        return $this;

    }


    public function getLabelFromFieldDescriptor(FieldDescriptor $fieldDescriptor)
    {
        return $fieldDescriptor->getLabel();
    }

    public function getInputFromFieldDescriptor(FieldDescriptor $descriptor)
    {
        $propertyName = $descriptor->getName();
        $value = $this->entity->getValue($propertyName);


        if($value === null && $descriptor->getDefaultValue()) {
            $value = $descriptor->getDefaultValue();
        }

        $foreignKeys = $this->entity->getForeignKeys();


        if(array_key_exists($propertyName, $foreignKeys)) {
            return $this->getEntityInput($foreignKeys[$propertyName], $descriptor);
        }


        if($descriptor->getType() == FieldDescriptor::TYPE_TEXT) {
            $input = new Textarea();
            $input->setValue($value);

        }
        else {
            $input = new Input();
            $input->setValue($value);
        }

        if($descriptor->isInt()) {
            $input->setAttribute('type', 'number');
            $input->setValue($value);
        }
        if($descriptor->isDate()) {
            $input->setAttribute('type', 'datetime-local');

            $time = strtotime($value);

            //print_r(date('Y-m-d\TH:i', $time));
            //exit();

            $input->setValue(
                date('Y-m-d\TH:i', $time)
            );
        }



        if($descriptor->isPrimaryKey()) {
            $input->setAttribute('readonly', '');
            $input->setValue($value);
        }



        $input->setName('entity['.$propertyName.']');
        $input->setAttribute('data-real-type', $descriptor->getType());
        $input->setAttribute('data-behaviour', 'interactive');



        return $input;
    }

    public function getEntityInput($entityClassName, FieldDescriptor $descriptor)
    {

        $propertyName = $descriptor->getName();
        $value = $this->entity->getValue($propertyName);

        $input = new Button();
        $input->setAttribute('data-behaviour', 'interactive');
        $input->setLabel('Selection ['.$value.']');
        $input->setValue($value);
        $input->setName('entity['.$propertyName.']');
        $input->setAttribute('data-real-type', $descriptor->getType());
        $input->setAttribute('data-entity-type', $entityClassName);
        $input->addClass('plk-entity-chooser');



        return $input;

    }


}


