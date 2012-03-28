<?php
   // Yii::app()->clientScript->registerScriptFile('chrome://myapp/content/js/Sys.js'); // path is absolute ...
    Yii::app()->getClientScript()->registerCssFile('bindings/bindings.css');  // ...but we can give it relative
?>

<h1>Welcome!</h1>

<x:hbox>
    <x:mytag>
        <h2>Hello binding world!</h2>
    </x:mytag>
</x:hbox>