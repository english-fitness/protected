<?php $this->renderPartial('courseTab',array('course'=>$course)); ?>
<div class="details-class">
    <div class="session">
        <div class="list">
            <table class="table table-bordered table-striped data-grid">
                    <thead>
                    <tr>
                        <th>Subjective</th>
                        <th class="w100">Date</th>
                        <th class="w100">Time slot</th>
                        <th class="w100">Status</th>
                        <th class="w100">Enter the class</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($sessions):
                    foreach($sessions as $session):
                    	$statusCssClass = ($session->status==Session::STATUS_CANCELED)? 'clrOrange':"";
                 ?>
                 <tr class="even">
                    <td>
                        <a href="<?php echo Yii::app()->baseUrl; ?>/teacher/class/session/id/<?php echo $session->id?>"><?php echo $session->subject?></a>
                    </td>
                     <td class="<?php echo $statusCssClass;?>"><?php echo Common::formatDate($session->plan_start); ?></td>
                     <td class="<?php echo $statusCssClass;?>"><?php echo Common::formatDuration($session->plan_start,$session->plan_duration); ?></td>
                     <td class="<?php echo $statusCssClass;?>"><?php echo $session->getStatus(); ?></td>
                    <td>
                        <?php if(!$session->checkDisplayBoard(10)):?>
                       	<p><span><?php echo $session->displayRemainTime();?></span></p>
	                    <?php endif;?>
	                	<?php if($session->checkDisplayBoard(10080)):?>
	                	<div class="go">                        
	                        <?php ClsSession::displayEnterBoardButton($session->whiteboard); ?>
	                    </div>
	                	<?php endif;?>
                    </td>
                </tr>
                <?php endforeach;?>                
                <?php else:?>
                <tr><td colspan="5">There are no lessons in the classroom!</td></tr>
                </tbody>
                <?php endif;?>
            </table>
        </div>
    </div>
</div>
<!--.class-->