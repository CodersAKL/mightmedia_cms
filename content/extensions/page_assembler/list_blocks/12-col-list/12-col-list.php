<div class="row d-flex" id='<?php echo $content['parentId'];?>' orderID='<?php echo $content['orderID'];?>' data-filename =  'list_blocks' data-filename = '12-col-list' onclick="addClassBox(this)">
    <div class="col-lg-12 crop">
        <div class="row d-flex">
            <ul class="list-group">
                <li class="list-group-item">
                    <span class="<?php echo $content[0]['style'];?>"><?php echo $content[0]['value'];?></span>
                </li>
                <li class="list-group-item">
                    <span class="<?php echo $content[1]['style'];?>"><?php echo $content[1]['value'];?></span>
                </li>
                <li class="list-group-item">
                    <span class="<?php echo $content[2]['style'];?>"><?php echo $content[2]['value'];?></span>
                </li>
                <li class="list-group-item">
                    <span class="<?php echo $content[3]['style'];?>"><?php echo $content[3]['value'];?></span>
                </li>
                <li class="list-group-item">
                    <span class="<?php echo $content[4]['style'];?>"><?php echo $content[4]['value'];?></span>
                </li>
            </ul>
        </div>
    </div>
</div>
