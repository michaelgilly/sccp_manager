<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$def_val = null;
$dev_id = null;
if (!empty($_REQUEST['id'])) {
//    print_r($_REQUEST);
//    
// get model Info
    $dev_id = $_REQUEST['id'];
    $db_res = $this->get_db_SccpTableData('get_sccpdevice_byid', array("id" => $dev_id));
    foreach ($db_res as $key => $val) {
        if (!empty($val)) {
            switch ($key) {
                case 'name':
                    $key = 'mac';
                    $val = str_replace('SEP', '', $val);
                    $val = implode('.',sscanf($val, '%4s%4s%4s')); // Convert to Cisco display Format 
                    break;
                case '_hwlang':
                    $tmpar =  explode(":",$val);
                    $def_val['netlang'] =  array("keyword" => 'netlang', "data" => $tmpar[0], "seq" => "99");
                    $def_val['devlang'] =  array("keyword" => 'devlang', "data" => $tmpar[1], "seq" => "99");
                    break;
                case 'permit':
                case 'deny':
                    $def_val[$key . '_net'] = array("keyword" => $key, "data" => before('/', $val), "seq" => "99");
                    $key = $key . '_mask';
                    $val = after('/', $val);
                    break;
            }
            $def_val[$key] = array("keyword" => $key, "data" => $val, "seq" => "99");
        }
    }
}
?>

<form autocomplete="off" name="frm_adddevice" id="frm_adddevice" class="fpbx-submit" action="" method="post" data-id="hw_edit">
    <input type="hidden" name="category" value="adddevice_form">
    <input type="hidden" name="Submit" value="Submit">

    <?php
    if (empty($dev_id)){
        echo '<input type="hidden" name="sccp_deviceid" value="new">';
        echo $this->ShowGroup('sccp_hw_dev', 1, 'sccp_hw', $def_val);
    } else {
        echo '<input type="hidden" name="sccp_deviceid" value="'.$dev_id.'">';
        echo $this->ShowGroup('sccp_hw_dev_edit', 1, 'sccp_hw', $def_val);       
    }
    echo $this->ShowGroup('sccp_hw_dev2', 1, 'sccp_hw', $def_val);
    echo $this->ShowGroup('sccp_hw_dev_advance', 1, 'sccp_hw', $def_val);
    echo $this->ShowGroup('sccp_hw_dev_conference', 1, 'sccp_hw', $def_val);
    echo $this->ShowGroup('sccp_hw_dev_network', 1, 'sccp_hw', $def_val);
    ?>    
</form>
