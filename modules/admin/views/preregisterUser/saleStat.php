<?php
/* @var $this PreregisterUserController */
/* @var $model PreregisterUser */

$this->breadcrumbs=array(
	'Preregister Users'=>array('index'),
	'Manage',
);
?>
<?php
    function generateOptions($key){
        $options = UtmSaleStat::getFilterValues($key);
        echo "<option>All</option>";
        foreach($options as $param){
            $selected = isset($_REQUEST['p'][$key]) && $_REQUEST['p'][$key] == $param ? "selected" : "";
            echo '<option ' . $selected . ' value="' . $param . '">'.$param.'</option>';
        }
    }
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
    	<?php 
    		$pageTitle = 'Registration Statistics';
    	?>
        <h2 class="page-title mT10"><?php echo $pageTitle;?></h2>
    </div>
</div>
<div class="row">
    <div class="col col-lg-3">
        <label for="utm_campaign">Campaign</label>
        <select id="utm_campaign" name="p[utm_campaign]" class="statFilter">
            <?php generateOptions('utm_campaign')?>
        </select>
    </div>
    <div class="col col-lg-3">
        <label for="utm_source">Source</label>
        <select id="utm_source" name="p[utm_source]" class="statFilter">
            <?php generateOptions('utm_source')?>
        </select>
    </div>
    <div class="col col-lg-3">
        <label for="utm_medium">Medium</label>
        <select id="utm_medium" name="p[utm_medium]" class="statFilter">
            <?php generateOptions('utm_medium')?>
        </select>
    </div>
</div>
<div class="row">
    <div class="col col-lg-3">
        <label for="utm_term">Term</label>
        <select id="utm_term" name="p[utm_term]" class="statFilter">
            <?php generateOptions('utm_term')?>
        </select>
    </div>
    <div class="col col-lg-3">
        <label for="utm_content">Content</label>
        <select id="utm_content" name="p[utm_content]" class="statFilter">
            <?php generateOptions('utm_content')?>
        </select>
    </div>
</div>
<?php $this->renderPartial('saleGridView', array('model'=>$model))?>
<script>
    $(".statFilter").change(function(){
        var query = "";
        $(".statFilter").each(function(){
            if (this.value != "All"){
                query += this.getAttribute('name') + "=" + this.value + "&";
            }
        });
        query = query.substr(0, query.length - 1);
        
        window.location.href = "<?php echo Yii::app()->controller->createUrl(Yii::app()->controller->action->id);?>?" + query;
    });
</script>