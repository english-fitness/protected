<?php $this->renderPartial('courseTab',array('course'=>$course)); ?>
<div class="session" style="margin: 10px">
    <div class="list">
        <table class="table table-bordered table-striped data-grid">
            <thead>
            <tr>
                <th>Chủ đề buổi học</th>
                <th class="w150">Giáo viên</th>
                <th class="w120">Ngày học</th>
                <th class="w110">Thời gian học</th>
                <th class="w100">Trạng thái</th>
                <th class="w100">Vào lớp</th>
            </tr>
            </thead>
            <tbody>
            <?php if($sessions):
            	foreach($sessions as $item):
            		$statusCssClass = ($item->status==Session::STATUS_CANCELED)? 'clrOrange':"";
            ?>
                <tr class="even">
                    <td>
                        <a href="<?php echo Yii::app()->baseUrl; ?>/student/class/session/id/<?php echo $item->id?>"><?php echo $item->subject?></a>
                    </td>
                    <td class="<?php echo $statusCssClass;?>"><?php echo ($item->teacher_id)? $item->getTeacher(): "Chưa xác định"; ?></td>
                    <td class="<?php echo $statusCssClass;?>"><?php echo Common::formatDate($item->plan_start); ?></td>
                    <td class="<?php echo $statusCssClass;?>"><?php echo Common::formatDuration($item->plan_start,$item->plan_duration); ?></td>
                    <td class="<?php echo $statusCssClass;?>"><?php echo $item->getStatus(); ?></td>
                    <td>
                        <?php if($item->checkDisplayBoard()):?>
                            <div class="go">
                                <?php ClsSession::displayEnterBoardButton($item->whiteboard); ?>
                            </div>
                        <?php else:?>
                            <span><?php echo $item->displayRemainTime();?></span>
                        <?php endif;?>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody></table>
        <?php else:?>
            <div class="row">Hiện tại chưa có buổi học trong lớp</div>
        <?php endif;?>
    </div>
</div>
<!--.class-->