<!-- not using tab anymore
<div class="page-title"><label class="tabPage"> The training was completed</label></div>
-->
<div class="page-title"><p style="color:#ffffff; text-align:center; font-size:20px;">Completed Sessions</p></div>
<?php $this->renderPartial('myCourseTab'); ?>
<div class="details-class">
<link rel="stylesheet" type="text/css" href="/media/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/media/css/calendar.css" />
<link rel="stylesheet" type="text/css" href="/media/css/bootstrap-theme.min.css" />
<link rel="stylesheet" type="text/css" href="/media/js/bootstrap.min.js" />
<link rel="stylesheet" type="text/css" href="/media/js/jquery.min.js" />
<form method="POST">
    <table class="table-calendar">
        <thead>
                <tr>
                    <th class="calendar-th">Time</th>
                    <th class="calendar-th">Monday</th>
                    <th class="calendar-th">Tuesday</th>
                    <th class="calendar-th">Wednesday</th>
                    <th class="calendar-th">Thursday</th>
                    <th class="calendar-th">Friday</th>
                    <th class="calendar-th">Saturday</th>
                    <th class="calendar-th">Sunday</th>
                </tr>
        </thead>
        <tbody>
            <?php
            $vitri=0;
                for($i=540;$i<1380;$i=$i+40){
                    
                    $vitri++;
                    
                    $h1=(int)($i/60);
                    $m1=$i-60*$h1;
                    if($h1<10){
                        $h1='0'.$h1;
                    }
                    if($m1==0){
                        $m1='00';
                    }

                    $h2=(int)(($i+40)/60);
                    $m2=$i+40-60*$h2;
                    if($h2<10){
                        $h2='0'.$h2;
                    }
                    if($m2==0){
                        $m2='00';
                        $m2='50';
                        $h2=$h2-1;
                    }  else {
                        $m2=$m2-10;
                    }
                   
            ?>
                <tr>
                    <td class="calendar-td">
                        <?php 
                            echo $h1.'h'.':'.$m1;
                            echo ' ~ ';
                            echo $h2.'h'.':'.$m2;
                        ?>
                    </td>
                    <?php
                    for($j=0;$j<7;$j++){
                    ?>
                    <td class="calendar-td">
                        <?php
                        $toado= ($j*21+$vitri-1);
                            if($calendars!=""){
                                if($calendars[$toado]==1){
                        ?>
                            <select name="calendar[<?php echo $toado;?>]">
                                <option value="1" selected="selected">Available</options>
                                <option value="0" > </options>
                            </select>
                        <?php 
                                }
                                else{
                        ?>
                            <select name="calendar[<?php echo $toado;?>]">
                                <option value="1" >Available</options>
                                <option value="0" selected="selected"> </options>
                            </select>
                        <?php
                                }
                        }else{
                        ?>
                            <select name="calendar[<?php echo $toado;?>]">
                                <option value="1" >Available</options>
                                <option value="0" selected="selected"> </options>
                            </select>
                        <?php 
                        }
                        ?>
                    </td>
                    <?php
                    }
                    ?>
                </tr>
            <?php
                }
            ?>
        </tbody>
         <thead>
                <tr>
                    <th class="calendar-th">Time</th>
                    <th class="calendar-th">Monday</th>
                    <th class="calendar-th">Tuesday</th>
                    <th class="calendar-th">Wednesday</th>
                    <th class="calendar-th">Thursday</th>
                    <th class="calendar-th">Friday</th>
                    <th class="calendar-th">Saturday</th>
                    <th class="calendar-th">Sunday</th>
                </tr>
        </thead>
    </table>
    <div class="row">
        <div class="col-md-5">
            
        </div>
        <div class="col-md-7">
            <input type="submit" value="Submit" class="text-center gui" />
        </div>
    </div>
    
    
</form>

</div>
<!--.class-->
