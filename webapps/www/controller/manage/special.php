<?php

/**
 * ZZCMS 商家管理
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */
class special extends Lowxp
{
    function __construct()
    {
        $this->btnMenu = array(
            0 => array('url' => '#!special/index', 'name' => '专题列表'),
            1 => array('url' => '#!special/edit', 'name' => '添加专题'),
            2 => array('url' => '#!special/model_index', 'name' => '模板列表'),
            3 => array('url' => '#!special/model_edit', 'name' => '添加模板'),
        );
        parent::__construct();
        $this->load->model('special');
        $this->sid = 0;
    }

    function index($page = 1)
    {
        $this->load->model('page');
        $_GET['page'] = intval($page);
        $this->page->set_vars(array('per' => (int)$this->common['page_listrows']));
        $sql = "select * from `###_special`";
        $data['list'] = $this->page->hashQuery($sql)->result_array();
        $this->smarty->assign('data', $data);
        $this->smarty->assign('btnNo', 0);
        $this->smarty->display('manage/special/index.html');
    }

    function edit($id = '')
    {
        if(isset($_POST['Submit'])){
            $res = $this->special->save($this->sid);
            if(isset($res['code']) && $res['code'] == 0){
                $this->tip($res['message'], array('inIframe' => true));
                $this->exeJs("parent.com.xhide();parent.main.refresh()");
            }else{
                $this->tip($res['message'], array('inIframe' => true, 'type' => 1));
            }
        }
        $data['special'] =  $this->special->selectModel($this->sid);
        if($id){
            $data['info'] = $this->special->getByIdSid($id, $this->sid);
            $special = $this->special->getByIdModel($data['info']['special_model_id']);
            $config = json_decode($special['config'], true);
            $config_value = json_decode($data['info']['config_value'], true);
            $html = $this->configToHTML($config, $config_value);
            $this->smarty->assign('html', $html);
            $data['info']['goods_id'] = explode(',', $data['info']['goods_id']);
        }
        $this->smarty->assign('data',$data);
        $this->smarty->assign('btnNo', 1);
        $this->smarty->display('manage/special/edit.html');
    }

    function del()
    {
        $id = intval($_REQUEST['id']);
        if ($id == 0)
            $this->error_msg('ID不能为空');
        $res = $this->special->delete($id, $this->sid);
        if (false !== $res) {
            $this->tip("操作成功", array('inIframe' => true));
            $this->exeJs("parent.com.xhide();parent.main.refresh()");
        } else {
            $this->error_msg('操作失败');
        }
    }

    function ajax_model_config()
    {
        $special_model_id = isset($_POST['special_model_id']) ? intval($_POST['special_model_id']) : 0;
        if(!$special_model_id)
            $data = array('code'=>10001, 'msg' => '参数异常');
        $special = $this->special->getByIdSidModel($special_model_id ,$this->sid);
        if(!$special)
            $data = array('code'=>10001, 'msg' => '专题模板不存在');
        $config = json_decode($special['config'], true);
        $html = $this->configToHTML($config);
        $data = array('code' => 0, 'html' => $html);
        echo json_encode($data);
    }

    function configToHTML($config ,$config_value = '')
    {
        $html = '';
        foreach($config as $k => $v){
            if(!empty($config_value[$k])){
                $value = $config_value[$k];
            }else{
                $value = '';
            }
            switch($v['type']){
                case 'image':
                    if(!empty($value)){
                        $image_value[0]['path'] = $config_value[$k]['path'][0];
                        $image_value[0]['title'] = $config_value[$k]['title'][0];
                        $value = json_encode($image_value);
                    }
                    $btn = $this->form->files($k, $value, '上传图片', array('maxnum' => 1));
                    $html .= "<tr class='special'><td class='td-label'><label>$v[label]：</label></td><td class='td-input'>$btn <span class='form-tip'>$v[tip]</span></td></tr>";
                    break;
                case 'text':
                    $html .= "<tr class='special'><td class='td-label'><label>$v[label]：</label></td><td class='td-input'><input class='form-i w360' type='text' name='post[$k]' value='$value'/> <span class='form-tip'>$v[tip]</span></td></tr>";
                    break;
                case 'textarea':
                    $html .= "<tr class='special'><td class='td-label'><label>$v[label]：</label></td><td class='td-input'><textarea rows='3' cols='20' name='post[$k]'>$value</textarea> <span class='form-tip'>$v[tip]</span></td></tr>";
                    break;
            }
        }
        return $html;
    }

    function model_index($page = 1)
    {
        $this->load->model('page');
        $_GET['page'] = intval($page);
        $this->page->set_vars(array('per' => (int)$this->common['page_listrows']));
        $sql = "select * from `###_special_model`";
        $data['list'] = $this->page->hashQuery($sql)->result_array();
        $this->smarty->assign('data', $data);
        $this->smarty->assign('btnNo', 2);
        $this->smarty->display('manage/special/model_index.html');
    }

    function model_edit($id = '')
    {
        if(isset($_POST['Submit'])){
            $res = $this->special->saveModel($this->sid);
            if(isset($res['code']) && $res['code'] == 0){
                $this->tip($res['message'], array('inIframe' => true));
                $this->exeJs("parent.com.xhide();parent.main.refresh()");
            }else{
                $this->tip($res['message'], array('inIframe' => true, 'type' => 1));
            }
        }
        if($id){
            $data['info'] = $this->special->getByIdSidModel($id, $this->sid);
            $data['info']['file_name'] = substr($data['info']['file_name'], 0, -5);
            $data['info']['config'] = json_decode($data['info']['config'], true);
            $this->smarty->assign('data',$data);
        }
        $this->smarty->assign('btnNo', 3);
        $this->smarty->display('manage/special/model_edit.html');
    }

    function del_model()
    {
        $id = intval($_REQUEST['id']);
        if ($id == 0)
            $this->error_msg('ID不能为空');
        $res = $this->special->deleteModel($id, $this->sid);
        if(isset($res['code']) && $res['code'] == 0){
            $this->tip($res['message'], array('inIframe' => true));
            $this->exeJs("parent.com.xhide();parent.main.refresh()");
        }else{
            $this->error_msg($res['message']);
        }
    }

    function error_msg($msg)
    {
        $this->tip($msg, array('inIframe' => true, 'type' => 1));
        exit;
    }
}