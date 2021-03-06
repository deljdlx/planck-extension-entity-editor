<?php

namespace Planck\Extension\EntityEditor\View\Component\Form\Renderer;



use Planck\View\Component;

class Image extends Text
{



    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub




        $this->addCSSFile('vendor/jquery-cropper/dist/cropper.css');
        $this->addJavascriptFile('vendor/jquery-cropper/dist/jquery-cropper.js');
        $this->addJavascriptFile('vendor/jquery-cropper/dist/jquery-cropper-initialize.js');


        $this->addJavascriptFile('vendor/exif.js');

        $this->addJavascriptFile('vendor/Planck/source/Component/ImageUploader.js');
        $this->addJavascriptFile('vendor/Planck/source/Component/ImageChooser.js');
        $this->addJavascriptFile('vendor/Planck/source/Component/ImagePropertyChooser.js');


    }


    public function render()
    {


        $this->dom->html(
            $this->obInclude(__DIR__.'/template.php', $this->getVariables())
        );

        return Component::render();
    }

}
