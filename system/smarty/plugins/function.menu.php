<?php
/**
 * 权限菜单控制
 *
 * @author lowxp
 *
 * Date: 13-3-14
 * Time: 下午5:10
 */

/**
 * param $params
 *     type audit:审核流程菜单,role:权限管理菜单
 * param $template
 */
function smarty_function_menu(){
    $segments = Loader::getInstance()->segments;
    if(!isset($segments[0]))return;
    $mode = '/'.$segments[0];

    $menus = array();
    $indexKey = 0;
    foreach($_SERVER['canAccessMods'] as $val){
        if($val['parent']==0 && strpos($val['url'],$mode)===0){
            $indexKey = $val['id'];
        }
        $menus[$val['parent']][$val['id']] = $val;
    }

    #$action = $_SERVER['config']['action'];
//    if($_SERVER['config']['method']=='index'){
//        $action = $_SERVER['config']['class'];
//    }else{
//        $action = $_SERVER['config']['class'].'/'.$_SERVER['config']['method'];
//    }

    $segments = Loader::getInstance()->segments;
    if(count($segments)>2)array_pop($segments);

    $action = implode('/',$segments);

    if(isset($_SERVER['config']['active'])){
        $action = $_SERVER['config']['active'];
    }

    $str = get_nav_list($menus, $indexKey, $action);
    echo $str;
}

/**
 * 生成菜单
 * @param $data
 * @param $key
 * @param string $link
 * @return string
 */
function get_nav_list($data, $key, $link=''){
    static $firstKey,$action;
    if(is_null($firstKey))$firstKey = $key;
    if(is_null($action))$action = $link;
    $index = 0;

    $str = '';
    if(is_array($data[$key])){
        if($key==$firstKey)$str.= '<div class="well span2" style="padding: 5px 0;"><ul class="nav nav-list">';

        if(isset($data[0][$key]['name']))$str.= '<li class="nav-header">'.$data[0][$key]['name'].'</li>';

        foreach($data[$key] as $k=>$v){

            #echo $action.'!'.$v['url'].'/<br />';
            if(isset($data[$k])){
                if($key==$firstKey && $index++>0)$str.= '<li class="divider"></li>';
                $str.= '<li class="nav-header">'.$v['name'].'</li>';
                $str.= get_nav_list($data,$k);
            }else{
                $url1 = ltrim($v['url'],'/');
                $url2 = $action;
                if(strlen($action)>strlen($url1)){
                    $url2 = $url1;
                    $url1 = $action;
                }
                //strpos($v['url'].'/',$action)

                $str.= '<li'.(strpos($url1,$url2)!==false?' class="active"':'').'><a href="/'.ltrim($v['url'],'/').'">'.$v['name'].'</a></li>';
            }
        }
        if($key==$firstKey)$str.= '</ul></div>';
    }

    return $str;
}