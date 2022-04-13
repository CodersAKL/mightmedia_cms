<div class="row d-flex" id='<?php echo $content['parentId'];?>' orderID='<?php echo $content['orderID'];?>' data-filename =  'text_blocks' data-filename = '2-col-text-1-img' onclick="addClassBox(this)">
    <div class="col-lg-8 crop">
        <div class="row d-flex">
            <div class="col-lg-6 text-justify pt-4">
                <span class="<?php echo $content[0]['style'];?>"><?php echo $content[0]['value'];?></span>
            </div>
            <div class="col-lg-6 text-justify pt-4">
                <span class="<?php echo $content['1']['style'];?>"><?php echo $content['1']['value'];?></span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 <?php echo $content[2]['style'];?>">
       <img src="<?php echo $content[2]['value'];?>" title ="<?php echo $content[3]['value'];?>" alt="<?php echo $content[4]['value'];?>" />
    </div>
</div>