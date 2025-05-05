<?php   

if(!function_exists('convert_price')){
    function convert_price(string $price = ''){
        return str_replace('.','', $price);
    }
}
//chuyeern sang mang 1 chieu
if(!function_exists('convert_array')){
    function convert_array($system = null , $keyword = '', $value = ''){
        $temp = [];
        if(is_array($system)) {
            foreach($system as $key => $val) {
                $temp[$val[$keyword]] = $val[$value];
            }
        }
        if(is_object($system)) {
            foreach($system as $key => $val) {
                $temp[$val->{$keyword}] = $val->{$value}; 
            }
        }
        return $temp;
    }
}
if(!function_exists('renderSystemInput')){
    function renderSystemInput(string $name = '', $systems = null){
        return '<input 
                    type="text" 
                    name="config['.$name.']"
                    value="'.old($name, ($systems[$name]) ?? '').'"
                    class="form-control"
                    placeholder=""
                    autocomplete="off"
                >';
    }
}
if(!function_exists('renderSystemImages')){
    function renderSystemImages(string $name = '', $systems = null){
        return '<input 
                    type="text" 
                    name="config['.$name.']"
                    value="'.old($name, ($systems[$name]) ?? '').'"
                    class="form-control upload-image"
                    placeholder=""
                    autocomplete="off"
                >';
    }
}
if(!function_exists('renderSystemTextarea')){
    function renderSystemTextarea(string $name = '', $systems = null){
        return '<textarea name="config['.$name.']" class="form-control system-textarea">'.old($name, ($systems[$name]) ?? '').'</textarea>';
    }
}
if(!function_exists('renderSystemLink')){
    function renderSystemLink(array $item = []){
        return (isset($item['link'])) ? '<a class="system-link" target="'.$item['link']['target'].'" href="'.$item['link']['href'].'">'.$item['link']['text'].'</a>' : '';
    }
}
if(!function_exists('renderSystemSelect')){
    function renderSystemSelect(array $item = [], string $name = '', $systems = null){
        $html = '<select name="config['.$name.']" class="form-control">';
            foreach($item['option'] as $key => $val) {
                $html .= '<option '.((isset($systems[$name]) && $key == ($systems[$name]) ?? '') ? 'selected' : '').' value="'.$key.'">'.$val.'</option>';
            }
        $html .= '</select>';
        return $html;
    }
    if(!function_exists('renderSystemEditor')){
        function renderSystemEditor(string $name = '', $systems = null){
            return '<textarea name="config['.$name.']" id="'.$name.'" class="form-control system-textarea ck-editor">'.old($name, ($systems[$name]) ?? '').'</textarea>';
        }
    }

}
if(!function_exists('recursive')){
    function recursive($data, $parentId = 0){
        $temp = [];
        if(!is_null($data) && count($data)) {
            foreach($data as $key => $val) {
                if($val->parent_id == $parentId) {
                    $temp[] = [
                        'item' => $val,
                        'children' => recursive($data, $val->id)
                    ];
                }
            }
        }
        return $temp;
    }
}

if(!function_exists('recursive_menu')){
    function recursive_menu($data){
        $html = '';
        if(count($data)) {
            foreach($data as $key => $val) {
                $itemId = $val['item']->id;
                $itemName = $val['item']->languages->first()->pivot->name;
                $itemUrl = route('menu.children', ['id' => $itemId]);
                $html .= "<li class='dd-item' data-id='$itemId'>";
                    $html .= "<div class='dd-handle'>";
                        $html .= "<span class='label label-info'><i class='fa fa-arrows'></i></span> $itemName";
                    $html .= "</div>";
                    $html .= "<a class='create-children-menu' href='$itemUrl'>Quản lý Menu con</a>";
                    if(count($val['children'])) {
                    $html .= "<ol class='dd-list'>";
                        $html .= recursive_menu($val['children']);
                    $html .= "</ol>";
                    }
                $html .= "</li>";
            }
        }
        return $html;
    }
}
if(!function_exists('loadClass')) {
    function loadClass(string $model = '', $folder = 'Repositories', $interface = 'Repository' ) {
        $serviceInterfaceNamespacce = '\App\\'.$folder.'\\'.ucfirst($model).$interface;
        if(class_exists($serviceInterfaceNamespacce)) {
            $serviceInstance = app($serviceInterfaceNamespacce);
        }
        return $serviceInstance;
    }
}
if(!function_exists('convertArrayByKey')) {
    function convertArrayByKey($object = null, $fields = []) {
        $temp = [];
        foreach($object as $key => $val) {
            foreach($fields as $field) {
                if(is_array($object)) {
                    $temp[$field][] = $val[$field];
                }else {
                    $extract = explode('.', $field);
                    if(count($extract) == 2) {
                        $temp[$extract[0]][] = $val->{$extract[1]}->first()->pivot->{$extract[0]};
                    } else {
                        $temp[$field][] = $val->{$field};
                    }
                }
            }
        }
        return $temp;
    }
}