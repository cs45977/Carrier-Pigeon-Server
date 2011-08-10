
<table cellpadding="<?= $table_cellpadding ?>" cellspacing="<?= $table_cellspacing ?>" border="<?= $table_border ?>" class="<?= $table_class ?>" id="<?= $table_id ?>" width="100%">

    <thead>
        <tr>
            <?php if(count($header) >= 1) { ?>
                <?php foreach($header as $key => $value) { ?>
                <th width="<?= $value['width'] ?>%" class="<?= $value['class'] ?>"><?= $key ?></th>
                <?php } ?>
            <?php } ?>
        </tr>
    </thead>

    <tbody>



        <?php
        
            foreach($data as $row){
                echo"<tr>";

                    
                    foreach($row as $field => $value){

                        switch($field){
                            case"user_name":
                                    
                            case "display_name":
                                $value = <<<VALUE
                                   <span  class='clickable view_profile'  user='{$row['id']}'>{$value}</span>
VALUE;
                            break;
                            case"created":
                               $value = date($this->config->item('read_date_format'), strtotime($value));
                                break;
                            case "msg_type":
                                $msgTpe = "MESSAGE_TYPE_".$value;
                                $value = $this->lang->line($msgTpe);
                                break;
                            default :
                                $value = $value;
                        }

                        echo "<td>".$value."</td>";
                    }

                echo"</tr>";
            }
        ?>




    </tbody>

    <tfoot>
        <tr>
            <?php if(count($footer) >= 1) { ?>
                <?php foreach($footer as $key => $value) { ?>
                <th width="<?= $value['width'] ?>%" class="<?= $value['class'] ?>"><?= $key ?></th>
                <?php } ?>
            <?php } ?>
        </tr>
    </tfoot>
    
</table>

