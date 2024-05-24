<?php

// Html form generate
foreach ($json_inputs['inputs'] as $key => $value) {

    if ($value['type'] == "textarea") {
        // Html foreachs values itens
        $form_html .= '<div class="uk-width-1-1@s">
                            <label>'.$value['placeholder'].'</label>
                            <textarea class="uk-textarea" rows="5" type="'.$value['type'].'" id="'.$value['id'].'" placeholder="'.$value['placeholder'].'"></textarea>
                        </div>
        ';
    } else {

        // Html foreachs values itens
        $form_html .= '<div class="uk-width-1-1@s">
                            <label>'.$value['placeholder'].'</label>
                            <input class="uk-input" type="'.$value['type'].'" id="'.$value['id'].'" placeholder="'.$value['placeholder'].'">
                        </div>
                        ';
    }

        $form_ajax_1 .= 'var '.$value['id'].' = $("#'.$value['id'].'").val();
            ';

    $form_ajax_2 .= ''.$value['id'].': '.$value['id'].', ';
    $form_php_1 .= "".$value['id'].", ";
    $form_php_2 .= "?, ";
    $form_php_3 .= '$request->'.$value['id'].', ';
    $form_php_4 .= "'".$value['id']."' => '".$value['validator']."', ";
    $form_php_5 .= "'".$value['id']."' => '".$value['placeholder']."', ";
}